<?php

namespace App\Models;

use CodeIgniter\Model;

class ReporteEntradasModel extends Model
{
    protected $table = 'lote_entrada';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'tipo_pimienta_id',
        'peso_bruto_kg',
        'precio_compra',
        'costo_total',
        'proveedor',
        'fecha_entrada',
        'centro_id',
        'tipo_entrada_id',
        'users_id',
        'observaciones'
    ];

    /**
     * Obtiene las entradas de lote con su peso vendido y datos del usuario
     *
     * @param int|null $tipoPimientaId
     * @param string|null $fechaInicio Formato 'YYYY-MM-DD'
     * @param string|null $fechaFin Formato 'YYYY-MM-DD'
     * @return array
     */
    public function getEntradasConSalidas($tipoPimientaId = null, $fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->db->table($this->table . ' le');
        $builder->select('
            le.id,
            le.tipo_pimienta_id,
            le.peso_bruto_kg,
            le.precio_compra,
            le.costo_total,
            le.proveedor,
            le.fecha_entrada,
            le.centro_id,
            le.tipo_entrada_id,
            le.users_id,
            u.username AS usuario,
            le.observaciones,
            IFNULL(SUM(ls.peso_neto_kg),0) as peso_vendido
        ');
        $builder->join('lote_salida ls', 'ls.lote_entrada_id = le.id', 'left');
        $builder->join('users u', 'u.id = le.users_id', 'left');

        if ($tipoPimientaId) {
            $builder->where('le.tipo_pimienta_id', $tipoPimientaId);
        }
        if ($fechaInicio) {
            $builder->where('le.fecha_entrada >=', $fechaInicio);
        }
        if ($fechaFin) {
            $builder->where('le.fecha_entrada <=', $fechaFin);
        }

        $builder->groupBy('le.id');
        $builder->orderBy('le.fecha_entrada', 'DESC');

        return $builder->get()->getResultArray();
    }
}
