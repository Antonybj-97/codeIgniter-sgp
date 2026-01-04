<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CentroSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre'      => 'Centro Principal',
                'ubicacion'   => 'Ciudad de México',
                'descripcion' => 'Centro principal de recepción de pimienta',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'      => 'Centro Secundario',
                'ubicacion'   => 'Guadalajara',
                'descripcion' => 'Centro secundario de recepción',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'      => 'Centro Norte',
                'ubicacion'   => 'Monterrey',
                'descripcion' => 'Centro regional norte',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        // Inserta los datos en la tabla 'centro'
        $this->db->table('centro')->insertBatch($data);
    }
}
