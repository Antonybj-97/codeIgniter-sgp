<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DetalleProcesoMasivoModel;

class ProcesoPimientaModel extends Model
{
    protected $table          = 'procesos';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';
    
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields  = [
        'fecha_proceso',
        'tipo_proceso',
        'observacion_proceso',
        'proveedor',
        'peso_bruto_kg',
        'peso_estimado_kg',
        'peso_final_kg',
        'estado_proceso',
        'lote_entrada_id',
        'tipo_entrada_id',
        'fecha_fin',
        'usuario_id',
        'es_proceso_masivo',
        'lote_proceso_id'
    ];

    /**
     * Crear procesos masivos
     */
    public function crearProcesosMasivos(array $datosProcesos): bool
    {
        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $loteProcesoId = uniqid('masivo_');
            
            foreach ($datosProcesos as $proceso) {
                $procesoData = [
                    'fecha_proceso' => date('Y-m-d H:i:s'),
                    'tipo_proceso' => $proceso['tipo_proceso'],
                    'peso_bruto_kg' => $proceso['peso_inicial'],
                    'peso_estimado_kg' => $proceso['peso_estimado'],
                    'peso_final_kg' => 0,
                    'estado_proceso' => 'en_proceso',
                    'lote_entrada_id' => $proceso['lote_id'],
                    'proveedor' => $proceso['proveedor'] ?? '',
                    'observacion_proceso' => 'Proceso masivo - ' . $proceso['tipo_proceso'],
                    'usuario_id' => $proceso['usuario_id'] ?? null,
                    'es_proceso_masivo' => 1,
                    'lote_proceso_id' => $loteProcesoId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                if (!$this->insert($procesoData)) {
                    throw new \Exception('Error al crear proceso para lote: ' . $proceso['lote_id']);
                }
            }
            
            $db->transComplete();
            return $db->transStatus();
            
        } catch (\Exception $e) {
            log_message('error', 'Error en crearProcesosMasivos: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener procesos por lote de proceso masivo
     */
    public function getProcesosMasivos(string $loteProcesoId): array
    {
        return $this->where('lote_proceso_id', $loteProcesoId)
                    ->where('deleted_at', null)
                    ->findAll();
    }

    /**
     * Obtener datos para DataTables server-side incluyendo procesos masivos
     */
    public function getProcesosDataTable(array $postData): array
    {
        $draw  = (int)($postData['draw'] ?? 1);
        $start = (int)($postData['start'] ?? 0);
        $length = (int)($postData['length'] ?? 10);
        $searchValue = trim($postData['search']['value'] ?? '');
        $orderColumnIndex = (int)($postData['order'][0]['column'] ?? 0);
        $orderDir = ($postData['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

        $orderColumnMap = [
            0 => 'procesos.id',
            1 => 'procesos.fecha_proceso',
            2 => 'procesos.tipo_proceso',
            3 => 'procesos.estado_proceso',
            4 => 'procesos.peso_bruto_kg',
            5 => 'procesos.peso_estimado_kg',
            6 => 'procesos.peso_final_kg',
            7 => 'procesos.observacion_proceso',
            8 => 'lote_entrada.proveedor',
            9 => 'procesos.es_proceso_masivo'
        ];

        $orderColumn = $orderColumnMap[$orderColumnIndex] ?? 'procesos.id';

        $builder = $this->db->table($this->table . ' AS procesos')
            ->select('
                procesos.id,
                procesos.fecha_proceso,
                procesos.tipo_proceso,
                procesos.estado_proceso,
                procesos.peso_bruto_kg,
                procesos.peso_estimado_kg,
                procesos.peso_final_kg,
                procesos.observacion_proceso,
                procesos.fecha_fin,
                procesos.es_proceso_masivo,
                procesos.lote_proceso_id,
                lote_entrada.proveedor,
                lote_entrada.folio as folio_lote
            ')
            ->join('lote_entrada', 'lote_entrada.id = procesos.lote_entrada_id', 'left')
            ->where('procesos.deleted_at', null);

        if ($searchValue !== '') {
            $builder->groupStart()
                ->like('procesos.id', $searchValue)
                ->orLike('procesos.tipo_proceso', $searchValue)
                ->orLike('procesos.estado_proceso', $searchValue)
                ->orLike('procesos.observacion_proceso', $searchValue)
                ->orLike('DATE(procesos.fecha_proceso)', $searchValue)
                ->orLike('lote_entrada.proveedor', $searchValue)
                ->orLike('lote_entrada.folio', $searchValue)
                ->orLike('procesos.lote_proceso_id', $searchValue)
                ->groupEnd();
        }

        $countBuilder = clone $builder;
        $recordsFiltered = $countBuilder->countAllResults(false);

        $recordsTotal = $this->where('deleted_at', null)->countAllResults();

        $builder->orderBy($orderColumn, $orderDir)
                ->limit($length, $start);

        $data = $builder->get()->getResultArray();

        foreach ($data as &$row) {
            $row['peso_bruto_kg']       = (float)($row['peso_bruto_kg'] ?? 0);
            $row['peso_estimado_kg']    = (float)($row['peso_estimado_kg'] ?? 0);
            $row['peso_final_kg']       = (float)($row['peso_final_kg'] ?? 0);
            $row['observacion_proceso'] = $row['observacion_proceso'] ?? '';
            $row['proveedor']           = $row['proveedor'] ?? '-';
            $row['folio_lote']          = $row['folio_lote'] ?? '-';
            $row['estado_proceso']      = $row['estado_proceso'] ?? 'Pendiente';
            $row['tipo_proceso']        = $row['tipo_proceso'] ?? '-';
            $row['fecha_proceso']       = $row['fecha_proceso'] ?? '';
            $row['fecha_fin']           = $row['fecha_fin'] ?? '';
            $row['es_proceso_masivo']   = (bool)($row['es_proceso_masivo'] ?? false);
            $row['lote_proceso_id']     = $row['lote_proceso_id'] ?? '';
            
            $row['badge_masivo'] = $row['es_proceso_masivo'] ? 
                '<span class="badge bg-info">Masivo</span>' : '';
        }

        return [
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data
        ];
    }

    /**
     * Actualizar estado de un proceso
     */
    public function actualizarEstado(int $procesoId, string $estado, ?float $pesoFinal = null): bool
    {
        $data = [
            'estado_proceso' => $estado,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($estado === 'completado' && $pesoFinal !== null) {
            $data['peso_final_kg'] = $pesoFinal;
            $data['fecha_fin'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($procesoId, $data);
    }

    /**
     * Obtener resumen de procesos masivos
     */
    public function getResumenProcesosMasivos(): array
    {
        return $this->select("
                lote_proceso_id,
                COUNT(*) as total_procesos,
                SUM(peso_bruto_kg) as total_peso_bruto,
                SUM(peso_final_kg) as total_peso_final,
                MIN(fecha_proceso) as fecha_inicio,
                MAX(fecha_fin) as fecha_fin,
                tipo_proceso
            ")
            ->where('es_proceso_masivo', 1)
            ->where('deleted_at', null)
            ->groupBy('lote_proceso_id, tipo_proceso')
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
    }

    /** Conteo total con soft deletes */
    public function countAll(): int
    {
        return $this->where('deleted_at', null)->countAllResults();
    }

    /**
     * Obtener procesos por lote de entrada
     */
    public function getProcesosPorLote(int $loteId): array
    {
        return $this->where('lote_entrada_id', $loteId)
                    ->where('deleted_at', null)
                    ->orderBy('fecha_proceso', 'DESC')
                    ->findAll();
    }

    
}