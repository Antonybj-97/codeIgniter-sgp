<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProcesoPimientaModel;
use App\Models\LoteEntradaModel;
use App\Models\DetalleProcesoMasivoModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Throwable;

class ProcesoController extends BaseController
{
    protected ProcesoPimientaModel $procesoModel;
    protected LoteEntradaModel $loteModel;
    protected DetalleProcesoMasivoModel $detalleModel;
    protected $db;

    private const TIPOS_PROCESO = ['Desgranado', 'Secado', 'Soplado', 'Empaque'];
    private const ESTADO_PENDIENTE = 'Pendiente';
    private const ESTADO_INICIADO = 'Iniciado';
    private const ESTADO_FINALIZADO = 'Finalizado';
    private const ESTADO_EN_PROCESO = 'en_proceso';

    public function __construct()
    {
        helper(['form', 'url', 'text', 'escaper']);
        $this->procesoModel = new ProcesoPimientaModel();
        $this->loteModel    = new LoteEntradaModel();
        $this->detalleModel = new DetalleProcesoMasivoModel();
        $this->db           = \Config\Database::connect();
    }

    /** Página principal */
    public function index(): string
    {
        try {
            return view('procesos/index');
        } catch (Exception $e) {
            $this->logError('index', $e);
            return redirect()->to(site_url('/'))->with('error', 'Error al cargar la vista de procesos.');
        }
    }

    /**
     * Base query reutilizable para procesos
     */
    private function baseProcesoQuery()
    {
        return $this->procesoModel->builder()
            ->select('procesos.*, lote_entrada.proveedor, centro.nombre AS centro')
            ->join('lote_entrada', 'lote_entrada.id = procesos.lote_entrada_id', 'left')
            ->join('centro', 'centro.id = lote_entrada.centro_id', 'left')
            ->where('procesos.deleted_at', null);
    }

    /** Helper de logging uniforme */
    private function logError(string $method, Throwable $e): void
    {
        log_message('error', sprintf('[ProcesoController::%s] %s', $method, $e->getMessage()));
    }

    /**
     * Endpoint AJAX para DataTables
     */
    public function ajaxList(): ResponseInterface
    {
        try {
            $postData = $this->request->getPost();
            $draw = (int)($postData['draw'] ?? 1);
            $start = (int)($postData['start'] ?? 0);
            $length = (int)($postData['length'] ?? 10);

            $builder = $this->baseProcesoQuery();
            $recordsTotal = (int)(clone $builder)->countAllResults(false);

            // Aplicar búsqueda si existe
            if (!empty($postData['search']['value'])) {
                $search = $postData['search']['value'];
                $builder->groupStart()
                    ->like('procesos.tipo_proceso', $search)
                    ->orLike('lote_entrada.proveedor', $search)
                    ->orLike('centro.nombre', $search)
                    ->orLike('procesos.estado_proceso', $search)
                    ->groupEnd();
            }

            $builder->orderBy('procesos.fecha_proceso', 'DESC');
            
            // Obtener total filtrado
            $recordsFiltered = (int)(clone $builder)->countAllResults(false);
            
            if ($length > 0) {
                $builder->limit($length, $start);
            }

            $procesos = $builder->get()->getResultArray();

            $data = array_map(function ($p) {
                return [
                    'id'                  => (int)($p['id'] ?? 0),
                    'fecha_proceso'       => $p['fecha_proceso'] ?? null,
                    'tipo_proceso'        => $p['tipo_proceso'] ?? '-',
                    'proveedor'           => $p['proveedor'] ?? '-',
                    'centro'              => $p['centro'] ?? '-', 
                    'peso_bruto_kg'       => (float)($p['peso_bruto_kg'] ?? 0),
                    'peso_estimado_kg'    => (float)($p['peso_estimado_kg'] ?? 0),
                    'peso_final_kg'       => (float)($p['peso_final_kg'] ?? 0),
                    'observacion_proceso' => $p['observacion_proceso'] ?? '',
                    'estado_proceso'      => $p['estado_proceso'] ?? self::ESTADO_PENDIENTE,
                    'es_proceso_masivo'   => (bool)($p['es_proceso_masivo'] ?? false),
                ];
            }, $procesos);

            return $this->response->setJSON([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]);
        } catch (Exception $e) {
            $this->logError('ajaxList', $e);
            return $this->response->setStatusCode(500)->setJSON([
                'draw' => $this->request->getPost('draw') ?? 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Error interno del servidor.'
            ]);
        }
    }

    /** Crear proceso individual */
    public function create(int $loteId): ResponseInterface
    {
        try {
            $lote = $this->loteModel->find($loteId);
            if (!$lote || ((float)($lote['peso_bruto_kg'] ?? 0)) <= 0 || !empty($lote['deleted_at'])) {
                return redirect()->to(site_url('lotes-entrada'))
                    ->with('error', 'Lote no encontrado o sin peso disponible.');
            }
            
            // Calcular peso disponible restando procesos activos
            $pesoProcesado = $this->procesoModel
                ->where('lote_entrada_id', $loteId)
                ->whereIn('estado_proceso', [self::ESTADO_EN_PROCESO, self::ESTADO_PENDIENTE, self::ESTADO_INICIADO])
                ->selectSum('peso_bruto_kg')
                ->get()
                ->getRow();
            
            $pesoProcesado = $pesoProcesado ? (float)$pesoProcesado->peso_bruto_kg : 0;
            $pesoDisponible = max(0, (float)$lote['peso_bruto_kg'] - $pesoProcesado);
            
            if ($pesoDisponible <= 0) {
                return redirect()->to(site_url('lotes-entrada'))
                    ->with('error', 'El lote no tiene peso disponible para nuevos procesos.');
            }
            
            $lote['peso_disponible'] = $pesoDisponible;
            
            return view('procesos/create', [
                'lote' => $lote,
                'tiposProceso' => self::TIPOS_PROCESO
            ]);
        } catch (Exception $e) {
            $this->logError('create', $e);
            return redirect()->to(site_url('lotes-entrada'))->with('error', 'Error al obtener el lote.');
        }
    }

    /** Reglas de validación centralizadas */
    private function getValidationRules(array $context = []): array
    {
        $baseRules = [
            'lote_id' => 'required|is_natural_no_zero',
            'tipo_proceso' => 'required|in_list[' . implode(',', self::TIPOS_PROCESO) . ']',
            'peso_bruto_kg' => 'required|decimal|greater_than[0]',
            'peso_estimado_kg' => 'permit_empty|decimal|greater_than_equal_to[0]'
        ];

        if (isset($context['max_peso'])) {
            $baseRules['peso_bruto_kg'] = 'required|decimal|greater_than[0]|less_than_equal_to[' . (float)$context['max_peso'] . ']';
        }

        return $baseRules;
    }

    /** Editar proceso masivo */
    /**
 * Edita un proceso de transformación masivo.
 * * @param int|string $id ID del proceso
 * @return ResponseInterface
 */
public function edit($id): ResponseInterface
{
    try {
        // 1. Obtener el proceso masivo principal con su relación a lote_entrada
        $procesoMasivo = $this->procesoModel
            ->select('procesos.*, lote_entrada.proveedor, lote_entrada.folio')
            ->join('lote_entrada', 'lote_entrada.id = procesos.lote_entrada_id', 'left')
            ->where('procesos.id', $id)
            ->where('procesos.deleted_at', null)
            ->first();

        // Validaciones iniciales
        if (!$procesoMasivo) {
            return redirect()->to('/procesos')->with('error', 'Proceso no encontrado.');
        }

        if (!$procesoMasivo['es_proceso_masivo']) {
            return redirect()->to('/procesos')->with('error', 'Este no es un proceso masivo.');
        }

        // 2. Obtener procesos relacionados (otros lotes que forman parte de esta misma transformación)
        $procesosRelacionados = [];
        $lotesSeleccionados = [];
        $pesosParciales = [];
        
        if (!empty($procesoMasivo['lote_proceso_id'])) {
            $procesosRelacionados = $this->procesoModel
                ->select('procesos.*, lote_entrada.proveedor, lote_entrada.folio')
                ->join('lote_entrada', 'lote_entrada.id = procesos.lote_entrada_id', 'left')
                ->where('procesos.lote_proceso_id', $procesoMasivo['lote_proceso_id'])
                ->where('procesos.deleted_at', null)
                ->findAll();

            // Mapear IDs seleccionados y sus pesos actuales en el proceso
            foreach ($procesosRelacionados as $proceso) {
                if (!empty($proceso['lote_entrada_id'])) {
                    $lotesSeleccionados[] = $proceso['lote_entrada_id'];
                    $pesosParciales[$proceso['lote_entrada_id']] = (float)$proceso['peso_bruto_kg'];
                }
            }
        }

        // 3. Obtener catálogo de lotes disponibles (con stock)
        $lotes = $this->loteModel
            ->select([
                'lote_entrada.id',
                'lote_entrada.folio',
                'lote_entrada.proveedor',
                'lote_entrada.peso_bruto_kg AS peso_original',
                'tipo_entrada.nombre AS tipo_entrada',
                'tipo_pimienta.nombre AS tipo_pimienta',
                'centro.nombre AS centro_acopio'
            ])
            ->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id', 'left')
            ->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id', 'left')
            ->join('centro', 'centro.id = lote_entrada.centro_id', 'left')
            ->where('lote_entrada.peso_bruto_kg >', 0)
            ->where('lote_entrada.deleted_at', null)
            ->orderBy('lote_entrada.id', 'ASC')
            ->findAll();

        // 4. Calcular disponibilidad real de cada lote
        foreach ($lotes as &$lote) {
            // Sumar peso comprometido en otros procesos activos
            $subQuery = $this->procesoModel
                ->where('lote_entrada_id', $lote['id'])
                // Excluimos este proceso específico para que el peso actual no reste disponibilidad al editar
                ->where('lote_proceso_id !=', $procesoMasivo['lote_proceso_id'] ?? '')
                ->whereIn('estado_proceso', [self::ESTADO_EN_PROCESO, self::ESTADO_PENDIENTE, self::ESTADO_INICIADO])
                ->selectSum('peso_bruto_kg')
                ->get()
                ->getRow();
            
            $pesoOcupado = $subQuery ? (float)$subQuery->peso_bruto_kg : 0;
            $lote['peso_disponible'] = max(0, (float)$lote['peso_original'] - $pesoOcupado);
            
            // Flags para la vista
            $lote['seleccionado'] = in_array($lote['id'], $lotesSeleccionados);
            $lote['peso_parcial'] = $pesosParciales[$lote['id']] ?? $lote['peso_disponible'];
        }

        // 5. Preparar filtros únicos para la interfaz
        $tiposEntrada  = array_values(array_unique(array_filter(array_column($lotes, 'tipo_entrada'))));
        $tiposPimienta = array_values(array_unique(array_filter(array_column($lotes, 'tipo_pimienta'))));
        $centrosAcopio = array_values(array_unique(array_filter(array_column($lotes, 'centro_acopio'))));

        $data = [
            'procesoMasivo'        => $procesoMasivo,
            'procesosRelacionados' => $procesosRelacionados,
            'lotes'                => $lotes,
            'lotesSeleccionados'   => $lotesSeleccionados,
            'pesosParciales'       => $pesosParciales,
            'tiposProceso'         => self::TIPOS_PROCESO,
            'tiposEntrada'         => $tiposEntrada,
            'tiposPimienta'        => $tiposPimienta,
            'centrosAcopio'        => $centrosAcopio
        ];

        // RETORNO CORRECTO: Envolvemos la vista en el objeto Response
        return $this->response->setBody(view('procesos/edit', $data));

    } catch (\Exception $e) {
        $this->logError('edit', $e);
        return redirect()->to('/procesos')->with('error', 'Ocurrió un error inesperado al cargar la edición.');
    }
}

    /** Actualizar proceso masivo */
    public function updateMasivo($id): RedirectResponse
    {
        $db = $this->db;
        $db->transBegin();

        try {
            // Validar que el proceso existe y es masivo
            $procesoMasivo = $this->procesoModel->find($id);
            if (!$procesoMasivo || !$procesoMasivo['es_proceso_masivo']) {
                return redirect()->to('/procesos')->with('error', 'Proceso masivo no encontrado');
            }

            $validation = Services::validation();
            
            $validationRules = [
                'tipo_proceso' => 'required|string',
                'lotes' => 'required',
            ];

            if (!$validation->setRules($validationRules)->run($this->request->getPost())) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $tipoProceso = $this->request->getPost('tipo_proceso');
            $lotesIds = $this->request->getPost('lotes') ?? [];
            $pesosParciales = $this->request->getPost('peso_parcial') ?? [];
            $pesosEstimados = $this->request->getPost('peso_estimado_final') ?? [];
            
            // Nuevos datos para procesos individuales
            $estados = $this->request->getPost('estados') ?? [];
            $observaciones = $this->request->getPost('observaciones') ?? [];
            $pesosFinales = $this->request->getPost('pesos_finales') ?? [];

            if (!is_array($lotesIds) || empty($lotesIds)) {
                return redirect()->back()->withInput()->with('error', 'Debe seleccionar al menos un lote.');
            }

            // Obtener procesos existentes para este proceso masivo
            $procesosExistentes = $this->procesoModel
                ->where('lote_proceso_id', $procesoMasivo['lote_proceso_id'])
                ->where('deleted_at', null)
                ->findAll();

            // Crear mapa de procesos existentes por lote_id
            $procesosPorLote = [];
            foreach ($procesosExistentes as $proceso) {
                $procesosPorLote[$proceso['lote_entrada_id']] = $proceso;
            }

            $procesosActualizados = 0;
            $procesosCreados = 0;

            foreach ($lotesIds as $loteId) {
                $loteId = (int)$loteId;
                $lote = $this->loteModel->find($loteId);
                
                if (!$lote) {
                    throw new Exception("Lote #{$loteId} no encontrado");
                }

                $pesoProcesar = isset($pesosParciales[$loteId]) ? (float)$pesosParciales[$loteId] : 0;
                
                if ($pesoProcesar <= 0) {
                    continue;
                }

                // Calcular peso disponible actual (excluyendo el proceso actual si existe)
                $builder = $this->procesoModel->builder();
                $builder->selectSum('peso_bruto_kg')
                    ->where('lote_entrada_id', $loteId)
                    ->whereIn('estado_proceso', [self::ESTADO_EN_PROCESO, self::ESTADO_PENDIENTE, self::ESTADO_INICIADO]);
                
                if (isset($procesosPorLote[$loteId])) {
                    $builder->where('id !=', $procesosPorLote[$loteId]['id']);
                }
                
                $pesoProcesado = $builder->get()->getRow();
                $pesoProcesado = $pesoProcesado ? (float)$pesoProcesado->peso_bruto_kg : 0;
                $pesoDisponible = max(0, (float)$lote['peso_bruto_kg'] - $pesoProcesado);

                if ($pesoProcesar > $pesoDisponible) {
                    throw new Exception("El peso a procesar ({$pesoProcesar} kg) excede el disponible ({$pesoDisponible} kg) para el lote #{$loteId}");
                }

                $pesoEstimado = isset($pesosEstimados[$loteId]) ? (float)$pesosEstimados[$loteId] : $pesoProcesar;

                // Verificar si ya existe un proceso para este lote en el proceso masivo
                if (isset($procesosPorLote[$loteId])) {
                    // Actualizar proceso existente
                    $procesoId = $procesosPorLote[$loteId]['id'];
                    $data = [
                        'tipo_proceso' => $tipoProceso,
                        'peso_bruto_kg' => $pesoProcesar,
                        'peso_estimado_kg' => $pesoEstimado,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    // Actualizar estado si existe
                    if (isset($estados[$procesoId])) {
                        $data['estado_proceso'] = $estados[$procesoId];
                        
                        // Si el estado es Finalizado, establecer fecha_fin
                        if ($estados[$procesoId] === self::ESTADO_FINALIZADO) {
                            $data['fecha_fin'] = date('Y-m-d H:i:s');
                        } elseif ($estados[$procesoId] === self::ESTADO_INICIADO) {
                            $data['fecha_proceso'] = date('Y-m-d H:i:s');
                        }
                    }

                    // Actualizar observaciones si existen
                    if (isset($observaciones[$procesoId])) {
                        $data['observacion_proceso'] = $observaciones[$procesoId];
                    }

                    // Actualizar peso final si existe
                    if (isset($pesosFinales[$procesoId]) && !empty($pesosFinales[$procesoId])) {
                        $pesoFinal = (float)$pesosFinales[$procesoId];
                        
                        // Validar que el peso final no sea mayor al peso bruto + 10%
                        $pesoMaximoPermitido = $pesoProcesar * 1.1;
                        if ($pesoFinal > $pesoMaximoPermitido) {
                            throw new Exception("El peso final ({$pesoFinal} kg) no puede ser mayor a " . number_format($pesoMaximoPermitido, 2) . " kg para el proceso #{$procesoId}");
                        }
                        
                        $data['peso_final_kg'] = $pesoFinal;
                    }

                    if ($this->procesoModel->update($procesoId, $data)) {
                        $procesosActualizados++;
                    }
                } else {
                    // Crear nuevo proceso
                    $data = [
                        'fecha_proceso' => date('Y-m-d H:i:s'),
                        'tipo_proceso' => $tipoProceso,
                        'observacion_proceso' => 'Proceso masivo - ' . $tipoProceso,
                        'proveedor' => $lote['proveedor'] ?? '',
                        'peso_bruto_kg' => $pesoProcesar,
                        'peso_estimado_kg' => $pesoEstimado,
                        'peso_final_kg' => 0,
                        'estado_proceso' => self::ESTADO_PENDIENTE,
                        'lote_entrada_id' => $loteId,
                        'tipo_entrada_id' => $lote['tipo_entrada_id'] ?? null,
                        'es_proceso_masivo' => 1,
                        'lote_proceso_id' => $procesoMasivo['lote_proceso_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    if ($this->procesoModel->insert($data)) {
                        $procesosCreados++;
                    }
                }
            }

            // Eliminar procesos que ya no están seleccionados
            foreach ($procesosPorLote as $loteId => $proceso) {
                if (!in_array($loteId, $lotesIds)) {
                    $this->procesoModel->delete($proceso['id']);
                }
            }

            $db->transCommit();
            
            $mensaje = "Proceso masivo actualizado correctamente.";
            if ($procesosActualizados > 0) {
                $mensaje .= " {$procesosActualizados} procesos actualizados.";
            }
            if ($procesosCreados > 0) {
                $mensaje .= " {$procesosCreados} procesos agregados.";
            }

            return redirect()->to(site_url('procesos'))->with('success', $mensaje);

        } catch (Exception $e) {
            $db->transRollback();
            $this->logError('updateMasivo', $e);
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /** Actualizar proceso individual */
    public function update($id): RedirectResponse
    {
        // Validar que el proceso existe
        $proceso = $this->procesoModel->find($id);
        if (!$proceso) {
            return redirect()->to(site_url('procesos'))->with('error', 'Proceso no encontrado');
        }
        
        // Validar campos requeridos
        $validation = Services::validation();
        
        $rules = [
            'tipo_proceso' => 'required|in_list[' . implode(',', self::TIPOS_PROCESO) . ']',
            'estado_proceso' => 'required|in_list[Pendiente,Iniciado,Finalizado]',
            'observacion_proceso' => 'permit_empty|string'
        ];
        
        // Validación condicional para peso final
        if ($this->request->getPost('estado_proceso') === self::ESTADO_FINALIZADO) {
            $rules['peso_final_kg'] = 'required|decimal|greater_than[0]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        // Preparar datos para actualizar
        $data = [
            'tipo_proceso' => $this->request->getPost('tipo_proceso'),
            'estado_proceso' => $this->request->getPost('estado_proceso'),
            'observacion_proceso' => $this->request->getPost('observacion_proceso'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Solo actualizar peso_final_kg si está presente y el estado es Finalizado
        if ($this->request->getPost('estado_proceso') === self::ESTADO_FINALIZADO) {
            $pesoFinal = (float)$this->request->getPost('peso_final_kg');
            
            // Validar que el peso final no sea mayor al peso bruto + 10%
            $pesoMaximoPermitido = (float)$proceso['peso_bruto_kg'] * 1.1;
            if ($pesoFinal > $pesoMaximoPermitido) {
                return redirect()->back()->withInput()->with('error', "El peso final no puede ser mayor a " . number_format($pesoMaximoPermitido, 2) . " kg");
            }
            
            $data['peso_final_kg'] = $pesoFinal;
            
            // Si se está finalizando el proceso y no tiene fecha_fin, establecerla
            if (empty($proceso['fecha_fin'])) {
                $data['fecha_fin'] = date('Y-m-d H:i:s');
            }
        } elseif ($this->request->getPost('estado_proceso') === self::ESTADO_INICIADO) {
            // Si se está iniciando, actualizar fecha_proceso
            if (empty($proceso['fecha_proceso']) || $proceso['estado_proceso'] === self::ESTADO_PENDIENTE) {
                $data['fecha_proceso'] = date('Y-m-d H:i:s');
            }
        }
        
        try {
            if ($this->procesoModel->update($id, $data)) {
                return redirect()->to(site_url('procesos'))->with('success', 'Proceso actualizado correctamente');
            } else {
                return redirect()->back()->withInput()->with('error', 'Error al actualizar el proceso');
            }
        } catch (Exception $e) {
            $this->logError('update', $e);
            return redirect()->back()->withInput()->with('error', 'Error del sistema al actualizar el proceso');
        }
    }

    /** Guardar proceso individual */
    public function store(): RedirectResponse
    {
        $db = $this->db;
        $db->transBegin();

        try {
            $loteId = (int)$this->request->getPost('lote_id');
            $tipoProceso = $this->request->getPost('tipo_proceso');
            $pesoBruto = (float)$this->request->getPost('peso_bruto_kg');
            $pesoEstimado = (float)($this->request->getPost('peso_estimado_kg') ?? $pesoBruto);

            $lote = $this->loteModel->find($loteId);
            if (!$lote || !empty($lote['deleted_at'])) {
                return redirect()->back()->withInput()->with('error', 'Lote no encontrado.');
            }

            if ($errorFlujo = $this->validarFlujoSecuencial($loteId, $tipoProceso)) {
                return redirect()->back()->withInput()->with('error', $errorFlujo);
            }

            $validation = Services::validation();
            $rules = $this->getValidationRules(['max_peso' => (float)($lote['peso_bruto_kg'] ?? 0)]);
            
            if (!$validation->setRules($rules)->run($this->request->getPost())) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            // Calcular peso disponible actual
            $pesoProcesado = $this->procesoModel
                ->where('lote_entrada_id', $loteId)
                ->whereIn('estado_proceso', [self::ESTADO_EN_PROCESO, self::ESTADO_PENDIENTE, self::ESTADO_INICIADO])
                ->selectSum('peso_bruto_kg')
                ->get()
                ->getRow();
            
            $pesoProcesado = $pesoProcesado ? (float)$pesoProcesado->peso_bruto_kg : 0;
            $pesoDisponible = max(0, (float)$lote['peso_bruto_kg'] - $pesoProcesado);

            if ($pesoBruto > $pesoDisponible) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', "El peso solicitado ({$pesoBruto} kg) excede el disponible ({$pesoDisponible} kg) para este lote.");
            }

            $data = [
                'fecha_proceso' => date('Y-m-d H:i:s'),
                'tipo_proceso' => $tipoProceso,
                'observacion_proceso' => $this->request->getPost('observaciones') ? esc($this->request->getPost('observaciones')) : null,
                'proveedor' => $lote['proveedor'] ?? null,
                'peso_bruto_kg' => $pesoBruto,
                'peso_estimado_kg' => $pesoEstimado,
                'peso_final_kg' => 0,
                'estado_proceso' => self::ESTADO_PENDIENTE,
                'lote_entrada_id' => $loteId,
                'tipo_entrada_id' => $lote['tipo_entrada_id'] ?? null,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            if (!$this->procesoModel->insert($data)) {
                throw new Exception('Error al insertar el proceso');
            }

            $db->transCommit();
            return redirect()->to(site_url('procesos'))->with('success', 'Proceso creado correctamente.');
        } catch (Throwable $e) {
            $db->transRollback();
            $this->logError('store', $e);
            return redirect()->back()->withInput()->with('error', 'Error al guardar el proceso: ' . $e->getMessage());
        }
    }

    /** Iniciar proceso individual */
    public function iniciar(int $id): RedirectResponse
    {
        try {
            $proceso = $this->procesoModel->find($id);
            if (!$proceso || !empty($proceso['deleted_at'])) {
                return redirect()->to(site_url('procesos'))->with('error', 'Proceso no encontrado.');
            }

            if (($proceso['estado_proceso'] ?? '') !== self::ESTADO_PENDIENTE) {
                return redirect()->back()->with('error', 'El proceso no puede iniciarse en este estado.');
            }

            $this->procesoModel->update($id, [
                'estado_proceso' => self::ESTADO_INICIADO,
                'fecha_proceso' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->to(site_url('procesos'))->with('success', 'Proceso iniciado correctamente.');
        } catch (Exception $e) {
            $this->logError('iniciar', $e);
            return redirect()->to(site_url('procesos'))->with('error', 'Error al iniciar el proceso.');
        }
    }

    /** Finalizar proceso individual */
    public function finalizar(int $id): RedirectResponse
    {
        try {
            $proceso = $this->procesoModel->find($id);

            if (!$proceso) {
                return redirect()->back()->with('error', 'Proceso no encontrado');
            }

            if (($proceso['estado_proceso'] ?? '') !== self::ESTADO_INICIADO) {
                return redirect()->back()->with('error', 'No se puede finalizar un proceso que no está iniciado');
            }

            $this->procesoModel->update($id, [
                'estado_proceso' => self::ESTADO_FINALIZADO,
                'fecha_fin' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->to(site_url('procesos'))->with('success', 'Proceso finalizado correctamente');
        } catch (Exception $e) {
            $this->logError('finalizar', $e);
            return redirect()->back()->with('error', 'Error al finalizar el proceso');
        }
    }

    /** Formulario procesos masivos optimizado */
   /** Formulario procesos masivos optimizado */
public function crearMasivo(): ResponseInterface
{
    try {
        // 1. Definir estados que bloquean el stock disponible
        $estadosActivos = [self::ESTADO_EN_PROCESO, self::ESTADO_PENDIENTE, self::ESTADO_INICIADO];

        // 2. Construir subconsulta para obtener pesos ocupados por procesos actuales
        $subquery = $this->procesoModel->builder()
            ->select('lote_entrada_id, SUM(peso_bruto_kg) as suma_procesado')
            ->whereIn('estado_proceso', $estadosActivos)
            ->groupBy('lote_entrada_id')
            ->getCompiledSelect();

        // 3. Consulta principal con Joins
        $lotes = $this->loteModel
            ->select([
                'lote_entrada.id',
                'lote_entrada.folio',
                'lote_entrada.proveedor',
                'lote_entrada.peso_bruto_kg AS peso_original',
                'tipo_entrada.nombre AS tipo_entrada',
                'tipo_pimienta.nombre AS tipo_pimienta',
                'centro.nombre AS centro_acopio',
                'COALESCE(resumen_procesos.suma_procesado, 0) AS peso_en_uso'
            ])
            ->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id', 'left')
            ->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id', 'left')
            ->join('centro', 'centro.id = lote_entrada.centro_id', 'left')
            ->join("($subquery) AS resumen_procesos", 'resumen_procesos.lote_entrada_id = lote_entrada.id', 'left')
            ->where('lote_entrada.peso_bruto_kg >', 0)
            ->where('lote_entrada.deleted_at', null)
            ->orderBy('lote_entrada.id', 'ASC')
            ->findAll();

        // 4. Calcular disponibilidad y limpiar filtros
        foreach ($lotes as &$lote) {
            $lote['peso_disponible'] = max(0, (float)$lote['peso_original'] - (float)$lote['peso_en_uso']);
        }

        // Helper para extraer opciones únicas para los filtros del frontend
        $extraerUnicos = function($columna) use ($lotes) {
            return array_values(array_unique(array_filter(array_column($lotes, $columna))));
        };

        // Crear respuesta con la vista
        $response = service('response');
        $output = view('procesos/create_masivo', [
            'lotes'          => $lotes,
            'tiposProceso'   => self::TIPOS_PROCESO,
            'tiposEntrada'   => $extraerUnicos('tipo_entrada'),
            'tiposPimienta'  => $extraerUnicos('tipo_pimienta'),
            'centrosAcopio'  => $extraerUnicos('centro_acopio')
        ]);
        
        return $response->setBody($output);

    } catch (Exception $e) {
        $this->logError('crearMasivo', $e);
        return redirect()->to(site_url('procesos'))
            ->with('error', 'No se pudo cargar el formulario masivo debido a un error interno.');
    }
}

    /** Iniciar procesos masivos usando el nuevo modelo */
    public function iniciarMasivo(): RedirectResponse
    {
        $db = $this->db;
        $db->transBegin();

        try {
            $validation = Services::validation();
            
            $validationRules = [
                'tipo_proceso' => 'required|string',
                'lotes' => 'required',
            ];

            if (!$validation->setRules($validationRules)->run($this->request->getPost())) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $tipoProceso = $this->request->getPost('tipo_proceso');
            $lotesIds = $this->request->getPost('lotes') ?? [];
            $pesosParciales = $this->request->getPost('peso_parcial') ?? [];
            $pesosEstimados = $this->request->getPost('peso_estimado_final') ?? [];

            if (!is_array($lotesIds) || empty($lotesIds)) {
                return redirect()->back()->withInput()->with('error', 'Debe seleccionar al menos un lote.');
            }

            $procesosCreados = 0;
            $loteProcesoId = 'masivo_' . date('Ymd_His');

            foreach ($lotesIds as $loteId) {
                $loteId = (int)$loteId;
                $lote = $this->loteModel->find($loteId);
                
                if (!$lote) {
                    throw new Exception("Lote #{$loteId} no encontrado");
                }

                $pesoProcesar = isset($pesosParciales[$loteId]) ? (float)$pesosParciales[$loteId] : 0;
                
                if ($pesoProcesar <= 0) {
                    continue;
                }

                // Calcular peso disponible actual
                $pesoProcesado = $this->procesoModel
                    ->where('lote_entrada_id', $loteId)
                    ->whereIn('estado_proceso', [self::ESTADO_EN_PROCESO, self::ESTADO_PENDIENTE, self::ESTADO_INICIADO])
                    ->selectSum('peso_bruto_kg')
                    ->get()
                    ->getRow();
                
                $pesoProcesado = $pesoProcesado ? (float)$pesoProcesado->peso_bruto_kg : 0;
                $pesoDisponible = max(0, (float)$lote['peso_bruto_kg'] - $pesoProcesado);

                if ($pesoProcesar > $pesoDisponible) {
                    throw new Exception("El peso a procesar ({$pesoProcesar} kg) excede el disponible ({$pesoDisponible} kg) para el lote #{$loteId}");
                }

                $pesoEstimado = isset($pesosEstimados[$loteId]) ? (float)$pesosEstimados[$loteId] : $pesoProcesar;

                // Crear proceso individual para cada lote
                $data = [
                    'fecha_proceso' => date('Y-m-d H:i:s'),
                    'tipo_proceso' => $tipoProceso,
                    'observacion_proceso' => 'Proceso masivo - ' . $tipoProceso,
                    'proveedor' => $lote['proveedor'] ?? '',
                    'peso_bruto_kg' => $pesoProcesar,
                    'peso_estimado_kg' => $pesoEstimado,
                    'peso_final_kg' => 0,
                    'estado_proceso' => self::ESTADO_PENDIENTE,
                    'lote_entrada_id' => $loteId,
                    'tipo_entrada_id' => $lote['tipo_entrada_id'] ?? null,
                    'es_proceso_masivo' => 1,
                    'lote_proceso_id' => $loteProcesoId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                if (!$this->procesoModel->insert($data)) {
                    throw new Exception("Error al crear proceso para lote #{$loteId}");
                }

                $procesosCreados++;
            }

            if ($procesosCreados === 0) {
                throw new Exception('No se crearon procesos. Verifique los pesos ingresados.');
            }

            $db->transCommit();
            return redirect()->to(site_url('procesos'))->with('success', "{$procesosCreados} procesos masivos creados correctamente");

        } catch (Exception $e) {
            $db->transRollback();
            $this->logError('iniciarMasivo', $e);
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /** Validar flujo secuencial */
    private function validarFlujoSecuencial(int $loteId, string $nuevoProceso): ?string
    {
        $orden = self::TIPOS_PROCESO;
        $indiceNuevo = array_search($nuevoProceso, $orden, true);
        if ($indiceNuevo === false) return "Tipo de proceso inválido.";

        $procesos = $this->procesoModel
            ->where('lote_entrada_id', $loteId)
            ->where('deleted_at', null)
            ->orderBy('fecha_proceso', 'ASC')
            ->findAll();

        if (empty($procesos)) {
            return $indiceNuevo === 0 ? null : "El primer proceso debe ser Desgranado.";
        }

        $ultimo = end($procesos);
        if (($ultimo['estado_proceso'] ?? '') !== self::ESTADO_FINALIZADO) {
            return "El proceso anterior ({$ultimo['tipo_proceso']}) debe estar finalizado.";
        }

        $indiceUltimo = array_search($ultimo['tipo_proceso'], $orden, true);
        if ($indiceUltimo === false) {
            return "El proceso anterior es inválido.";
        }

        if (!isset($orden[$indiceUltimo + 1]) || $indiceNuevo !== $indiceUltimo + 1) {
            $siguiente = $orden[$indiceUltimo + 1] ?? 'Empaque';
            return "Debe completarse '$siguiente' antes de '$nuevoProceso'.";
        }

        return null;
    }

    /** Exportar todos los procesos a PDF (resumen) */
    public function exportarPDF(): ResponseInterface
    {
        try {
            $procesos = $this->procesoModel
                ->select('procesos.*, lote_entrada.proveedor, lote_entrada.folio')
                ->join('lote_entrada', 'lote_entrada.id = procesos.lote_entrada_id', 'left')
                ->where('procesos.deleted_at', null)
                ->orderBy('procesos.fecha_proceso', 'DESC')
                ->findAll();

            if (empty($procesos)) {
                return redirect()->to(site_url('procesos'))->with('error', 'No hay procesos para exportar.');
            }

            $resumen = [];
            $totalBruto = 0;
            $totalEstimado = 0;
            $totalFinal = 0;
            
            foreach ($procesos as $p) {
                $tipo = $p['tipo_proceso'] ?? 'Otros';
                $resumen[$tipo]['cantidad'] = ($resumen[$tipo]['cantidad'] ?? 0) + 1;
                $resumen[$tipo]['peso_bruto'] = ($resumen[$tipo]['peso_bruto'] ?? 0) + floatval($p['peso_bruto_kg'] ?? 0);
                $resumen[$tipo]['peso_estimado'] = ($resumen[$tipo]['peso_estimado'] ?? 0) + floatval($p['peso_estimado_kg'] ?? 0);
                $resumen[$tipo]['peso_final'] = ($resumen[$tipo]['peso_final'] ?? 0) + floatval($p['peso_final_kg'] ?? 0);

                $totalBruto += floatval($p['peso_bruto_kg'] ?? 0);
                $totalEstimado += floatval($p['peso_estimado_kg'] ?? 0);
                $totalFinal += floatval($p['peso_final_kg'] ?? 0);
            }

            $logo_base64 = $this->getLogoBase64();

            $html = view('procesos/exportarPDF', [
                'procesos' => $procesos,
                'resumen' => $resumen,
                'totalBruto' => $totalBruto,
                'totalEstimado' => $totalEstimado,
                'totalFinal' => $totalFinal,
                'logo_base64' => $logo_base64
            ]);

            return $this->generatePDFResponse($html, 'procesos_' . date('Ymd_His') . '.pdf');
        } catch (Exception $e) {
            $this->logError('exportarPDF', $e);
            return redirect()->to(site_url('procesos'))->with('error', 'Error al generar el PDF.');
        }
    }

    /** PDF individual */
   public function pdf(int $proceso_id): ResponseInterface
{
    try {
        // 1. Obtener el proceso principal
        $proceso = $this->procesoModel->find($proceso_id);
        
        if (!$proceso) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Proceso no encontrado");
        }

        // 2. Trazabilidad: Obtener todos los lotes relacionados al mismo proceso masivo
        $detalles = [];
        if (!empty($proceso['lote_proceso_id'])) {
            $detalles = $this->procesoModel
                ->select('
                    procesos.id, 
                    procesos.peso_bruto_kg as peso_parcial_kg, 
                    procesos.peso_estimado_kg, 
                    procesos.estado_proceso as estado,
                    procesos.created_at as fecha_registro,
                    lote_entrada.folio as lote,
                    lote_entrada.proveedor as proveedor_lote,
                    tipo_pimienta.nombre as tipo_pimienta,
                    centro.nombre as centro,
                    tipo_entrada.nombre as tipo_entrada_nombre
                ')
                ->join('lote_entrada', 'lote_entrada.id = procesos.lote_entrada_id', 'left')
                ->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id', 'left')
                ->join('centro', 'centro.id = lote_entrada.centro_id', 'left')
                ->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id', 'left')
                ->where('procesos.lote_proceso_id', $proceso['lote_proceso_id'])
                ->where('procesos.deleted_at', null)
                ->findAll();
        }

        // 3. Información del lote principal (para el encabezado del PDF si es necesario)
        $lotePrincipal = null;
        if (!empty($proceso['lote_entrada_id'])) {
            $lotePrincipal = $this->loteModel
                ->select('lote_entrada.*, tipo_entrada.nombre as tipo_entrada, tipo_pimienta.nombre as tipo_pimienta, centro.nombre as centro')
                ->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id', 'left')
                ->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id', 'left')
                ->join('centro', 'centro.id = lote_entrada.centro_id', 'left')
                ->where('lote_entrada.id', $proceso['lote_entrada_id'])
                ->first();
        }

        // 4. Cálculos de totales para el reporte
        $totalPesoParcial = 0;
        foreach ($detalles as $d) {
            $totalPesoParcial += (float)($d['peso_parcial_kg'] ?? 0);
        }

        $logo_base64 = $this->getLogoBase64();

        $data = [
            'proceso'          => $proceso,
            'lote'             => $lotePrincipal,
            'detalles'         => $detalles,
            'totalPesoParcial' => $totalPesoParcial,
            'logo_base64'      => $logo_base64,
            'fecha_reporte'    => date('d/m/Y H:i:s'),
        ];

        // 5. Generación del HTML y respuesta PDF
        // Asegúrate de que la vista 'procesos/proceso_detalle' tenga el bucle para $detalles
        $html = view('procesos/proceso_detalle', $data);
        
        return $this->generatePDFResponse($html, "proceso_{$proceso_id}.pdf", 'portrait');

    } catch (\Exception $e) {
        $this->logError('pdf', $e);
        return redirect()->to(site_url('procesos'))
                         ->with('error', 'Error al generar el PDF: ' . $e->getMessage());
    }
}
    /** Historial de un lote */
    public function historial(int $loteId): ResponseInterface
    {
        try {
            if ($loteId <= 0) {
                return redirect()->to(site_url('procesos'))
                    ->with('error', 'ID de lote inválido.');
            }

            $lote = $this->loteModel
                ->select('lote_entrada.*, centro.nombre AS centro_nombre, tipo_entrada.nombre as tipo_entrada, tipo_pimienta.nombre as tipo_pimienta')
                ->join('centro', 'centro.id = lote_entrada.centro_id', 'left')
                ->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id', 'left')
                ->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id', 'left')
                ->where('lote_entrada.id', $loteId)
                ->first();

            if (!$lote) {
                return redirect()->to(site_url('procesos'))
                    ->with('error', 'El lote no existe.');
            }

            $procesos = $this->procesoModel
                ->where('lote_entrada_id', $loteId)
                ->where('deleted_at', null)
                ->orderBy('fecha_proceso', 'ASC')
                ->findAll();

            $porcentaje = $this->calcularPorcentajeAvance($procesos);

            return view('procesos/historial', [
                'procesos' => $procesos,
                'lote' => $lote,
                'loteId' => $loteId,
                'porcentaje' => $porcentaje,
                'mensaje' => empty($procesos) ? 'No hay procesos registrados para este lote.' : null
            ]);
        } catch (Throwable $e) {
            $this->logError('historial', $e);
            return redirect()->to(site_url('procesos'))
                ->with('error', 'Error al obtener el historial.');
        }
    }

    /** Mostrar detalles de un proceso */
    /** Mostrar detalles de un proceso */
public function detalles(?int $proceso_id = null): ResponseInterface
{
    try {
        if (!$proceso_id || $proceso_id <= 0) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("ID de proceso no válido");
        }

        // 1. Obtener el proceso principal
        $proceso = $this->procesoModel
            ->select('procesos.*, lote_entrada.proveedor, lote_entrada.folio')
            ->join('lote_entrada', 'lote_entrada.id = procesos.lote_entrada_id', 'left')
            ->where('procesos.id', $proceso_id)
            ->where('procesos.deleted_at', null)
            ->first();

        if (!$proceso) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Proceso no encontrado");
        }

        // 2. Trazabilidad: Obtener TODOS los procesos que comparten el mismo lote_proceso_id
        // Esto es vital para procesos masivos (varios lotes de entrada -> un resultado)
        $detalles = [];
        if (!empty($proceso['lote_proceso_id'])) {
            $detalles = $this->procesoModel
                ->select('
                    procesos.id, 
                    procesos.peso_bruto_kg as peso_parcial_kg, 
                    procesos.peso_estimado_kg, 
                    procesos.estado_proceso as estado,
                    procesos.created_at as fecha_registro,
                    lote_entrada.folio as lote,
                    lote_entrada.proveedor as proveedor_lote,
                    tipo_pimienta.nombre as tipo_pimienta,
                    centro.nombre as centro,
                    tipo_entrada.nombre as tipo_entrada_nombre
                ')
                ->join('lote_entrada', 'lote_entrada.id = procesos.lote_entrada_id', 'left')
                ->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id', 'left')
                ->join('centro', 'centro.id = lote_entrada.centro_id', 'left')
                ->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id', 'left')
                ->where('procesos.lote_proceso_id', $proceso['lote_proceso_id'])
                ->where('procesos.deleted_at', null)
                ->findAll();
        }

        // 3. Totales para la vista
        $totalPesoParcial = 0;
        $totalPesoEstimado = 0;
        $totalPesoBruto = 0; // Si aplica suma de originales

        foreach ($detalles as $d) {
            $totalPesoParcial += (float)($d['peso_parcial_kg'] ?? 0);
            $totalPesoEstimado += (float)($d['peso_estimado_kg'] ?? 0);
        }

        $data = [
            'proceso'           => $proceso,
            'detalles'          => $detalles,
            'totalPesoParcial'  => $totalPesoParcial,
            'totalPesoEstimado' => $totalPesoEstimado,
            'totalPesoBruto'    => $totalPesoParcial, // En procesos masivos, el parcial es lo que entró
            'title'             => 'Detalles del Proceso #' . $proceso['id']
        ];

        return $this->response->setBody(view('procesos/detalles', $data));

    } catch (\Exception $e) {
        return redirect()->to(site_url('procesos'))
            ->with('error', 'Error: ' . $e->getMessage());
    }
}
    /** Agregar detalle a un proceso */
    public function agregarDetalle(): RedirectResponse
    {
        $db = $this->db;
        $db->transBegin();

        try {
            // Obtener y validar datos del POST
            $proceso_id = $this->request->getPost('proceso_id');
            $descripcion = $this->request->getPost('descripcion');
            $peso_parcial_kg = $this->request->getPost('peso_parcial_kg');
            $estado = $this->request->getPost('estado');

            // Validaciones básicas
            if (empty($proceso_id)) {
                throw new Exception('El ID del proceso es requerido.');
            }

            if (empty($descripcion) || trim($descripcion) === '') {
                throw new Exception('La descripción es requerida.');
            }

            // Validar que el proceso exista
            $proceso = $this->procesoModel->find($proceso_id);
            if (!$proceso) {
                throw new Exception('El proceso especificado no existe.');
            }

            // Validar y formatear el peso parcial
            $peso_parcial = 0.0;
            if (!empty($peso_parcial_kg)) {
                $peso_parcial = (float) $peso_parcial_kg;
                if ($peso_parcial <= 0) {
                    throw new Exception('El peso parcial debe ser mayor a 0.');
                }
                
                // Validar que no exceda el peso disponible del proceso
                $peso_actual_proceso = (float) $proceso['peso_bruto_kg'];
                if ($peso_parcial > $peso_actual_proceso) {
                    throw new Exception("El peso parcial ({$peso_parcial} kg) no puede ser mayor al peso del proceso ({$peso_actual_proceso} kg).");
                }
            }

            // Validar estado
            $estadosPermitidos = ['Pendiente', 'En Proceso', 'Completo', 'Cancelado', 'EXITOSO', 'FALLIDO', 'PENDIENTE'];
            if (!empty($estado) && !in_array($estado, $estadosPermitidos)) {
                throw new Exception('Estado no válido.');
            }

            // Preparar datos para inserción
            $data = [
                'proceso_id' => (int) $proceso_id,
                'descripcion' => trim(esc($descripcion)),
                'peso_parcial_kg' => $peso_parcial,
                'estado' => !empty($estado) ? esc($estado) : 'Pendiente',
                'fecha_registro' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Insertar el detalle
            if (!$this->detalleModel->insert($data)) {
                $errors = $this->detalleModel->errors();
                throw new Exception('Error al guardar el detalle: ' . implode(', ', $errors));
            }

            // Si el proceso está relacionado con un lote, actualizar información adicional
            if (!empty($proceso['lote_entrada_id'])) {
                $lote = $this->loteModel->find($proceso['lote_entrada_id']);
                if ($lote) {
                    $this->detalleModel->update($this->detalleModel->getInsertID(), [
                        'lote_entrada_id' => $proceso['lote_entrada_id'],
                        'proveedor_lote' => $lote['proveedor'] ?? null,
                        'tipo_pimienta' => $this->obtenerTipoPimienta($lote['tipo_pimienta_id'] ?? null),
                        'centro' => $this->obtenerCentroAcopio($lote['centro_id'] ?? null),
                        'peso_bruto' => $lote['peso_bruto_kg'] ?? 0,
                        'tipo_entrada' => $this->obtenerTipoEntrada($lote['tipo_entrada_id'] ?? null)
                    ]);
                }
            }

            $db->transCommit();

            return redirect()->to(site_url('procesos/detalles/' . $proceso_id))
                ->with('success', 'Detalle agregado correctamente.');

        } catch (Exception $e) {
            $db->transRollback();
            $this->logError('agregarDetalle', $e);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al agregar el detalle: ' . $e->getMessage());
        }
    }

    /** Helper para obtener nombre del tipo de pimienta */
    private function obtenerTipoPimienta(?int $tipoPimientaId): ?string
    {
        if (!$tipoPimientaId) return null;
        
        $tipoPimienta = $this->db->table('tipo_pimienta')
            ->select('nombre')
            ->where('id', $tipoPimientaId)
            ->get()
            ->getRow();
        
        return $tipoPimienta->nombre ?? null;
    }

    /** Helper para obtener nombre del centro de acopio */
    private function obtenerCentroAcopio(?int $centroId): ?string
    {
        if (!$centroId) return null;
        
        $centro = $this->db->table('centro')
            ->select('nombre')
            ->where('id', $centroId)
            ->get()
            ->getRow();
        
        return $centro->nombre ?? null;
    }

    /** Helper para obtener nombre del tipo de entrada */
    private function obtenerTipoEntrada(?int $tipoEntradaId): ?string
    {
        if (!$tipoEntradaId) return null;
        
        $tipoEntrada = $this->db->table('tipo_entrada')
            ->select('nombre')
            ->where('id', $tipoEntradaId)
            ->get()
            ->getRow();
        
        return $tipoEntrada->nombre ?? null;
    }

    /** Helper para obtener logo en base64 */
    private function getLogoBase64(): ?string
    {
        $pathLogo = FCPATH . 'assets/img/logo01.jpg';
        if (file_exists($pathLogo)) {
            $data = file_get_contents($pathLogo);
            if ($data !== false) {
                $tipo = pathinfo($pathLogo, PATHINFO_EXTENSION);
                return 'data:image/' . $tipo . ';base64,' . base64_encode($data);
            }
        }
        return null;
    }

    /** Helper para generar respuesta PDF */
    private function generatePDFResponse(string $html, string $filename, string $orientation = 'landscape'): ResponseInterface
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', $orientation);
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }

    /** Calcular porcentaje de avance */
    private function calcularPorcentajeAvance(array $procesos): float
    {
        if (empty($procesos)) {
            return 0.0;
        }

        $finalizados = 0;
        foreach ($procesos as $p) {
            if (($p['estado_proceso'] ?? '') === self::ESTADO_FINALIZADO) {
                $finalizados++;
            }
        }

        return round(($finalizados / count($procesos)) * 100, 2);
    }

    /** Eliminar proceso */
    public function delete(int $id): RedirectResponse
    {
        $db = $this->db;
        $db->transBegin();

        try {
            $proceso = $this->procesoModel->find($id);
            if (!$proceso) {
                return redirect()->to(site_url('procesos'))->with('error', 'Proceso no encontrado');
            }

            // Verificar que el proceso no esté en estado iniciado o finalizado
            if (in_array($proceso['estado_proceso'], [self::ESTADO_INICIADO, self::ESTADO_FINALIZADO])) {
                return redirect()->back()->with('error', 'No se puede eliminar un proceso que está iniciado o finalizado');
            }

            // Si es proceso masivo, eliminar todos los procesos relacionados
            if ($proceso['es_proceso_masivo'] && !empty($proceso['lote_proceso_id'])) {
                $this->procesoModel->where('lote_proceso_id', $proceso['lote_proceso_id'])->delete();
            } else {
                $this->procesoModel->delete($id);
            }

            $db->transCommit();
            return redirect()->to(site_url('procesos'))->with('success', 'Proceso eliminado correctamente');

        } catch (Exception $e) {
            $db->transRollback();
            $this->logError('delete', $e);
            return redirect()->back()->with('error', 'Error al eliminar el proceso: ' . $e->getMessage());
        }
    }

    /** Obtener información de proceso para edición rápida */
    public function getProcesoInfo(int $id): ResponseInterface
    {
        try {
            $proceso = $this->procesoModel->find($id);
            if (!$proceso) {
                return $this->response->setStatusCode(404)->setJSON([
                    'error' => 'Proceso no encontrado'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'id' => $proceso['id'],
                    'tipo_proceso' => $proceso['tipo_proceso'],
                    'peso_bruto_kg' => $proceso['peso_bruto_kg'],
                    'peso_estimado_kg' => $proceso['peso_estimado_kg'],
                    'peso_final_kg' => $proceso['peso_final_kg'],
                    'estado_proceso' => $proceso['estado_proceso'],
                    'observacion_proceso' => $proceso['observacion_proceso'],
                    'fecha_proceso' => $proceso['fecha_proceso'],
                    'fecha_fin' => $proceso['fecha_fin']
                ]
            ]);
        } catch (Exception $e) {
            $this->logError('getProcesoInfo', $e);
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Error interno del servidor'
            ]);
        }
    }
}