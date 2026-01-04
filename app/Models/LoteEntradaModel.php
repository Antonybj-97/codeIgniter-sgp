<?php
namespace App\Models;

use CodeIgniter\Model;

class LoteEntradaModel extends Model
{
    protected $table            = 'lote_entrada';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'entrada_id',
        'folio',
        'centro_id',
        'tipo_pimienta_id',
        'tipo_entrada_id',
        'usuario_id',
        'peso_bruto_kg',
        'precio_compra',
        'costo_total',
        'proveedor',
        'observaciones',
        'estado',
        'fecha_entrada'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $dateFormat    = 'datetime';

    protected $afterInsert  = ['clearCache'];
    protected $afterUpdate  = ['clearCache'];
    protected $afterDelete  = ['clearCache'];

    protected function clearCache(array $data)
    {
        \Config\Services::cache()->delete('entradas_mes');
        return $data;
    }

    /**
     * Obtiene un lote por su ID con datos relacionados
     */
    public function getById(int $id): ?array
    {
        return $this->select('lote_entrada.*, 
                              centro.nombre AS centro,
                              tipo_pimienta.nombre AS tipo_pimienta,
                              tipo_entrada.nombre AS tipo_entrada,
                              usuario.nombre_completo AS usuario')
                    ->join('centro', 'centro.id = lote_entrada.centro_id', 'left')
                    ->join('tipo_pimienta', 'tipo_pimienta.id = lote_entrada.tipo_pimienta_id', 'left')
                    ->join('tipo_entrada', 'tipo_entrada.id = lote_entrada.tipo_entrada_id', 'left')
                    ->join('usuario', 'usuario.id = lote_entrada.usuario_id', 'left')
                    ->where('lote_entrada.id', $id)
                    ->first();
    }

    /**
     * Obtiene entradas filtradas por mes, año y centro
     */
    public function getFilteredEntries(?int $mes = null, ?int $anio = null, ?int $centro_id = null): array
    {
        $builder = $this->builder();

        if ($centro_id) {
            $builder->where('centro_id', $centro_id);
        }

        if ($mes && $anio) {
            $inicio = date("Y-m-d", strtotime("$anio-$mes-01"));
            $fin = date("Y-m-t", strtotime($inicio));
            $builder->where('fecha_entrada >=', $inicio)
                    ->where('fecha_entrada <=', $fin);
        } elseif ($mes) {
            $builder->where('MONTH(fecha_entrada)', $mes);
        } elseif ($anio) {
            $builder->where('YEAR(fecha_entrada)', $anio);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene estadísticas mensuales de entradas y salidas
     */
    public function getMonthlyStats(string $inicioMes, string $finMes): array
    {
        return $this->select([
                'SUM(lote_entrada.peso_bruto_kg) AS entradas_mes',
                'SUM(lote_salida.peso_neto_kg) AS salidas_mes'
            ])
            ->join('lote_salida', 'lote_salida.lote_entrada_id = lote_entrada.id', 'left')
            ->where('lote_entrada.fecha_entrada >=', $inicioMes)
            ->where('lote_entrada.fecha_entrada <=', $finMes)
            ->first() ?? ['entradas_mes' => 0, 'salidas_mes' => 0];
    }
}
