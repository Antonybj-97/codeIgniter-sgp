<?php

namespace App\Models;

use CodeIgniter\Model;

class CentroModel extends Model
{
    protected $table            = 'centro';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'nombre',
        'ubicacion',
        'descripcion',
        'is_active'
    ];

    // Fechas
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validación
    protected $validationRules = [
        'nombre'     => 'required|min_length[3]|max_length[100]',
        'ubicacion'  => 'permit_empty|max_length[255]',
        'descripcion'=> 'permit_empty|max_length[500]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre del centro es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     *  Obtener centros activos (no eliminados)
     */
    public function getActivos()
    {
        return $this->where('deleted_at', null)->findAll();
    }

    /**
     *  Obtener centros eliminados (soft delete)
     */
    public function getEliminados()
    {
        return $this->where('deleted_at !=', null)->findAll();
    }

    /**
     * Obtener centro activo por ID
     */
    public function getActivo($id)
    {
        return $this->where('deleted_at', null)->find($id);
    }
}
