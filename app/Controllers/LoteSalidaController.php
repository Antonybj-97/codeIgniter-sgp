<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoteSalidaModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use Dompdf\Dompdf;
use Dompdf\Options;

class LoteSalidaController extends BaseController
{
    protected LoteSalidaModel $loteSalidaModel;
    protected $helpers = ['form', 'url', 'text'];

    private const TIPOS_PRODUCTO = ['Orgánico', 'Convencional'];
    private const UNIDADES = ['kg', 'g', 'lb', 'ton', 'cajas', 'piezas'];
    private const FOLIO_PREFIX = 'NS';

    public function __construct()
    {
        $this->loteSalidaModel = new LoteSalidaModel();

        // Configurar zona horaria
        date_default_timezone_set('America/Mexico_City');
    }

    /* =====================================================
     * VISTAS PRINCIPALES
     * ===================================================== */

    /**
     * Página principal con listado de lotes
     */
    public function index(): string
    {
        $data = [
            'currentPage' => 'lotes-salida',
            'anios'       => $this->getAvailableYears(),
            'title'       => 'Lotes de Salida',
        ];

        return view('lotes-salida/index', $data);
    }

    /**
     * Vista para crear un nuevo lote
     */
    public function create(): string
    {
        $data = [
            'product_name'   => 'Pimienta gorda convencional',
            'product_type'   => 'Convencional',
            'unit'           => 'kg',
            'ship_date'      => Time::now()->toDateString(),
            'folio'          => $this->generateNextFolio(),
            'tipos_producto' => self::TIPOS_PRODUCTO,
            'unidades'       => self::UNIDADES,
            'title'          => 'Nueva Nota de Salida',
        ];

        return view('lotes-salida/create', $data);
    }

    /**
     * Vista para editar un lote existente
     */
    public function edit(int $id): string
    {
        $lote = $this->loteSalidaModel->find($id);

        if (!$lote) {
            throw PageNotFoundException::forPageNotFound("El lote #{$id} no existe.");
        }

        $data = [
            'lote'           => $lote,
            'tipos_producto' => self::TIPOS_PRODUCTO,
            'unidades'       => self::UNIDADES,
            'title'          => 'Editar Lote ' . esc($lote['folio_salida']),
        ];

        return view('lotes-salida/edit', $data);
    }

    /**
     * Vista para mostrar detalles de un lote
     */
    public function show(int $id): string
    {
        $lote = $this->loteSalidaModel->find($id);

        if (!$lote) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Añadir peso neto en kg para la vista
        $lote['peso_neto_kg'] = $lote['cantidad'];

        $data = [
            'batch'            => $lote,
            'fecha'            => date('d/m/Y'),
            'hora'             => date('H:i:s'),
            'title'            => 'Nota de Salida - ' . esc($lote['folio_salida']),
            'companyLogoLeft'  => $this->getBase64Image(FCPATH . 'assets/img/logo01.jpg'),
            'companyLogoRight' => $this->getOrganicLogo($lote['tipo_producto']),
        ];

        return view('lotes-salida/show', $data);
    }

    /* =====================================================
     * OPERACIONES CRUD
     * ===================================================== */

    /**
     * Guardar un nuevo lote
     */
    public function store()
    {
        // Obtener datos del POST
        $postData = $this->request->getPost();

        // Validar datos
        if (!$this->validate($this->getValidationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Preparar datos limpios
        $data = $this->prepareLoteData($postData);

        // Validar tipo de producto
        if (!in_array($data['tipo_producto'], self::TIPOS_PRODUCTO, true)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tipo de producto inválido.');
        }

        // Insertar en la base de datos
        try {
            $this->loteSalidaModel->insert($data);
            $newId = $this->loteSalidaModel->getInsertID();
        } catch (\Exception $e) {
            log_message('error', 'Error al crear lote: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar el lote. Por favor, intente nuevamente.');
        }

        return redirect()->to('lotes-salida')
            ->with('success', 'Nota de salida creada correctamente.')
            ->with('new_id', $newId);
    }

    /**
     * Actualizar un lote existente
     */
    /**
     * Actualizar un lote existente
     */
    public function update(int $id)
    {
        // Verificar existencia del lote
        $lote = $this->loteSalidaModel->find($id);
        if (!$lote) {
            throw PageNotFoundException::forPageNotFound("El lote #{$id} no existe.");
        }

        // Obtener datos del POST
        $postData = $this->request->getPost();

        // Preparar datos limpios
        $data = $this->prepareLoteData($postData);

        // Verificar si el folio ha cambiado
        $folioHaCambiado = $data['folio_salida'] !== $lote['folio_salida'];

        // Reglas de validación base
        $rules = $this->getValidationRules();

        // Si el folio ha cambiado, validar unicidad
        if ($folioHaCambiado) {
            $rules['folio_salida'] = [
                'label'  => 'Folio de Salida',
                'rules'  => "required|max_length[10]|is_unique[lote_salida.folio_salida,id_salida,{$id}]",
                'errors' => [
                    'required'   => 'El folio de salida es obligatorio.',
                    'max_length' => 'El folio no puede exceder 10 caracteres.',
                    'is_unique'  => 'Este folio ya está registrado.',
                ]
            ];
        } else {
            // Si el folio no ha cambiado, solo validar requerido y longitud
            $rules['folio_salida'] = [
                'label'  => 'Folio de Salida',
                'rules'  => 'required|max_length[10]',
                'errors' => [
                    'required'   => 'El folio de salida es obligatorio.',
                    'max_length' => 'El folio no puede exceder 10 caracteres.',
                ]
            ];
        }

        // Validar datos
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Validar tipo de producto
        if (!in_array($data['tipo_producto'], self::TIPOS_PRODUCTO, true)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tipo de producto inválido.');
        }

        // Actualizar en la base de datos
        try {
            // Primero, verificar si hay cambios antes de actualizar
            $hayCambios = false;
            foreach ($data as $key => $value) {
                if ($lote[$key] != $value) {
                    $hayCambios = true;
                    break;
                }
            }

            if (!$hayCambios) {
                return redirect()->to("lotes-salida/show/{$id}")
                    ->with('info', 'No se detectaron cambios para actualizar.');
            }

            // Usar el método protegido _update para evitar validaciones adicionales
            $db = \Config\Database::connect();
            $builder = $db->table('lote_salida');
            $builder->where('id_salida', $id);
            $success = $builder->update($data);

            if (!$success) {
                throw new \Exception("No se realizaron cambios en el lote #{$id}");
            }

        } catch (\Exception $e) {
            log_message('error', "Error al actualizar lote #{$id}: " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el lote: ' . $e->getMessage());
        }

        return redirect()->to("lotes-salida/show/{$id}")
            ->with('success', 'Nota actualizada correctamente.');
    }

    /**
     * Eliminar un lote (soft delete)
     */
    public function delete(int $id)
    {
        // Verificar existencia del lote
        $lote = $this->loteSalidaModel->find($id);
        if (!$lote) {
            throw PageNotFoundException::forPageNotFound("El lote #{$id} no existe.");
        }

        // Eliminar lógicamente
        try {
            $success = $this->loteSalidaModel->delete($id);

            if (!$success) {
                throw new \Exception("No se pudo eliminar el lote #{$id}");
            }
        } catch (\Exception $e) {
            log_message('error', "Error al eliminar lote #{$id}: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al eliminar el lote: ' . $e->getMessage());
        }

        return redirect()->to('lotes-salida')
            ->with('success', 'Nota eliminada correctamente.');
    }

    /* =====================================================
     * GENERACIÓN DE PDF
     * ===================================================== */

    /**
     * Exportar todos los lotes a PDF
     */
    public function exportarPdf()
    {
        $anio = $this->request->getGet('anio');

        // Consultar lotes
        $builder = $this->loteSalidaModel
            ->select('*, cantidad AS peso_neto_kg');

        if (!empty($anio) && $anio !== 'all') {
            $builder->where('YEAR(fecha_embarque)', (int) $anio);
        }

        $lotes = $builder->orderBy('fecha_embarque', 'DESC')->findAll();

        if (empty($lotes)) {
            return redirect()->to(site_url('lotes-salida'))
                ->with('warning', 'No hay registros para exportar.');
        }

        // Generar HTML para PDF
        $html = view('lotes-salida/exportar_salida', [
            'title' => 'Reporte de Lotes de Salida',
            'lotes' => $lotes,
            'fecha' => date('d/m/Y'),
        ]);

        // Generar y descargar PDF
        $this->generatePdfResponse(
            $html,
            'reporte_salidas_' . date('Y-m-d') . '.pdf',
            'landscape'
        );
    }

    /**
     * Exportar un lote individual a PDF
     */
    // Cambia el nombre a lotePDF si es el que usas en tus rutas/vistas
    public function lotePDF(int $id): ResponseInterface
    {
        $lote = $this->loteSalidaModel->find($id);

        if (!$lote) {
            throw PageNotFoundException::forPageNotFound();
        }

        $lote['peso_neto_kg'] = $lote['cantidad'];

        $html = view('reportes/salidas_pdf', [
            'batch'            => $lote,
            'fecha'            => date('d/m/Y'),
            'title'            => 'Nota de Salida - ' . $lote['folio_salida'],
            'companyLogoLeft'  => $this->getBase64Image(FCPATH . 'assets/img/logo01.jpg'),
            'companyLogoRight' => $this->getOrganicLogo($lote['tipo_producto']),
        ]);

        // IMPORTANTE: Asegúrate de usar 'return' para enviar la respuesta al navegador
        return $this->generatePdfResponse(
            $html,
            "nota_salida_{$lote['folio_salida']}.pdf",
            'landscape'
        );
    }
    /* =====================================================
     * API PARA DATATABLES
     * ===================================================== */

    /**
     * Endpoint API para Datatables
     */
    public function apiSalidas(): ResponseInterface
    {
        $anio = $this->request->getGet('anio');
        $draw = (int) ($this->request->getGet('draw') ?? 1);

        // Construir consulta
        $builder = $this->loteSalidaModel
            ->select('*, cantidad AS peso_neto_kg');

        // Filtrar por año si se especifica
        if ($anio && $anio !== 'all') {
            $builder->where('YEAR(fecha_embarque)', $anio);
        }

        // Obtener datos
        $data = $builder->orderBy('created_at', 'DESC')->findAll();

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $this->loteSalidaModel->countAll(),
            'recordsFiltered' => count($data),
            'data'            => $data,
        ]);
    }

    /* =====================================================
     * MÉTODOS PRIVADOS AUXILIARES
     * ===================================================== */

    /**
     * Obtener reglas de validación
     */
    private function getValidationRules(): array
    {
        return [
            'fecha_embarque'   => [
                'label'  => 'Fecha de Embarque',
                'rules'  => 'required|valid_date[Y-m-d]',
                'errors' => [
                    'required'    => 'La fecha de embarque es obligatoria.',
                    'valid_date'  => 'La fecha de embarque no es válida.',
                ]
            ],
            'nombre_cliente'   => [
                'label'  => 'Nombre del Cliente',
                'rules'  => 'required|max_length[100]',
                'errors' => [
                    'required'   => 'El nombre del cliente es obligatorio.',
                    'max_length' => 'El nombre no puede exceder 100 caracteres.',
                ]
            ],
            'folio_salida'     => [
                'label'  => 'Folio de Salida',
                'rules'  => 'required|max_length[10]|is_unique[lote_salida.folio_salida]',
                'errors' => [
                    'required'   => 'El folio de salida es obligatorio.',
                    'max_length' => 'El folio no puede exceder 10 caracteres.',
                    'is_unique'  => 'Este folio ya está registrado.',
                ]
            ],
            'producto'         => [
                'label'  => 'Producto',
                'rules'  => 'required|max_length[150]',
                'errors' => [
                    'required'   => 'El producto es obligatorio.',
                    'max_length' => 'El producto no puede exceder 150 caracteres.',
                ]
            ],
            'tipo_producto'    => [
                'label'  => 'Tipo de Producto',
                'rules'  => 'required|in_list[Orgánico,Convencional]',
                'errors' => [
                    'required'  => 'El tipo de producto es obligatorio.',
                    'in_list'   => 'Seleccione un tipo de producto válido.',
                ]
            ],
            'unidad'           => [
                'label'  => 'Unidad',
                'rules'  => 'required|max_length[20]',
                'errors' => [
                    'required'   => 'La unidad es obligatoria.',
                    'max_length' => 'La unidad no puede exceder 20 caracteres.',
                ]
            ],
            'cantidad'         => [
                'label'  => 'Cantidad',
                'rules'  => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required'     => 'La cantidad es obligatoria.',
                    'numeric'      => 'La cantidad debe ser un número.',
                    'greater_than' => 'La cantidad debe ser mayor a 0.',
                ]
            ],
            'no_maquila'       => [
                'label'  => 'No. Maquila',
                'rules'  => 'permit_empty|max_length[50]',
                'errors' => [
                    'max_length' => 'El número de maquila no puede exceder 50 caracteres.',
                ]
            ],
            'no_factura'       => [
                'label'  => 'No. Factura',
                'rules'  => 'permit_empty|max_length[50]',
                'errors' => [
                    'max_length' => 'El número de factura no puede exceder 50 caracteres.',
                ]
            ],
            'certificado'      => [
                'label'  => 'Certificado',
                'rules'  => 'permit_empty|max_length[50]',
                'errors' => [
                    'max_length' => 'El certificado no puede exceder 50 caracteres.',
                ]
            ],
            'clave_lote'       => [
                'label'  => 'Clave Lote',
                'rules'  => 'permit_empty|max_length[50]',
                'errors' => [
                    'max_length' => 'La clave de lote no puede exceder 50 caracteres.',
                ]
            ],
            'datos_transporte' => [
                'label'  => 'Datos Transporte',
                'rules'  => 'permit_empty|string',
                'errors' => [
                    'string' => 'Los datos del transporte deben ser texto válido.',
                ]
            ],
            'recibe_producto'  => [
                'label'  => 'Recibe Producto',
                'rules'  => 'permit_empty|max_length[100]',
                'errors' => [
                    'max_length' => 'El nombre no puede exceder 100 caracteres.',
                ]
            ],
            'autoriza_salida'  => [
                'label'  => 'Autoriza Salida',
                'rules'  => 'permit_empty|max_length[100]',
                'errors' => [
                    'max_length' => 'El nombre no puede exceder 100 caracteres.',
                ]
            ],
        ];
    }

    /**
     * Preparar datos del lote para inserción/actualización
     */
    private function prepareLoteData(array $postData): array
    {
        return [
            'folio_salida'     => trim($postData['folio_salida'] ?? ''),
            'fecha_embarque'   => $postData['fecha_embarque'] ?? '',
            'tipo_producto'    => trim($postData['tipo_producto'] ?? ''),
            'nombre_cliente'   => trim($postData['nombre_cliente'] ?? ''),
            'producto'         => trim($postData['producto'] ?? ''),
            'unidad'           => trim($postData['unidad'] ?? 'kg'),
            'cantidad'         => (float) ($postData['cantidad'] ?? 0),
            'no_maquila'       => trim($postData['no_maquila'] ?? ''),
            'no_factura'       => trim($postData['no_factura'] ?? ''),
            'certificado'      => trim($postData['certificado'] ?? ''),
            'clave_lote'       => trim($postData['clave_lote'] ?? ''),
            'datos_transporte' => trim($postData['datos_transporte'] ?? ''),
            'recibe_producto'  => trim($postData['recibe_producto'] ?? ''),
            'autoriza_salida'  => trim($postData['autoriza_salida'] ?? ''),
        ];
    }

    /**
     * Generar el siguiente folio secuencial
     */
    private function generateNextFolio(): string
    {
        try {
            // Primero buscar el último folio numérico existente
            $result = $this->loteSalidaModel
                ->select('folio_salida')
                ->orderBy('id_salida', 'DESC')
                ->limit(1)
                ->first();

            if (!$result || empty($result['folio_salida'])) {
                return self::FOLIO_PREFIX . '0001';
            }

            $lastFolio = $result['folio_salida'];

            // Extraer número del folio (ej: NS0001 -> 1)
            preg_match('/\d+/', $lastFolio, $matches);

            if (empty($matches)) {
                return self::FOLIO_PREFIX . '0001';
            }

            $lastNumber = (int)$matches[0];
            $nextNumber = $lastNumber + 1;

            return self::FOLIO_PREFIX . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        } catch (\Exception $e) {
            log_message('error', 'Error generando folio: ' . $e->getMessage());
            return self::FOLIO_PREFIX . '0001';
        }
    }

    /**
     * Obtener años disponibles con cache
     */
    private function getAvailableYears(): array
    {
        $cache = \Config\Services::cache();
        $cacheKey = 'available_years_lote_salida';

        // Intentar obtener del cache
        if ($years = $cache->get($cacheKey)) {
            return $years;
        }

        // Obtener de la base de datos
        $result = $this->loteSalidaModel
            ->select('YEAR(fecha_embarque) AS anio')
            ->distinct()
            ->where('fecha_embarque IS NOT NULL')
            ->orderBy('anio', 'DESC')
            ->findAll();

        $years = array_column($result, 'anio');

        // Guardar en cache por 1 hora
        $cache->save($cacheKey, $years, 3600);

        return $years;
    }

    /**
     * Obtener logo orgánico según tipo de producto
     */
    private function getOrganicLogo(?string $tipoProducto): ?string
    {
        return $tipoProducto === 'Orgánico'
            ? $this->getBase64Image(FCPATH . 'assets/img/certimex.png')
            : null;
    }

    /**
     * Convertir imagen a base64
     */
    private function getBase64Image(string $path): ?string
    {
        if (!is_file($path) || !is_readable($path)) {
            log_message('warning', "Imagen no encontrada: {$path}");
            return null;
        }

        try {
            $mimeType = mime_content_type($path);
            $imageData = file_get_contents($path);

            if ($imageData === false) {
                throw new \Exception("No se pudo leer la imagen: {$path}");
            }

            return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        } catch (\Exception $e) {
            log_message('error', 'Error procesando imagen: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generar respuesta PDF
     */
    private function generatePdfResponse(
        string $html,
        string $filename,
        string $orientation = 'portrait'
    ): void {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isFontSubsettingEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', $orientation);
        $dompdf->render();

        // Enviar PDF al navegador
        $dompdf->stream($filename, ['Attachment' => 0]);
        exit;
    }
}