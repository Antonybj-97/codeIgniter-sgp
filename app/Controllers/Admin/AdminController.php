<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\API\ResponseTrait;

class AdminController extends BaseController
{
    use ResponseTrait;

    /**
     * @var BaseConnection Instancia de la conexión a la base de datos.
     */
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ==========================================================
    // VISTAS
    // ==========================================================
    
    /**
     * Muestra la vista principal del Dashboard.
     */
    public function dashboard(): string
    {
        return view('admin/dashboard');
    }

    // ==========================================================
    // ENDPOINTS AJAX
    // ==========================================================

    /**
     * Carga datos principales: KPIs, Movimientos recientes y Gráfico Entradas/Salidas mensuales.
     * URL: /admin/dashboard-ajax
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function dashboardAjax()
    {
        // Validar y obtener año
        $anio = $this->request->getGet('anio');
        if (!$anio || !is_numeric($anio)) {
            $anio = date('Y');
        }
        $anio = (int) $anio;
        
        // Validar rango del año (últimos 10 años)
        $anioActual = date('Y');
        if ($anio < ($anioActual - 10) || $anio > $anioActual) {
            $anio = $anioActual;
        }

        try {
            // Obtener todos los datos necesarios
            $datos = [
                // KPIs - usando nombres que el frontend espera
                'entradas_mes_total'    => $this->getEntradasAnuales($anio),
                'salidas_mes_total'     => $this->getSalidasAnuales($anio),
                'lotes_pendientes'      => $this->getLotesPendientes(),
                'total_lotes'           => $this->getTotalLotes(),

                // Datos para gráficos sparklines (nombres que el frontend espera)
                'entradas_mes'          => $this->getMensualesEntradas($anio),
                'salidas_mes'           => $this->getMensualesSalidas($anio),
                
                // Tabla de Movimientos
                'ultimos_movimientos'   => $this->getUltimosMovimientos(),
                
                // Gráfico principal Entradas vs Salidas
                'entradas'              => $this->getMensualesEntradas($anio),
                'salidas'               => $this->getMensualesSalidas($anio),
                'meses'                 => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 
                                           'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            ];

            return $this->respond($datos);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en dashboardAjax: ' . $e->getMessage());
            return $this->failServerError('Error al cargar los datos del dashboard');
        }
    }

    /**
     * Carga datos para el gráfico de Inventario agrupado (por Tipo o Centro).
     * URL: /admin/dashboard-inventario-ajax
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function dashboardInventarioAjax()
    {
        // Validar y obtener parámetros
        $anio = $this->request->getGet('anio');
        if (!$anio || !is_numeric($anio)) {
            $anio = date('Y');
        }
        $anio = (int) $anio;
        
        $tipoAgrupacion = $this->request->getGet('tipo') ?? 'tipo';
        
        // Validar el tipo de agrupación
        if (!in_array($tipoAgrupacion, ['tipo', 'centro'])) {
            return $this->fail('Parámetro "tipo" debe ser "tipo" o "centro"', 400);
        }

        try {
            $resultados = $this->getInventarioAgrupado($anio, $tipoAgrupacion);
            
            if (empty($resultados)) {
                return $this->respond([
                    'labels'   => [],
                    'datasets' => [[
                        'label' => "Inventario por {$tipoAgrupacion} ({$anio})",
                        'data'  => [],
                    ]],
                ]);
            }
            
            $response = [
                'labels'   => array_column($resultados, 'nombre'),
                'datasets' => [[
                    'label' => "Inventario por {$tipoAgrupacion} ({$anio})",
                    'data'  => array_map('floatval', array_column($resultados, 'total_peso')),
                ]],
            ];
            
            return $this->respond($response);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en dashboardInventarioAjax: ' . $e->getMessage());
            return $this->failServerError('Error al cargar los datos de inventario');
        }
    }

    /**
     * Carga datos para el gráfico de Inventario Combinado (Tipo + Centro).
     * URL: /admin/dashboard-inventario-combinado-ajax
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function dashboardInventarioCombinadoAjax()
    {
        // Validar y obtener año
        $anio = $this->request->getGet('anio');
        if (!$anio || !is_numeric($anio)) {
            $anio = date('Y');
        }
        $anio = (int) $anio;

        try {
            // Consulta optimizada para inventario combinado
            $resultados = $this->db->table('lote_entrada AS le')
                ->select('
                    te.nombre AS tipo_entrada, 
                    c.nombre AS centro, 
                    COALESCE(SUM(le.peso_bruto_kg), 0) AS total_peso
                ')
                ->join('tipo_entrada AS te', 'te.id = le.tipo_entrada_id AND te.deleted_at IS NULL', 'left')
                ->join('centro AS c', 'c.id = le.centro_id AND c.deleted_at IS NULL', 'left')
                ->where('le.deleted_at', null)
                ->where('YEAR(le.fecha_entrada)', $anio)
                ->groupBy(['te.nombre', 'c.nombre'])
                ->orderBy('c.nombre', 'ASC')
                ->orderBy('te.nombre', 'ASC')
                ->get()
                ->getResultArray();

            // Si no hay resultados, devolver estructura vacía
            if (empty($resultados)) {
                return $this->respond([
                    'labels'   => [],
                    'datasets' => [],
                ]);
            }

            // Procesar los datos para el gráfico
            $labels = array_values(array_unique(array_column($resultados, 'centro')));
            $tipos = array_values(array_unique(array_column($resultados, 'tipo_entrada')));
            $datasets = [];

            // Paleta de colores consistente con el frontend
            $palette = ['#4a7c2f', '#6b9b37', '#d97706', '#b45309', '#2d5016', '#92400e', '#5a8529', '#f59e0b'];

            // Preparar datos para cada tipo
            foreach ($tipos as $i => $tipo) {
                $data = [];
                foreach ($labels as $centro) {
                    $peso = 0.0;
                    // Buscar el peso para esta combinación tipo-centro
                    foreach ($resultados as $r) {
                        if ($r['tipo_entrada'] === $tipo && $r['centro'] === $centro) {
                            $peso = (float) $r['total_peso'];
                            break;
                        }
                    }
                    $data[] = $peso;
                }

                $datasets[] = [
                    'label'           => $tipo,
                    'data'            => $data,
                    'backgroundColor' => $palette[$i % count($palette)],
                ];
            }

            return $this->respond([
                'labels'   => $labels,
                'datasets' => $datasets,
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en dashboardInventarioCombinadoAjax: ' . $e->getMessage());
            return $this->failServerError('Error al cargar los datos de inventario combinado');
        }
    }

    // ==========================================================
    // MÉTODOS PRIVADOS (CONSULTAS A DB)
    // ==========================================================

    /**
     * Obtiene la cantidad de lotes pendientes.
     */
    private function getLotesPendientes(): int
    {
        try {
            return (int) $this->db->table('lote_entrada')
                ->where('estado', 'Pendiente')
                ->where('deleted_at', null)
                ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error en getLotesPendientes: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene el total de lotes.
     */
    private function getTotalLotes(): int
    {
        try {
            return (int) $this->db->table('lote_entrada')
                ->where('deleted_at', null)
                ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error en getTotalLotes: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene los últimos movimientos (entradas y salidas).
     */
    private function getUltimosMovimientos(): array
    {
        try {
            // Usando la estructura real de tu base de datos
            $query = $this->db->query("
                SELECT * FROM (
                    -- Entradas
                    SELECT 
                        id AS id_movimiento,
                        'entrada' AS tipo,
                        peso_bruto_kg AS peso,
                        fecha_entrada AS fecha,
                        proveedor AS cliente_proveedor,
                        tipo_entrada_id,
                        created_at,
                        NULL AS folio_salida,
                        NULL AS tipo_producto,
                        NULL AS producto,
                        NULL AS unidad,
                        NULL AS cantidad,
                        NULL AS no_maquila,
                        NULL AS no_factura,
                        NULL AS certificado,
                        NULL AS clave_lote
                    FROM lote_entrada
                    WHERE deleted_at IS NULL
                    
                    UNION ALL
                    
                    -- Salidas (usando la estructura real de tu tabla)
                    SELECT 
                        id_salida AS id_movimiento,
                        'salida' AS tipo,
                        cantidad AS peso,
                        fecha_embarque AS fecha,
                        nombre_cliente AS cliente_proveedor,
                        NULL AS tipo_entrada_id,
                        created_at,
                        folio_salida,
                        tipo_producto,
                        producto,
                        unidad,
                        cantidad,
                        no_maquila,
                        no_factura,
                        certificado,
                        clave_lote
                    FROM lote_salida  -- Nombre real de tu tabla de salidas
                    WHERE deleted_at IS NULL
                ) AS t
                ORDER BY fecha DESC, created_at DESC
                LIMIT 100
            ");
            
            $resultados = $query->getResultArray();
            
            // Formatear datos para el frontend
            foreach ($resultados as &$row) {
                if (!empty($row['fecha'])) {
                    // Formatear fecha para el frontend
                    $row['fecha'] = date('Y-m-d', strtotime($row['fecha']));
                }
                
                // Asegurar que el peso sea numérico
                $row['peso'] = (float) ($row['peso'] ?? 0);
                
                // Estructura que espera el frontend
                $row['cliente'] = $row['cliente_proveedor'] ?? null;
                $row['proveedor'] = ($row['tipo'] === 'entrada') ? $row['cliente_proveedor'] : null;
            }
            
            return $resultados;
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getUltimosMovimientos: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene datos mensuales para ENTRADAS (lote_entrada)
     */
    private function getMensualesEntradas(int $anio): array
    {
        try {
            // Inicializar array con 12 meses en cero
            $result = array_fill(0, 12, 0.0);

            $query = $this->db->table('lote_entrada')
                ->select("MONTH(fecha_entrada) AS mes, COALESCE(SUM(peso_bruto_kg), 0) AS total")
                ->where('deleted_at', null)
                ->where('YEAR(fecha_entrada)', $anio)
                ->groupBy('mes')
                ->orderBy('mes')
                ->get();

            if ($query->getNumRows() > 0) {
                foreach ($query->getResultArray() as $row) {
                    $mes = (int) $row['mes'] - 1; // Convertir a índice 0-11
                    $result[$mes] = (float) $row['total'];
                }
            }

            return $result;
            
        } catch (\Exception $e) {
            log_message('error', "Error en getMensualesEntradas: " . $e->getMessage());
            return array_fill(0, 12, 0.0);
        }
    }
    
    /**
     * Obtiene datos mensuales para SALIDAS (tabla salidas)
     */
    private function getMensualesSalidas(int $anio): array
    {
        try {
            // Inicializar array con 12 meses en cero
            $result = array_fill(0, 12, 0.0);

            // Usar la estructura real de tu tabla de salidas
            $query = $this->db->table('lote_salida')  // Nombre real de tu tabla
                ->select("MONTH(fecha_embarque) AS mes, COALESCE(SUM(cantidad), 0) AS total")
                ->where('deleted_at', null)
                ->where('YEAR(fecha_embarque)', $anio)
                ->groupBy('mes')
                ->orderBy('mes')
                ->get();

            if ($query->getNumRows() > 0) {
                foreach ($query->getResultArray() as $row) {
                    $mes = (int) $row['mes'] - 1; // Convertir a índice 0-11
                    $result[$mes] = (float) $row['total'];
                }
            }

            return $result;
            
        } catch (\Exception $e) {
            log_message('error', "Error en getMensualesSalidas: " . $e->getMessage());
            return array_fill(0, 12, 0.0);
        }
    }
    
    /**
     * Obtiene el total de entradas anuales.
     */
    private function getEntradasAnuales(int $anio): float
    {
        try {
            $row = $this->db->table('lote_entrada')
                ->selectSum('peso_bruto_kg', 'total_peso')
                ->where('deleted_at', null)
                ->where('YEAR(fecha_entrada)', $anio)
                ->get()
                ->getRowArray();

            return (float) ($row['total_peso'] ?? 0);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getEntradasAnuales: ' . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * Obtiene el total de salidas anuales.
     */
    private function getSalidasAnuales(int $anio): float
    {
        try {
            // Usar la tabla real de salidas y el campo cantidad
            $row = $this->db->table('lote_salida')  // Nombre real de tu tabla
                ->selectSum('cantidad', 'total_peso')
                ->where('deleted_at', null)
                ->where('YEAR(fecha_embarque)', $anio)
                ->get()
                ->getRowArray();

            return (float) ($row['total_peso'] ?? 0);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getSalidasAnuales: ' . $e->getMessage());
            return 0.0;
        }
    }
    
    /**
     * Obtiene inventario agrupado por tipo o centro.
     */
    private function getInventarioAgrupado(int $anio, string $groupBy): array
    {
        try {
            // Validar parámetro de agrupación
            if (!in_array($groupBy, ['tipo', 'centro'])) {
                return [];
            }
            
            // Configurar campos según tipo de agrupación
            if ($groupBy === 'tipo') {
                $selectField = 'te.nombre';
                $joinTable = 'tipo_entrada';
                $joinAlias = 'te';
                $joinField = 'tipo_entrada_id';
            } else {
                $selectField = 'c.nombre';
                $joinTable = 'centro';
                $joinAlias = 'c';
                $joinField = 'centro_id';
            }
            
            $resultados = $this->db->table('lote_entrada AS le')
                ->select("{$selectField} AS nombre, COALESCE(SUM(le.peso_bruto_kg), 0) AS total_peso")
                ->join("{$joinTable} AS {$joinAlias}", 
                    "{$joinAlias}.id = le.{$joinField} AND {$joinAlias}.deleted_at IS NULL", 
                    'left')
                ->where('le.deleted_at', null)
                ->where('YEAR(le.fecha_entrada)', $anio)
                ->groupBy('nombre')
                ->orderBy('total_peso', 'DESC')
                ->get();
                
            if ($resultados->getNumRows() > 0) {
                return $resultados->getResultArray();
            }
            
            return [];
            
        } catch (\Exception $e) {
            log_message('error', "Error en getInventarioAgrupado para $groupBy: " . $e->getMessage());
            return [];
        }
    }

    // ==========================================================
    // MÉTODO AUXILIAR PARA DEBUG
    // ==========================================================
    
    /**
     * Método auxiliar para verificar estructura de tablas.
     * Solo para desarrollo - eliminar en producción.
     */
    public function debugTablas()
    {
        if (ENVIRONMENT !== 'development') {
            return $this->failForbidden('Acceso no permitido');
        }

        $tablas = ['lote_entrada', 'lote_salida'];
        $resultados = [];

        foreach ($tablas as $tabla) {
            if ($this->db->tableExists($tabla)) {
                $fields = $this->db->getFieldData($tabla);
                $resultados[$tabla] = array_map(function($field) {
                    return [
                        'name' => $field->name,
                        'type' => $field->type,
                        'max_length' => $field->max_length,
                        'primary_key' => $field->primary_key ?? 0,
                    ];
                }, $fields);
            } else {
                $resultados[$tabla] = "Tabla no existe";
            }
        }

        return $this->respond($resultados);
    }
}