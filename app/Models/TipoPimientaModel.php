<?php 
namespace App\Models;

use CodeIgniter\Model;

class TipoPimientaModel extends Model
{
    protected $table            = 'tipo_pimienta';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;  // ✅ Activa soft deletes
    protected $deletedField     = 'deleted_at'; // ✅ Campo de soft delete
    protected $protectFields    = true;

    protected $allowedFields = [
        'nombre',
        'descripcion',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------------
    // Eventos para limpiar cache
    // -------------------------
    protected $afterInsert  = ['clearCache'];
    protected $afterUpdate  = ['clearCache'];
    protected $afterDelete  = ['clearCache'];

    // Callback interno para limpiar cache
    protected function clearCache(array $data)
    {
        \Config\Services::cache()->delete('total_tipos');
        return $data;
    }
}
