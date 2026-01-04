<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo para manejar los detalles de un proceso masivo.
 */
class DetalleProcesoMasivoModel extends Model
{
    protected $table      = 'detalle_proceso';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    // Soft Deletes
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    // Timestamps
    protected $useTimestamps  = true;
    protected $dateFormat     = 'datetime';
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    // Campos permitidos
    protected $allowedFields = [
        'proceso_id', 'lote_entrada_id', 'descripcion', 'tipo_proceso',
        'peso_parcial_kg', 'peso_estimado_kg', 'estado', 'fecha_registro',
        'centro', 'fecha', 'acopiador', 'cosecha', 'dinero_entregado',
        'otros_cargos', 'dinero_comprobado', 'saldo_acopiador',
        'firmo_elaboro', 'firmo_autorizo', 'firmo_acopiador', 'pimienta'
    ];

    // Validaciones
    protected $validationRules = [
        'proceso_id'       => 'required|integer',
        'lote_entrada_id'  => 'permit_empty|integer',
        'descripcion'      => 'permit_empty|string|max_length[500]',
        'tipo_proceso'     => 'required|string|max_length[50]',
        'peso_parcial_kg'  => 'permit_empty|numeric|greater_than_equal_to[0]',
        'peso_estimado_kg' => 'permit_empty|numeric|greater_than_equal_to[0]',
        'estado'           => 'required|in_list[PENDIENTE,EN_EJECUCION,EXITOSO,FALLIDO,CANCELADO]',
        'fecha_registro'   => 'required|valid_date[Y-m-d H:i:s]',
        'centro'           => 'required|string|min_length[1]|max_length[255]',
        'fecha'            => 'required|valid_date[Y-m-d]',
        'acopiador'        => 'required|string|min_length[1]|max_length[255]',
        'cosecha'          => 'required|string|min_length[1]|max_length[255]',
        'dinero_entregado' => 'permit_empty|numeric',
        'otros_cargos'     => 'permit_empty|numeric',
        'dinero_comprobado'=> 'permit_empty|numeric',
        'saldo_acopiador'  => 'permit_empty|numeric',
        'pimienta'         => 'permit_empty'
    ];

    // Hooks
    protected $beforeInsert = ['normalizeData'];
    protected $beforeUpdate = ['normalizeData'];
    protected $afterFind    = ['decodePimienta']; // Decodifica JSON automáticamente

    /**
     * Normaliza datos antes de guardar.
     */
    protected function normalizeData(array $data): array
    {
        if (!isset($data['data'])) {
            return $data;
        }

        $fields = &$data['data'];

        // 1. Normalizar números (asegurar floats positivos)
        $numericFields = [
            'dinero_entregado', 'otros_cargos', 'dinero_comprobado',
            'saldo_acopiador', 'peso_parcial_kg', 'peso_estimado_kg',
        ];

        foreach ($numericFields as $field) {
            if (isset($fields[$field])) {
                $fields[$field] = max(0.0, (float) $fields[$field]);
            }
        }

        // 2. Manejo de campo JSON 'pimienta'
        if (isset($fields['pimienta'])) {
            if (is_array($fields['pimienta'])) {
                $fields['pimienta'] = json_encode($fields['pimienta'], JSON_UNESCAPED_UNICODE);
            } elseif (is_string($fields['pimienta'])) {
                json_decode($fields['pimienta']);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $fields['pimienta'] = json_encode($this->getDefaultPimientaStructure());
                }
            }
        } else {
            // Estructura default en inserts si no viene el campo
            if ($data['method'] === 'insert') {
                $fields['pimienta'] = json_encode($this->getDefaultPimientaStructure());
            }
        }

        return $data;
    }

    /**
     * Hook para convertir el string JSON de la BD en array de PHP automáticamente.
     */
    protected function decodePimienta(array $data): array
    {
        if (empty($data['data'])) return $data;

        // Si es una sola fila (find)
        if (isset($data['data']['pimienta'])) {
            $data['data']['pimienta_array'] = json_decode($data['data']['pimienta'], true) ?: $this->getDefaultPimientaStructure();
        } 
        // Si son múltiples filas (findAll)
        else {
            foreach ($data['data'] as &$row) {
                if (isset($row['pimienta'])) {
                    $row['pimienta_array'] = json_decode($row['pimienta'], true) ?: $this->getDefaultPimientaStructure();
                }
            }
        }

        return $data;
    }

    protected function getDefaultPimientaStructure(): array
    {
        return [
            'con-rama' => ['precio' => 0, 'kilos' => 0, 'importe' => 0],
            'verde'    => ['precio' => 0, 'kilos' => 0, 'importe' => 0],
            'seca'     => ['precio' => 0, 'kilos' => 0, 'importe' => 0],
        ];
    }

    /**
     * Búsqueda con filtros dinámicos.
     */
    public function search(array $filters = [], int $limit = 100, int $offset = 0): array
    {
        if (!empty($filters['centro'])) {
            $this->like('centro', $filters['centro']);
        }

        if (!empty($filters['acopiador'])) {
            $this->like('acopiador', $filters['acopiador']);
        }

        if (!empty($filters['proceso_id'])) {
            $this->where('proceso_id', $filters['proceso_id']);
        }

        if (!empty($filters['estado'])) {
            $this->where('estado', $filters['estado']);
        }

        if (!empty($filters['fecha_from'])) {
            $this->where('fecha >=', $filters['fecha_from']);
        }

        if (!empty($filters['fecha_to'])) {
            $this->where('fecha <=', $filters['fecha_to']);
        }

        // findAll() activará automáticamente el hook afterFind (decodePimienta)
        return $this->orderBy('created_at', 'DESC')
                    ->findAll($limit, $offset);
    }
}