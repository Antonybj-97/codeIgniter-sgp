<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TipoPimientaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre'       => 'Pimienta Negra',
                'descripcion'  => 'Pimienta en grano seca',
                'precio_base'  => 120.50,
                'is_active'    => 1,
                'fecha_creacion'=> date('Y-m-d H:i:s'),
            ],
            [
                'nombre'       => 'Pimienta Blanca',
                'descripcion'  => 'Pimienta descascarillada',
                'precio_base'  => 150.00,
                'is_active'    => 1,
                'fecha_creacion'=> date('Y-m-d H:i:s'),
            ],
            [
                'nombre'       => 'Pimienta Verde',
                'descripcion'  => 'Pimienta fresca en salmuera',
                'precio_base'  => 90.00,
                'is_active'    => 1,
                'fecha_creacion'=> date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('tipo_pimienta')->insertBatch($data);
    }
}
