<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class EntradaModel extends Model
{
    protected $table            = 'entrada';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'fecha_entrada',
        'centro_id',
        'tipo_entrada_id',
        'proveedor',
        'observaciones',
        'usuario_id'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $dateFormat    = 'datetime';

    /**
     * Crea una entrada y sus lotes asociados en una sola transacción.
     *
     * @param array $data
     *      [
     *          'fecha_entrada' => 'YYYY-MM-DD',
     *          'centro_id' => int,
     *          'tipo_entrada_id' => int,
     *          'proveedor' => string|null,
     *          'observaciones' => string|null,
     *          'usuario_id' => int,
     *          'lotes' => [
     *              ['tipo_pimienta_id'=>int, 'peso_bruto_kg'=>float, 'precio_compra'=>float, 'observaciones'=>string|null],
     *              ...
     *          ]
     *      ]
     * @return int ID de la entrada creada
     * @throws DatabaseException
     */
    public function createWithLotes(array $data): int
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Insertar entrada principal
            $entradaId = $this->insert([
                'fecha_entrada'   => $data['fecha_entrada'] ?? date('Y-m-d'),
                'centro_id'       => $data['centro_id'],
                'tipo_entrada_id' => $data['tipo_entrada_id'],
                'proveedor'       => $data['proveedor'] ?? null,
                'observaciones'   => $data['observaciones'] ?? null,
                'usuario_id'      => $data['usuario_id'] ?? 1
            ]);

            if (!$entradaId) {
                throw new DatabaseException('No se pudo crear la entrada principal.');
            }

            // Insertar lotes asociados
            $loteModel = new LoteEntradaModel();
            foreach ($data['lotes'] as $lote) {
                $loteData = [
                    'entrada_id'       => $entradaId,
                    'tipo_pimienta_id' => $lote['tipo_pimienta_id'],
                    'peso_bruto_kg'    => $lote['peso_bruto_kg'] ?? 0,
                    'precio_compra'    => $lote['precio_compra'] ?? 0,
                    'costo_total'      => ($lote['peso_bruto_kg'] ?? 0) * ($lote['precio_compra'] ?? 0),
                    'observaciones'    => $lote['observaciones'] ?? null,
                    'estado'           => 'Recibido',
                    'usuario_id'       => $data['usuario_id'] ?? 1,
                ];
                $loteModel->insert($loteData);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new DatabaseException('Error al guardar los lotes asociados.');
            }

            return $entradaId;
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', '[ERROR] EntradaModel::createWithLotes -> ' . $e->getMessage());
            throw $e;
        }
    }
}
