<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoteEntradaModel;
use App\Models\LoteSalidaModel; // Añadido: Importación faltante
use App\Models\CentroModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteController extends BaseController
{
    // Propiedades de modelos
    protected $loteModel;
    protected $loteSalidaModel;
    protected $centroModel;

    // Constantes y Helpers
    protected $helpers = ['form', 'url', 'text'];
    private const TIPOS_PRODUCTO = ['Orgánico', 'Convencional'];
    private const UNIDADES = ['kg', 'g', 'lb', 'ton', 'cajas', 'piezas'];
    private const FOLIO_PREFIX = 'NS';

    public function __construct()
    {
        // Inicialización de modelos
        $this->loteModel       = new LoteEntradaModel();
        $this->loteSalidaModel = new LoteSalidaModel();
        $this->centroModel     = new CentroModel();

        // Configurar zona horaria
        date_default_timezone_set('America/Mexico_City');
    }

    /**
     * Vista del formulario de acopio
     */
    public function acopio()
    {
        $data = [
            'centros' => $this->centroModel->where('deleted_at', null)->findAll(),
            'cosecha' => date('Y'),
            'titulo'  => 'Reporte de Acopio de Pimienta'
        ];

        return view('reportes/acopio_form', $data);
    }

    /**
     * Obtiene entradas con joins y cálculo preciso de inventario.
     */
    private function obtenerEntradasCompletas($tipoPimientaId = 'all', $tipoEntradaId = 'all', $anio = 'all')
    {
        $builder = $this->loteModel->builder();

        $builder->select("
            lote_entrada.*,
            centro.nombre AS centro,
            tipo_pimienta.nombre AS tipo_pimienta,
            tipo_entrada.nombre AS tipo_entrada,
            users.username AS usuario,
            lote_entrada.peso_usado_kg AS peso_vendido,
            (lote_entrada.peso_bruto_kg - lote_entrada.peso_usado_kg) AS peso_restante
        ");

        $builder->join('centro', 'centro.id = lote_entrada.centro_id', 'left');
        $builder->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id', 'left');
        $builder->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id', 'left');
        $builder->join('users', 'users.id = lote_entrada.usuario_id', 'left');

        // Filtros robustos
        if ($tipoPimientaId !== 'all' && !empty($tipoPimientaId)) {
            $builder->where('lote_entrada.tipo_pimienta_id', $tipoPimientaId);
        }
        
        if ($tipoEntradaId !== 'all' && !empty($tipoEntradaId)) {
            $builder->where('lote_entrada.tipo_entrada_id', $tipoEntradaId);
        }
        
        if ($anio !== 'all' && !empty($anio)) {
            $builder->where('YEAR(lote_entrada.fecha_entrada)', $anio);
        }

        return $builder->where('lote_entrada.deleted_at', null)
                       ->orderBy('lote_entrada.fecha_entrada', 'DESC')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Exportación a PDF (Formato Horizontal)
     */
    public function entradasPdf($tipoPimientaId = 'all', $tipoEntradaId = 'all', $anio = 'all')
    {
        if ($anio === 'all') {
            $anio = $this->request->getGet('anio') ?? date('Y');
        }

        $entradas = $this->obtenerEntradasCompletas($tipoPimientaId, $tipoEntradaId, $anio);

        if (empty($entradas)) {
            return redirect()->back()->with('error', "No hay datos para generar el reporte.");
        }

        $html = view('reportes/entradas_pdf', [
            'entradas' => $entradas,
            'fecha'    => date('d/m/Y H:i'),
            'anio'     => ($anio !== 'all') ? $anio : 'Todos los años'
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); 
        $dompdf->render();
        
        $filename = "reporte_entradas_" . date('Ymd_His') . ".pdf";
        
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($dompdf->output());
    }

    /**
     * Exportación a Excel
     */
    public function entradasExcel($tipoPimientaId = 'all', $tipoEntradaId = 'all', $anio = 'all')
    {
        if ($anio === 'all') {
            $anio = $this->request->getGet('anio') ?? date('Y');
        }

        $entradas = $this->obtenerEntradasCompletas($tipoPimientaId, $tipoEntradaId, $anio);

        if (empty($entradas)) {
            return redirect()->back()->with('error', "No hay datos para exportar.");
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Encabezados y datos (Simplificado para brevedad)
        $sheet->setCellValue('A1', "REPORTE DE ENTRADAS - " . $anio);
        $headers = ['Folio', 'Centro', 'Tipo', 'Entrada', 'Proveedor', 'Peso Bruto', 'Vendido', 'Restante', 'Costo', 'Fecha', 'Estado'];
        $sheet->fromArray($headers, NULL, 'A3');

        $row = 4;
        foreach ($entradas as $e) {
            $sheet->fromArray([
                $e['folio'], $e['centro'], $e['tipo_pimienta'], $e['tipo_entrada'],
                $e['proveedor'], $e['peso_bruto_kg'], $e['peso_vendido'], 
                $e['peso_restante'], $e['costo_total'], $e['fecha_entrada'], $e['estado']
            ], NULL, "A$row");
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "entradas_" . date('Ymd_His') . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }

    /**
     * Lógica de Acopio PDF
     */
    public function acopioPdf()
    {
        $post = $this->request->getPost();
        if (!$post) return redirect()->to('/reportes/acopio');

        $centro = $this->centroModel->find($post['centro'] ?? 0);

        $data = [
            'centro'      => $centro['nombre'] ?? 'General',
            'fecha'       => $post['fecha'] ?? date('Y-m-d'),
            'acopiador'   => $post['acopiador'] ?? 'N/A',
            'cosecha'     => $post['cosecha'] ?? '',
            'finanzas'    => [
                'entregado'  => $post['dinero_entregado'] ?? 0,
                'comprobado' => $post['dinero_comprobado'] ?? 0,
                'cargos'     => $post['otros_cargos'] ?? 0,
                'saldo'      => $post['saldo_acopiador'] ?? 0
            ],
            'detalle'     => [
                'con_rama' => $this->procesarTablaDinamica($post, 'con_rama'),
                'verde'    => $this->procesarTablaDinamica($post, 'verde'),
                'seca'     => $this->procesarTablaDinamica($post, 'seca')
            ],
            'comisiones'  => $this->extraerComisiones($post),
            'logo_base64' => $this->getBase64Image(FCPATH . 'assets/img/logo.png') // Ejemplo de uso
        ];

        $html = view('reportes/acopio_pdf', $data);

        $dompdf = new Dompdf(['isRemoteEnabled' => true]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response->setHeader('Content-Type', 'application/pdf')
                              ->setBody($dompdf->output())
                              ->download("cierre_acopio_{$data['acopiador']}.pdf", null);
    }

    // --- MÉTODOS PRIVADOS DE APOYO ---

    private function getBase64Image($path) {
        if (!file_exists($path)) return null;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    private function procesarTablaDinamica($post, $tipo)
    {
        $items = [];
        $precios = $post["precio_{$tipo}"] ?? [];
        $kilos   = $post["kilos_{$tipo}"] ?? [];
        $importes = $post["importe_{$tipo}"] ?? [];

        $totalK = 0; $totalI = 0;

        foreach ($precios as $i => $p) {
            if (!empty($kilos[$i])) {
                $k = floatval($kilos[$i]);
                $imp = floatval($importes[$i]);
                $items[] = ['precio' => $p, 'kilos' => $k, 'importe' => $imp];
                $totalK += $k; 
                $totalI += $imp;
            }
        }
        return ['items' => $items, 'total_kilos' => $totalK, 'total_importe' => $totalI];
    }

    private function extraerComisiones($post)
    {
        return [
            'con_rama'       => $post['comision_con_rama'] ?? 0,
            'verde'          => $post['comision_verde'] ?? 0,
            'seca'           => $post['comision_seca'] ?? 0,
            'beneficio'      => $post['comision_beneficio'] ?? 0,
            'rendimiento'    => $post['comision_rendimiento'] ?? 0,
            'fecha_temprana' => $post['comision_fecha'] ?? 0
        ];
    }
}