<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'nombre_completo',
        'username',
        'email',
        'password',
        'rol',
        'active',
        'reset_token',
        'reset_expires'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    /**
     * Buscar usuario por username o email
     */
    public function findByLogin(string $login)
    {
        return $this->groupStart()
                        ->where('username', $login)
                        ->orWhere('email', $login)
                    ->groupEnd()
                    ->first();
    }
}
