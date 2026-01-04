<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoteEntradaModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LoteEntradaController extends BaseController
{
    protected $loteModel;

    public function __construct()
    {
        $this->loteModel = new LoteEntradaModel();
    }

    // ========================
    // UTILIDADES
    // ========================
    private function getRequestData()
    {
        $contentType = $this->request->getHeaderLine('Content-Type');
        return strpos($contentType, 'application/json') !== false
            ? $this->request->getJSON(true)
            : $this->request->getPost();
    }

    private function jsonResponse(bool $success, string $message = '', array $data = [], int $httpCode = 200)
    {
        $response = ['success' => $success, 'message' => $message];
        if (!empty($data)) $response['data'] = $data;
        return $this->response->setJSON($response)->setStatusCode($httpCode);
    }

    private function rules(): array
    {
        return [
            'folio'            => 'required|string|max_length[50]',
            'centro_id'        => 'required|is_natural_no_zero',
            'tipo_pimienta_id' => 'required|is_natural_no_zero',
            'tipo_entrada_id'  => 'required|is_natural_no_zero',
            'peso_bruto_kg'    => 'required|decimal|greater_than[0]',
            'precio_compra'    => 'required|decimal|greater_than[0]',
            'fecha_entrada'    => 'permit_empty|valid_date[Y-m-d]',
            'proveedor'        => 'permit_empty|string|max_length[255]',
            'observaciones'    => 'permit_empty|string|max_length[500]',
            'estado'           => 'permit_empty|string|max_length[50]',
        ];
    }

    // ========================
    // VISTAS
    // ========================
    public function index()
    {
        return view('lotes-entrada/index');
    }

    public function create()
    {
        $centros = model('CentroModel')->where('deleted_at', null)->findAll();
        $tipos_pimienta = model('TipoPimientaModel')->where('deleted_at', null)->findAll();
        $tipos_entrada = model('TipoEntradaModel')->where('deleted_at', null)->findAll();

        return view('lotes-entrada/create', compact('centros', 'tipos_pimienta', 'tipos_entrada'));
    }

    public function edit($id)
    {
        $lote = $this->loteModel
            ->select('lote_entrada.*, centro.nombre AS centro, tipo_pimienta.nombre AS tipo_pimienta, tipo_entrada.nombre AS tipo_entrada')
            ->join('centro', 'centro.id = lote_entrada.centro_id AND centro.deleted_at IS NULL', 'left')
            ->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id AND tipo_pimienta.deleted_at IS NULL', 'left')
            ->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id AND tipo_entrada.deleted_at IS NULL', 'left')
            ->where('lote_entrada.id', $id)
            ->where('lote_entrada.deleted_at', null)
            ->first();

        if (!$lote) {
            return redirect()->to('lotes-entrada')->with('error', 'Lote no encontrado');
        }

        $centros = model('CentroModel')->where('deleted_at', null)->findAll();
        $tipos_pimienta = model('TipoPimientaModel')->where('deleted_at', null)->findAll();
        $tipos_entrada = model('TipoEntradaModel')->where('deleted_at', null)->findAll();

        return view('lotes-entrada/edit', compact('lote', 'centros', 'tipos_pimienta', 'tipos_entrada'));
    }

    public function show($id)
    {
        $lote = $this->loteModel
            ->select('lote_entrada.*, centro.nombre AS centro, tipo_pimienta.nombre AS tipo_pimienta, tipo_entrada.nombre AS tipo_entrada, users.username AS usuario')
            ->join('centro', 'centro.id = lote_entrada.centro_id AND centro.deleted_at IS NULL', 'left')
            ->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id AND tipo_pimienta.deleted_at IS NULL', 'left')
            ->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id AND tipo_entrada.deleted_at IS NULL', 'left')
            ->join('users', 'users.id = lote_entrada.usuario_id', 'left')
            ->where('lote_entrada.id', $id)
            ->where('lote_entrada.deleted_at', null)
            ->first();

        if (!$lote) {
            return redirect()->to('lotes-entrada')->with('error', 'Lote no encontrado');
        }

        return view('lotes-entrada/show', compact('lote'));
    }
    
    // CRUD AJAX
    
    public function store()
    {
        $request = $this->getRequestData();

        if (!$this->validate($this->rules())) {
            return $this->jsonResponse(false, 'Datos inválidos', $this->validator->getErrors(), 400);
        }

        $usuarioId = session()->get('user_id');
        if (!$usuarioId) {
            return $this->jsonResponse(false, 'No hay usuario logueado', [], 401);
        }

        $request['costo_total']   = (float)($request['peso_bruto_kg'] ?? 0) * (float)($request['precio_compra'] ?? 0);
        $request['usuario_id']    = $usuarioId;
        $request['fecha_entrada'] = $request['fecha_entrada'] ?? date('Y-m-d');
        $request['folio']         = trim($request['folio'] ?? ('F' . date('ymdHis')));
        $request['estado']        = $request['estado'] ?? 'Recibido';
        $request['created_at']    = date('Y-m-d H:i:s');

        if ($this->loteModel->insert($request) === false) {
            return $this->jsonResponse(false, 'Error al registrar entrada', $this->loteModel->errors(), 500);
        }

        return $this->jsonResponse(true, 'Entrada registrada correctamente');
    }

    public function update($id)
    {
        $lote = $this->loteModel->find($id);
        if (!$lote) return $this->jsonResponse(false, 'Lote no encontrado', [], 404);

        $request = $this->getRequestData();

        if (!$this->validate($this->rules())) {
            return $this->jsonResponse(false, 'Datos inválidos', $this->validator->getErrors(), 400);
        }

        $usuarioId = session()->get('user_id');
        if (!$usuarioId) return $this->jsonResponse(false, 'No hay usuario logueado', [], 401);

        $request['costo_total']   = (float)($request['peso_bruto_kg'] ?? 0) * (float)($request['precio_compra'] ?? 0);
        $request['usuario_id']    = $usuarioId;
        $request['fecha_entrada'] = $request['fecha_entrada'] ?? $lote['fecha_entrada'];
        $request['folio']         = $request['folio'] ?? $lote['folio'];
        $request['updated_at']    = date('Y-m-d H:i:s');

        if ($this->loteModel->update($id, $request) === false) {
            return $this->jsonResponse(false, 'Error al actualizar entrada', $this->loteModel->errors(), 500);
        }

        return $this->jsonResponse(true, 'Entrada actualizada correctamente');
    }

    public function delete($id)
    {
        $lote = $this->loteModel->find($id);
        if (!$lote) return $this->jsonResponse(false, 'Lote no encontrado', [], 404);

        try {
            $db = \Config\Database::connect();
            $db->transBegin();

            $db->table('lote_salida')
                ->where('lote_entrada_id', $id)
                ->update(['deleted_at' => date('Y-m-d H:i:s')]);

            // Soft delete del lote
            if (!$this->loteModel->update($id, ['deleted_at' => date('Y-m-d H:i:s')])) {
                $db->transRollback();
                return $this->jsonResponse(false, 'No se pudo eliminar el lote', $this->loteModel->errors(), 500);
            }

            $db->transCommit();
            return $this->jsonResponse(true, 'Entrada eliminada correctamente (soft delete)');
        } catch (\Throwable $e) {
            if (isset($db) && $db->transStatus() === false) $db->transRollback();
            log_message('error', 'Error al eliminar lote ID ' . $id . ': ' . $e->getMessage());
            return $this->jsonResponse(false, 'Error al eliminar entrada: ' . $e->getMessage(), [], 500);
        }
    }

    // DATATABLES AJAX
   
    public function apiEntradas()
    {
        try {
            // Obtener parámetros de filtro del GET
            $anio = $this->request->getGet('anio');
            $tipo = $this->request->getGet('tipo');
            $estado = $this->request->getGet('estado');
            
            // Crear el builder con las relaciones
            $builder = $this->loteModel
                ->select('
                    lote_entrada.id,
                    lote_entrada.folio,
                    lote_entrada.fecha_entrada,
                    centro.nombre AS centro,
                    tipo_pimienta.nombre AS tipo_pimienta,
                    tipo_entrada.nombre AS tipo_entrada,
                    users.username AS usuario,
                    lote_entrada.proveedor,
                    lote_entrada.peso_bruto_kg,
                    lote_entrada.precio_compra,
                    lote_entrada.costo_total,
                    lote_entrada.observaciones,
                    lote_entrada.estado
                ')
                ->join('centro', 'centro.id = lote_entrada.centro_id AND centro.deleted_at IS NULL', 'left')
                ->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id AND tipo_pimienta.deleted_at IS NULL', 'left')
                ->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id AND tipo_entrada.deleted_at IS NULL', 'left')
                ->join('users', 'users.id = lote_entrada.usuario_id', 'left')
                ->where('lote_entrada.deleted_at', null);
            
            // Aplicar filtro por año si se especifica y no está vacío
            if (!empty($anio) && $anio !== 'all') {
                $builder->where("YEAR(lote_entrada.fecha_entrada)", $anio);
            }
            
            // Aplicar filtro por tipo si se especifica y no está vacío
            if (!empty($tipo) && $tipo !== 'all') {
                $builder->where('tipo_entrada.nombre', $tipo);
            }
            
            // Aplicar filtro por estado si se especifica y no está vacío
            if (!empty($estado) && $estado !== 'all') {
                $builder->where('lote_entrada.estado', $estado);
            }
            
            // Obtener los resultados ordenados
            $lotes = $builder
                ->orderBy('lote_entrada.fecha_entrada', 'DESC')
                ->orderBy('lote_entrada.id', 'DESC')
                ->asArray()
                ->findAll();

            return $this->response->setJSON(['data' => $lotes]);
        } catch (\Throwable $e) {
            log_message('error', 'Error en apiEntradas: ' . $e->getMessage());
            return $this->response->setJSON([
                'data' => [],
                'error' => true,
                'message' => 'Error al cargar los datos',
            ])->setStatusCode(500);
        }
    }

    /**
     * Retorna el peso disponible total para un tipo de pimienta (kg)
     */
    public function pesoDisponible($tipoId)
    {
        try {
            $db = \Config\Database::connect();

            $pesoTotalRow = $db->table('lote_entrada')
                ->selectSum('peso_bruto_kg')
                ->where('tipo_pimienta_id', $tipoId)
                ->where('deleted_at', null)
                ->get()
                ->getRow();

            $pesoTotal = $pesoTotalRow ? (float)$pesoTotalRow->peso_bruto_kg : 0.0;

            $pesoProcesadoRow = $db->table('procesos')
                ->selectSum('peso_bruto_kg')
                ->join('lote_entrada', 'lote_entrada.id = procesos.lote_entrada_id', 'left')
                ->where('lote_entrada.tipo_pimienta_id', $tipoId)
                ->whereIn('estado_proceso', ['Pendiente', 'Iniciado', 'en_proceso'])
                ->get()
                ->getRow();

            $pesoProcesado = $pesoProcesadoRow ? (float)$pesoProcesadoRow->peso_bruto_kg : 0.0;

            $disponible = max(0.0, $pesoTotal - $pesoProcesado);

            return $this->response->setJSON(['peso_disponible' => $disponible]);
        } catch (\Throwable $e) {
            log_message('error', 'LoteEntradaController::pesoDisponible - ' . $e->getMessage());
            return $this->response->setJSON(['peso_disponible' => 0, 'error' => true, 'message' => $e->getMessage()]);
        }
    }

    // =====================================================
    // GENERAR PDF POR FOLIO
    // =====================================================
    public function generarPdfPorFolio($folio)
    {
        try {
            $db = \Config\Database::connect();

            // Obtener los lotes
            $lotes = $db->table('lote_entrada as l')
                ->select('l.id, l.folio, l.fecha_entrada, c.nombre as centro, tp.nombre as tipo_pimienta, te.nombre as tipo_entrada, u.username as usuario, l.proveedor, l.peso_bruto_kg as peso, l.precio_compra as precio, l.costo_total, l.observaciones, l.estado')
                ->join('centro c', 'c.id = l.centro_id', 'left')
                ->join('tipo_pimienta tp', 'tp.id = l.tipo_pimienta_id', 'left')
                ->join('tipo_entrada te', 'te.id = l.tipo_entrada_id', 'left')
                ->join('users u', 'u.id = l.usuario_id', 'left')
                ->where('l.folio', $folio)
                ->where('l.deleted_at', null) // Añadido para filtrar eliminados
                ->get()
                ->getResultArray();

            if (empty($lotes)) {
                return redirect()->back()->with('error', 'No se encontraron registros con ese folio.');
            }

            // Procesar lote: evitar duplicados y asegurar floats
            $processedIds = [];
            foreach ($lotes as $k => &$lote) {
                if (isset($processedIds[$lote['id']])) {
                    unset($lotes[$k]);
                    continue;
                }
                $lote['peso'] = floatval($lote['peso'] ?? 0);
                $lote['precio'] = floatval($lote['precio'] ?? 0);
                $lote['costo_total'] = floatval($lote['costo_total'] ?? ($lote['peso'] * $lote['precio']));
                $processedIds[$lote['id']] = true;
            }
            unset($lote);
            $lotes = array_values($lotes);

            // Agrupar por tipo_entrada
            $lotesAgrupados = [];
            $totalGeneralPeso = 0;
            $totalGeneralCosto = 0;

            foreach ($lotes as $lote) {
                $tipo = $lote['tipo_entrada'] ?? 'Sin tipo';
                if (!isset($lotesAgrupados[$tipo])) {
                    $lotesAgrupados[$tipo] = ['lotes' => [], 'subtotal_peso' => 0, 'subtotal_costo' => 0];
                }
                $lotesAgrupados[$tipo]['lotes'][] = $lote;
                $lotesAgrupados[$tipo]['subtotal_peso'] += $lote['peso'];
                $lotesAgrupados[$tipo]['subtotal_costo'] += $lote['costo_total'];

                $totalGeneralPeso += $lote['peso'];
                $totalGeneralCosto += $lote['costo_total'];
            }

            // Logo
            $pathLogo = FCPATH . 'assets/img/logo01.jpg';
            $logo_base64 = file_exists($pathLogo) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($pathLogo)) : null;

            // Generar HTML
            $html = view('reportes/pdf_folio', [
                'folio' => $folio,
                'lotesAgrupados' => $lotesAgrupados,
                'logo_base64' => $logo_base64,
                'fecha' => date('d/m/Y H:i'),
                'totalGeneral' => ['peso' => $totalGeneralPeso, 'costo' => $totalGeneralCosto]
            ]);

            // Configurar Dompdf
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', false);

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream("Folio_{$folio}.pdf", ['Attachment' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Error en generarPdfPorFolio: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    public function pdfFolio()
    {
        $folio = $this->request->getGet('folio');
        if (empty($folio)) {
            return redirect()->back()->with('error', 'Debe ingresar un folio válido.');
        }

        return $this->generarPdfPorFolio($folio);
    }

    public function exportExcel()
    {
        try {
            // Obtener parámetros de filtro
            $tipo = $this->request->getGet('tipo') ?? 'all';
            $entrada = $this->request->getGet('entrada') ?? 'all'; // Este parámetro no se usa actualmente
            $anio = $this->request->getGet('anio') ?? 'all';
            
            // 🔹 Obtener los lotes con filtros
            $builder = $this->loteModel
                ->select('
                    lote_entrada.id,
                    lote_entrada.folio,
                    tipo_pimienta.nombre AS tipo_pimienta,
                    centro.nombre AS centro,
                    tipo_entrada.nombre AS tipo_entrada,
                    users.username AS usuario,
                    lote_entrada.proveedor,
                    lote_entrada.peso_bruto_kg,
                    lote_entrada.peso_vendido_kg,
                    lote_entrada.peso_restante_kg,
                    lote_entrada.precio_compra,
                    lote_entrada.costo_total,
                    lote_entrada.fecha_entrada,
                    lote_entrada.observaciones,
                    lote_entrada.estado
                ')
                ->join('centro', 'centro.id = lote_entrada.centro_id', 'left')
                ->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id', 'left')
                ->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id', 'left')
                ->join('users', 'users.id = lote_entrada.usuario_id', 'left')
                ->where('lote_entrada.deleted_at', null);
            
            // Aplicar filtros
            if ($tipo !== 'all' && !empty($tipo)) {
                $builder->where('tipo_entrada.nombre', $tipo);
            }
            
            if ($anio !== 'all' && !empty($anio)) {
                $builder->where("YEAR(lote_entrada.fecha_entrada)", $anio);
            }
            
            $lotes = $builder->orderBy('lote_entrada.fecha_entrada', 'DESC')
                ->asArray()
                ->findAll();

            if (empty($lotes)) {
                throw new \Exception('No hay lotes para exportar con los filtros seleccionados.');
            }

            // 🔹 Crear spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Lotes Entrada');

            // 🔹 Encabezados
            $headers = [
                'A1' => 'ID',
                'B1' => 'Folio',
                'C1' => 'Centro',
                'D1' => 'Tipo Pimienta',
                'E1' => 'Tipo Entrada',
                'F1' => 'Usuario',
                'G1' => 'Fecha Entrada',
                'H1' => 'Proveedor',
                'I1' => 'Peso Bruto (kg)',
                'J1' => 'Peso Vendido (kg)',
                'K1' => 'Peso Restante (kg)',
                'L1' => 'Precio ($/kg)',
                'M1' => 'Costo Total ($)',
                'N1' => 'Observaciones',
                'O1' => 'Estado',
            ];

            foreach ($headers as $cell => $text) {
                $sheet->setCellValue($cell, $text);
            }

            // 🔹 Contenido
            $row = 2;
            foreach ($lotes as $lote) {
                $folio = !empty($lote['folio'])
                    ? $lote['folio']
                    : 'F-' . str_pad($lote['id'], 4, '0', STR_PAD_LEFT);

                $sheet->setCellValue('A' . $row, $lote['id']);
                $sheet->setCellValue('B' . $row, $folio);
                $sheet->setCellValue('C' . $row, $lote['centro']);
                $sheet->setCellValue('D' . $row, $lote['tipo_pimienta']);
                $sheet->setCellValue('E' . $row, $lote['tipo_entrada']);
                $sheet->setCellValue('F' . $row, $lote['usuario']);
                $sheet->setCellValue('G' . $row, date('d/m/Y', strtotime($lote['fecha_entrada'])));
                $sheet->setCellValue('H' . $row, $lote['proveedor']);
                $sheet->setCellValue('I' . $row, (float)$lote['peso_bruto_kg']);
                $sheet->setCellValue('J' . $row, (float)$lote['peso_vendido_kg']);
                $sheet->setCellValue('K' . $row, (float)$lote['peso_restante_kg']);
                $sheet->setCellValue('L' . $row, (float)$lote['precio_compra']);
                $sheet->setCellValue('M' . $row, (float)$lote['costo_total']);
                $sheet->setCellValue('N' . $row, $lote['observaciones']);
                $sheet->setCellValue('O' . $row, $lote['estado']);
                $row++;
            }

            // 🔹 Formato encabezado
            $headerStyle = $sheet->getStyle('A1:O1');
            $headerStyle->getFont()->setBold(true);
            $headerStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $headerStyle->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('D9E1F2');

            // 🔹 Formato números
            $sheet->getStyle('I2:M' . ($row-1))
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');

            // 🔹 Autoajuste columnas
            foreach (range('A', 'O') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // 🔹 Nombre de archivo
            $filename = 'Lotes_Entrada_' . date('Ymd_His');
            if ($tipo !== 'all') $filename .= '_' . $tipo;
            if ($anio !== 'all') $filename .= '_' . $anio;
            $filename .= '.xlsx';

            // 🔹 Descargar archivo
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            log_message('error', 'Error en exportExcel: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al exportar Excel: ' . $e->getMessage());
        }
    }

    // ========================
    // PDF por lote
    // ========================
   public function lotePDF($id)
{
    try {
        helper(['filesystem', 'url']);

        $lote = $this->loteModel
            ->select('lote_entrada.*, centro.nombre AS centro, tipo_pimienta.nombre AS tipo_pimienta, tipo_entrada.nombre AS tipo_entrada, users.username AS usuario')
            ->join('centro', 'centro.id = lote_entrada.centro_id AND centro.deleted_at IS NULL', 'left')
            ->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id AND tipo_pimienta.deleted_at IS NULL', 'left')
            ->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id AND tipo_entrada.deleted_at IS NULL', 'left')
            ->join('users', 'users.id = lote_entrada.usuario_id', 'left')
            ->where('lote_entrada.id', $id)
            ->where('lote_entrada.deleted_at', null)
            ->first();

        if (!$lote) {
            return redirect()->to('lotes-entrada')->with('error', '❌ Lote no encontrado.');
        }

        // 1. Logo de la Empresa (Izquierda)
        $pathEmpresa = FCPATH . 'assets/img/logo01.jpg';
        $logo_base64 = file_exists($pathEmpresa) 
            ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($pathEmpresa)) 
            : null;

        // 2. Logo Certimex (Derecha) - CORRECCIÓN DE BÚSQUEDA
        $logo_derecha_base64 = null;

// Convertimos a minúsculas y buscamos la raíz "organ" para evitar fallos por tildes o género
$tipoPimienta = mb_strtolower($lote['tipo_pimienta'] ?? '', 'UTF-8');

if (str_contains($tipoPimienta, 'orgán') || str_contains($tipoPimienta, 'organ')) {
    $pathCertimex = FCPATH . 'assets/img/certimex.png';
    
    if (file_exists($pathCertimex)) {
        // Obtenemos la extensión dinámicamente para el MIME type
        $type = pathinfo($pathCertimex, PATHINFO_EXTENSION);
        $data = file_get_contents($pathCertimex);
        $logo_derecha_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}

        $html = view('lotes-entrada/lote_pdf', [
            'lote'             => $lote, 
            'logo_base64'      => $logo_base64,
            'companyLogoRight' => $logo_derecha_base64
        ]);

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        if (ob_get_length()) ob_end_clean();
        return $dompdf->stream('Lote_Entrada_' . $lote['id'] . '.pdf', ['Attachment' => false]);

    } catch (\Exception $e) {
        log_message('error', 'Error en lotePDF: ' . $e->getMessage());
        return redirect()->to('lotes-entrada')->with('error', 'Error: ' . $e->getMessage());
    }
}
    private function getOrganicLogo(?string $tipoProducto): ?string
    {
        return $tipoProducto === 'Orgánico'
            ? $this->getBase64Image(FCPATH . 'assets/img/certimex.png')
            : null;
    }
}