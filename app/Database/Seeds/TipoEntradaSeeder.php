<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TipoEntradaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre'              => 'Compra Local',
                'descripcion'         => 'Entrada de pimienta adquirida a proveedores locales',
                'factor_transformacion'=> 1.0,
                'is_active'           => 1,
                'fecha_creacion'      => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'              => 'Donación',
                'descripcion'         => 'Pimienta recibida como donación',
                'factor_transformacion'=> 1.0,
                'is_active'           => 1,
                'fecha_creacion'      => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'              => 'Importación',
                'descripcion'         => 'Entrada de pimienta importada del extranjero',
                'factor_transformacion'=> 1.1,
                'is_active'           => 1,
                'fecha_creacion'      => date('Y-m-d H:i:s'),
            ],
        ];

        // Insertar todos los registros
        $this->db->table('tipo_entrada')->insertBatch($data);
    }
}
