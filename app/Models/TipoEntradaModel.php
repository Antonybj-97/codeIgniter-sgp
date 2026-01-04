<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoEntradaModel extends Model
{
    protected $table      = 'tipo_entrada'; // nombre real en la BD
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nombre',
        'descripcion',
        'factor_transformacion',
        'is_active',
        'fecha_creacion'
    ];

    // 🚫 No usar timestamps automáticos
    protected $useTimestamps = false;

    // Validación (puedes personalizar si quieres)
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
