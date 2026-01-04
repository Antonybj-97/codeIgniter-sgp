<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;

class CierreCuentaController extends BaseController
{
    public function index()
    {
        $centroModel = model('CentroModel');

        return view('cierre/formulario', [
            'centros' => $centroModel->findAll()
        ]);
    }

    /** Previsualización del PDF */
    public function previsualizar()
    {
        if (!$this->request->is('post')) {
            return view('errors/html/error_405', ['message' => 'Método no permitido']);
        }

        try {
            $data = $this->procesarDatosParaVista();
            return view('cierre/pdf', [
                'data' => $data,
                'modo_previsualizacion' => true
            ]);
        } catch (Exception $e) {
            log_message('error', 'Error en previsualizar(): ' . $e->getMessage());
            return view('errors/html/error_general', [
                'message' => ENVIRONMENT == 'development' ? $e->getMessage() : 'Error interno'
            ]);
        }
    }

    /** Generar PDF final para descarga */
    public function pdf()
    {
        if (!$this->request->is('post')) {
            return $this->response->setStatusCode(405)->setBody('Método no permitido');
        }

        try {
            $data = $this->procesarDatosParaVista();

            $html = view('cierre/pdf', [
                'data' => $data,
                'modo_previsualizacion' => false
            ]);

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'Arial');
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('letter', 'portrait');
            $dompdf->render();

            $filename = 'cierre-cuenta-' . ($data['folio'] ?? 'documento') . '.pdf';

            return $dompdf->stream($filename, ['Attachment' => true]);
        } catch (Exception $e) {
            log_message('error', 'Error en pdf(): ' . $e->getMessage());
            return $this->response->setStatusCode(500)
                ->setBody('Error interno del servidor');
        }
    }

    /** Guardar registro en base de datos (Legacy/Universal wrapper) */
    public function guardar()
    {
        return $this->guardar_universal();
    }

    /** Guardar registro UNIVERSAL */
    public function guardar_universal()
    {
        if (!$this->request->is('post')) {
            return $this->response->setStatusCode(405)->setJSON([
                'status'  => 'error',
                'message' => 'Método no permitido'
            ]);
        }

        try {
            $data = $this->procesarDatosParaGuardar();

            $cierreModel = model('CierreCuentaModel');
            $id = $cierreModel->insert($data);

            if (!$id) {
                throw new Exception(
                    "Error al guardar: " . json_encode($cierreModel->errors(), JSON_UNESCAPED_UNICODE)
                );
            }

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Cierre guardado correctamente.',
                'id'      => $id
            ]);
        } catch (Exception $e) {
            log_message('error', 'Error en guardar_universal(): ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => ENVIRONMENT == 'development'
                    ? $e->getMessage()
                    : 'Error interno del servidor'
            ]);
        }
    }

    /** Procesar datos para vista (PDF y previsualización) */
    private function procesarDatosParaVista(): array
    {
        // Reutilizamos la lógica de guardado pero decodificamos los JSONs
        $data = $this->procesarDatosParaGuardar();

        // Decodificar JSONs si existen
        $data['pimienta'] = isset($data['pimienta']) ? json_decode($data['pimienta'], true) : [];
        $data['almacen'] = isset($data['almacen']) ? json_decode($data['almacen'], true) : [];
        $data['pagos'] = isset($data['pagos']) ? json_decode($data['pagos'], true) : [];
        $data['comisiones'] = isset($data['comisiones']) ? json_decode($data['comisiones'], true) : [];

        // Calcular totales para la vista
        $this->calcularTotalesParaVista($data);

        return $data;
    }

    /** Procesar datos para guardar en base de datos */
    private function procesarDatosParaGuardar(): array
    {
        $req = $this->request;
        $tipoPimienta = $req->getPost('tipo_pimienta') ?? 'organica';

        // Validar campos requeridos
        $this->validarDatosRequeridos($req);

        $data = [
            'tipo_pimienta'    => $tipoPimienta,
            'cooperativa'      => trim($req->getPost('cooperativa') ?? ''),
            'centro'           => trim($req->getPost('centro')),
            'fecha'            => trim($req->getPost('fecha')),
            'acopiador'        => trim($req->getPost('acopiador')),
            'folio'            => trim($req->getPost('folio')),

            // Resumen Financiero
            'dinero_entregado' => floatval($req->getPost('dinero_entregado') ?? 0),
            'descuento_anticipo' => floatval($req->getPost('descuento_anticipo') ?? 0),
            'otros_cargos'     => floatval($req->getPost('otros_cargos') ?? 0),
            'dinero_comprobado' => floatval($req->getPost('dinero_comprobado') ?? 0),
            'saldo_acopiador'  => floatval($req->getPost('saldo_acopiador') ?? 0),
            'total_dinero_cargo' => floatval($req->getPost('total_dinero_cargo') ?? 0),

            // Firmas
            'firmo_elaboro'    => trim($req->getPost('firmo_elaboro')),
            'firmo_autorizo'   => trim($req->getPost('firmo_autorizo')),
            'firmo_acopiador'  => trim($req->getPost('firmo_acopiador')),

            // Factores de conversión (Convencional)
            'factor_verde_seca' => floatval($req->getPost('factor_verde_seca') ?? 0),
            'factor_rama_seca'  => floatval($req->getPost('factor_rama_seca') ?? 0),
            'factor_precio_pago' => floatval($req->getPost('factor_precio_pago') ?? 3),
            'rendimiento_base'  => floatval($req->getPost('rendimiento_base') ?? 0),

            // Totales principales
            'importe_total_organica' => floatval($req->getPost('importe_total_organica') ?? 0),
            'importe_total_convencional' => floatval($req->getPost('importe_total_convencional') ?? 0),
            
            // Totales calculados del frontend (corregidos según la vista)
            'total_pimienta' => floatval($req->getPost('total_pimienta') ?? 0),
            'total_comisiones_final' => floatval($req->getPost('total_comisiones_final') ?? 0),
            'total_a_pagar' => floatval($req->getPost('total_a_pagar') ?? 0),
            'saldo_final' => floatval($req->getPost('saldo_final') ?? 0),

            // Comisiones orgánicas
            'comision_base_org' => floatval($req->getPost('comision_base_org') ?? 0),
            'comision_rendimiento_org' => floatval($req->getPost('comision_rendimiento_org') ?? 0),
            'comision_cierre_org' => floatval($req->getPost('comision_cierre_org') ?? 0),

            // Fecha de registro
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Procesar tablas según tipo de pimienta
        $pimientaData = [];
        $almacenData = [];
        $pagosData = [];
        $comisionesData = [];

        if ($tipoPimienta === 'organica') {
            // Pimienta orgánica seca
            $pimientaData['seca_organica'] = $this->extraerDatosTabla(
                $req, 
                'seca_organica', 
                ['precio', 'kilos', 'importe', 'observaciones']
            );
        } else {
            // Pimienta convencional - acopio
            $pimientaData['con_rama'] = $this->extraerDatosTabla(
                $req, 
                'con_rama', 
                ['precio', 'kilos', 'importe', 'observaciones']
            );
            $pimientaData['verde'] = $this->extraerDatosTabla(
                $req, 
                'verde', 
                ['precio', 'kilos', 'importe', 'seca_equivalente', 'observaciones']
            );
            $pimientaData['seca_convencional'] = $this->extraerDatosTabla(
                $req, 
                'seca_convencional', 
                ['precio', 'kilos', 'importe', 'observaciones']
            );

            // Pagos convencional (con precio ×3)
            $pagosData['rama_pago'] = $this->extraerDatosTabla(
                $req, 
                'rama_pago', 
                ['precio_acopio', 'precio_pagar', 'kilos_acopiados', 'kilos_entregados_verde', 'kilos_seca_pagar', 'importe']
            );
            $pagosData['verde_pago'] = $this->extraerDatosTabla(
                $req, 
                'verde_pago', 
                ['precio_acopio', 'precio_pagar', 'kilos_acopiados', 'kilos_entregados_verde', 'kilos_seca_pagar', 'importe']
            );
            $pagosData['seca_pago'] = $this->extraerDatosTabla(
                $req, 
                'seca_pago', 
                ['precio_acopio', 'kilos_acopiados', 'kilos_entregados', 'importe']
            );

            // Comisiones convencional
            $comisionesData = $this->extraerDatosTabla(
                $req, 
                'comisiones', 
                ['concepto', 'kilos', 'comision_kilo', 'importe']
            );
        }

        // Tablas comunes (almacén) - tanto para orgánica como convencional
        $almacenData['verde_proceso'] = $this->extraerDatosTabla(
            $req, 
            'verde_proceso', 
            ['fecha', 'folio', 'kilos_centro', 'kilos_beneficio', 'diferencia', 'kilos_seca_resultado', 'rendimiento']
        );
        $almacenData['seca_entregada'] = $this->extraerDatosTabla(
            $req, 
            'seca_entregada', 
            ['fecha', 'folio', 'kilos_centro', 'kilos_almacen', 'diferencia']
        );

        // Codificar como JSON
        $data['pimienta'] = json_encode($pimientaData, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        $data['almacen'] = json_encode($almacenData, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        $data['pagos'] = json_encode($pagosData, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        $data['comisiones'] = json_encode($comisionesData, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

        return $data;
    }

    /** Helper para extraer datos de tablas dinámicas */
    private function extraerDatosTabla($req, $sufijo, $campos): array
    {
        $datos = [];
        
        if (empty($campos)) {
            return $datos;
        }

        // Verificar si existe al menos un campo con datos
        $primerCampo = $campos[0];
        $nombreInput = $primerCampo . '_' . $sufijo;
        $valores = $req->getPost($nombreInput);
        
        // Si no es array o está vacío, retornar array vacío
        if (!is_array($valores) || empty($valores)) {
            return $datos;
        }

        $cantidad = count($valores);

        for ($i = 0; $i < $cantidad; $i++) {
            $fila = [];
            $tieneDatos = false;

            foreach ($campos as $campo) {
                $nombreInput = $campo . '_' . $sufijo;
                $valores = $req->getPost($nombreInput);
                $valor = $valores[$i] ?? '';

                // Limpiar valores numéricos
                if ($this->esCampoNumerico($campo)) {
                    $valor = ($valor === '' || $valor === null) ? 0 : floatval($valor);
                    // Considerar que hay datos si el valor es mayor que cero
                    if ($valor > 0) {
                        $tieneDatos = true;
                    }
                } else {
                    // Para campos de texto, verificar si no está vacío
                    $valor = trim($valor);
                    if ($valor !== '') {
                        $tieneDatos = true;
                    }
                }

                $fila[$campo] = $valor;
            }

            // Solo agregar la fila si tiene datos válidos
            if ($tieneDatos) {
                $datos[] = $fila;
            }
        }

        return $datos;
    }

    /** Determinar si un campo es numérico */
    private function esCampoNumerico(string $campo): bool
    {
        $camposNumericos = [
            'precio', 'kilos', 'importe', 'seca_equivalente',
            'kilos_centro', 'kilos_beneficio', 'diferencia',
            'kilos_seca_resultado', 'rendimiento', 'kilos_almacen',
            'precio_acopio', 'precio_pagar', 'kilos_acopiados',
            'kilos_entregados', 'kilos_entregados_verde', 'kilos_seca_pagar',
            'comision_kilo'
        ];

        return in_array($campo, $camposNumericos);
    }

    /** Validar datos requeridos */
    private function validarDatosRequeridos($req): void
    {
        $camposRequeridos = [
            'centro' => 'Centro de acopio',
            'fecha' => 'Fecha',
            'acopiador' => 'Nombre del acopiador',
            'firmo_elaboro' => 'Firma de elaboración',
            'firmo_autorizo' => 'Firma de autorización',
            'firmo_acopiador' => 'Firma del acopiador'
        ];

        $errores = [];

        foreach ($camposRequeridos as $campo => $nombre) {
            $valor = trim($req->getPost($campo) ?? '');
            if (empty($valor)) {
                $errores[] = "El campo '$nombre' es requerido";
            }
        }

        // Validar que haya al menos un registro de pimienta
        $tipoPimienta = $req->getPost('tipo_pimienta') ?? 'organica';
        $tieneDatosPimienta = false;
        
        if ($tipoPimienta === 'organica') {
            $secaOrganica = $req->getPost('precio_seca_organica') ?? [];
            $tieneDatosPimienta = is_array($secaOrganica) && count(array_filter($secaOrganica)) > 0;
        } else {
            $conRama = $req->getPost('precio_con_rama') ?? [];
            $verde = $req->getPost('precio_verde') ?? [];
            $secaConv = $req->getPost('precio_seca_convencional') ?? [];
            
            $tieneDatosPimienta = 
                (is_array($conRama) && count(array_filter($conRama)) > 0) ||
                (is_array($verde) && count(array_filter($verde)) > 0) ||
                (is_array($secaConv) && count(array_filter($secaConv)) > 0);
        }
        
        if (!$tieneDatosPimienta) {
            $errores[] = "Debe agregar al menos un registro de pimienta acopiada";
        }

        if (!empty($errores)) {
            throw new Exception(implode('. ', $errores));
        }
    }

    /** Calcular totales para la vista del PDF */
    private function calcularTotalesParaVista(array &$data): void
    {
        $tipoPimienta = $data['tipo_pimienta'];
        
        // Inicializar totales
        $data['total_kilos_acopiados'] = 0;
        $data['total_importe_acopiado'] = 0;
        $data['total_kilos_entregados'] = 0;
        $data['total_comisiones_calculadas'] = 0;
        $data['total_importe_pagar'] = 0;

        // Decodificar JSONs si es necesario
        $pimienta = is_string($data['pimienta']) ? json_decode($data['pimienta'], true) : $data['pimienta'];
        $almacen = is_string($data['almacen']) ? json_decode($data['almacen'], true) : $data['almacen'];
        $pagos = is_string($data['pagos']) ? json_decode($data['pagos'], true) : $data['pagos'];
        $comisiones = is_string($data['comisiones']) ? json_decode($data['comisiones'], true) : $data['comisiones'];

        // Calcular totales de pimienta acopiada
        if ($tipoPimienta === 'organica') {
            $secaOrganica = $pimienta['seca_organica'] ?? [];
            foreach ($secaOrganica as $item) {
                $data['total_kilos_acopiados'] += floatval($item['kilos'] ?? 0);
                $data['total_importe_acopiado'] += floatval($item['importe'] ?? 0);
            }
            $data['total_importe_pagar'] = $data['importe_total_organica'] ?? 0;
        } else {
            // Con rama
            $conRama = $pimienta['con_rama'] ?? [];
            foreach ($conRama as $item) {
                $data['total_kilos_acopiados'] += floatval($item['kilos'] ?? 0);
                $data['total_importe_acopiado'] += floatval($item['importe'] ?? 0);
            }
            
            // Verde
            $verde = $pimienta['verde'] ?? [];
            foreach ($verde as $item) {
                $data['total_kilos_acopiados'] += floatval($item['kilos'] ?? 0);
                $data['total_importe_acopiado'] += floatval($item['importe'] ?? 0);
            }
            
            // Seca convencional
            $secaConv = $pimienta['seca_convencional'] ?? [];
            foreach ($secaConv as $item) {
                $data['total_kilos_acopiados'] += floatval($item['kilos'] ?? 0);
                $data['total_importe_acopiado'] += floatval($item['importe'] ?? 0);
            }

            // Calcular total de comisiones
            $comisionesArray = is_array($comisiones) ? $comisiones : [];
            foreach ($comisionesArray as $comision) {
                $data['total_comisiones_calculadas'] += floatval($comision['importe'] ?? 0);
            }

            // Calcular total de pagos
            $ramaPago = $pagos['rama_pago'] ?? [];
            foreach ($ramaPago as $item) {
                $data['total_importe_pagar'] += floatval($item['importe'] ?? 0);
            }
            
            $verdePago = $pagos['verde_pago'] ?? [];
            foreach ($verdePago as $item) {
                $data['total_importe_pagar'] += floatval($item['importe'] ?? 0);
            }
            
            $secaPago = $pagos['seca_pago'] ?? [];
            foreach ($secaPago as $item) {
                $data['total_importe_pagar'] += floatval($item['importe'] ?? 0);
            }
        }

        // Calcular totales de entregas en almacén
        $verdeProceso = $almacen['verde_proceso'] ?? [];
        $secaEntregada = $almacen['seca_entregada'] ?? [];
        
        foreach ($verdeProceso as $item) {
            $data['total_kilos_entregados'] += floatval($item['kilos_centro'] ?? 0);
        }
        
        foreach ($secaEntregada as $item) {
            $data['total_kilos_entregados'] += floatval($item['kilos_centro'] ?? 0);
        }

        // Calcular factor de precio para convencional
        if ($tipoPimienta === 'convencional') {
            $factorPrecioPago = $data['factor_precio_pago'] ?? 3;
            $data['factor_precio_pago_texto'] = "Precio a pagar = Precio acopio × $factorPrecioPago";
        }

        // Calcular diferencia financiera
        $data['diferencia_financiera'] = $data['total_dinero_cargo'] - $data['dinero_comprobado'];
        
        // Determinar estado del saldo
        $data['estado_saldo'] = $data['saldo_final'] >= 0 ? 'A FAVOR' : 'EN CONTRA';
        $data['clase_saldo'] = $data['saldo_final'] >= 0 ? 'text-success' : 'text-danger';
        
        // Redondear valores para presentación
        $data['total_kilos_acopiados'] = round($data['total_kilos_acopiados'], 2);
        $data['total_importe_acopiado'] = round($data['total_importe_acopiado'], 2);
        $data['total_kilos_entregados'] = round($data['total_kilos_entregados'], 2);
        $data['total_comisiones_calculadas'] = round($data['total_comisiones_calculadas'], 2);
        $data['total_importe_pagar'] = round($data['total_importe_pagar'], 2);
    }

    /** Método para obtener historial de cierres */
    public function historial()
    {
        $cierreModel = model('CierreCuentaModel');
        
        $fechaInicio = $this->request->getGet('fecha_inicio');
        $fechaFin = $this->request->getGet('fecha_fin');
        $tipo = $this->request->getGet('tipo');
        
        $query = $cierreModel->orderBy('created_at', 'DESC');
        
        if ($fechaInicio) {
            $query->where('fecha >=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $query->where('fecha <=', $fechaFin);
        }
        
        if ($tipo && $tipo !== 'todos') {
            $query->where('tipo_pimienta', $tipo);
        }
        
        $cierres = $query->findAll();
        
        return view('cierre/historial', [
            'cierres' => $cierres,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'tipo' => $tipo
        ]);
    }

    /** Ver detalle de un cierre */
    public function ver($id)
    {
        $cierreModel = model('CierreCuentaModel');
        $cierre = $cierreModel->find($id);
        
        if (!$cierre) {
            return redirect()->to('/cierre/historial')->with('error', 'Cierre no encontrado');
        }
        
        // Decodificar JSONs
        $cierre['pimienta'] = isset($cierre['pimienta']) ? json_decode($cierre['pimienta'], true) : [];
        $cierre['almacen'] = isset($cierre['almacen']) ? json_decode($cierre['almacen'], true) : [];
        $cierre['pagos'] = isset($cierre['pagos']) ? json_decode($cierre['pagos'], true) : [];
        $cierre['comisiones'] = isset($cierre['comisiones']) ? json_decode($cierre['comisiones'], true) : [];
        
        return view('cierre/detalle', ['cierre' => $cierre]);
    }
}