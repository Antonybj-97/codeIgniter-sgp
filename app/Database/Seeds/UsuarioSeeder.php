<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        // Admin
        $userModel->insert([
            'rol'                   => 'admin', // 👈 Se agrega el rol
            'username'                  => 'Administrador',
            'email'                 => 'admin@demo.com',
            'nombre_completo'              => 'admin',
            'password'              => $this->setPassword('admin123'),
            'reset_token'           => null,
            'reset_token_expires'   => null,
            'active'                => 1,
            'created_at'            => date('Y-m-d H:i:s'),
            'updated_at'            => date('Y-m-d H:i:s'),
            'deleted_at'            => null,
        ]);

        // Usuario normal
        $userModel->insert([
            'rol'                   => 'Usuario', // 👈 Se agrega el rol
            'name'                  => 'Usuario Normal',
            'email'                 => 'user@demo.com',
            'username'              => 'usuario',
            'password'              => $this->setPassword('123456'),
            'reset_token'           => null,
            'reset_token_expires'   => null,
            'active'                => 1,
            'created_at'            => date('Y-m-d H:i:s'),
            'updated_at'            => date('Y-m-d H:i:s'),
            'deleted_at'            => null,
        ]);
    }

    private function setPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
