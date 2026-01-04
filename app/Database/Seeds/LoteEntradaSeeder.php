<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LoteEntradaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'centro_id'        => 1, // Ajusta según tu tabla centros
                'tipo_pimienta_id' => 1, // Pimienta Negra
                'tipo_entrada_id'  => 1, // Compra Local
                'usuario_id'       => 1, // Usuario administrador
                'fecha_entrada'    => date('Y-m-d'),
                'proveedor'        => 'Proveedor Juan',
                'peso_bruto_kg'    => 50.25,
                'precio_compra'    => 120.50,
                'costo_total'      => 50.25 * 120.50,
                'observaciones'    => 'Primera entrada de pimienta negra',
                'estado'           => 'Registrado',
                'fecha_creacion'   => date('Y-m-d H:i:s'),
                'fecha_actualizacion'=> date('Y-m-d H:i:s'),
            ],
            [
                'centro_id'        => 1,
                'tipo_pimienta_id' => 2, // Pimienta Blanca
                'tipo_entrada_id'  => 2, // Donación
                'usuario_id'       => 1,
                'fecha_entrada'    => date('Y-m-d'),
                'proveedor'        => 'Proveedor María',
                'peso_bruto_kg'    => 30.00,
                'precio_compra'    => 0.00,
                'costo_total'      => 0.00,
                'observaciones'    => 'Donación especial de pimienta blanca',
                'estado'           => 'Registrado',
                'updated_at'   => date('Y-m-d H:i:s'),
                'created_at'=> date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('lote_entrada')->insertBatch($data);

    }
}
