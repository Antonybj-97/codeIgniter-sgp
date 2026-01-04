<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCierreCuenta extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'centro' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],

            'fecha' => [
                'type' => 'DATE',
                'null' => false,
            ],

            'acopiador' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],

            'cosecha' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => false,
                'default'    => '2025',
            ],

            'folio' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],

            // Resumen financiero
            'dinero_entregado' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],

            'otros_cargos' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],

            'dinero_comprobado' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],

            'saldo_acopiador' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],

            // Totales calculados
            'importe_total_pimienta' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],

            'total_comisiones' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],

            'total_a_pagar' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],

            'saldo_final' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],

            // Rendimientos
            'rendimiento_beneficio' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
                'default'    => 0,
                'comment'    => 'Rendimiento en porcentaje',
            ],

            'rendimiento_centro' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
                'default'    => 0,
                'comment'    => 'Rendimiento en porcentaje',
            ],

            'rendimiento_general' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
                'default'    => 0,
                'comment'    => 'Rendimiento en porcentaje',
            ],

            // Observaciones
            'obs_acopio_verde' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'obs_acopio_seca' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            // Firmas
            'firmo_elaboro' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],

            'firmo_autorizo' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],

            'firmo_acopiador' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],

            // Tablas dinámicas en JSON
            'pimienta' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Datos de pimienta por tipo (con-rama, verde, seca)',
            ],

            'almacen' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Datos de entregas en almacén',
            ],

            'pagos' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Datos de pagos por tipo de pimienta',
            ],

            'comisiones' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Datos de comisiones aplicadas',
            ],

            // Timestamps automáticos
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],

            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],

            'deleted_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('folio'); // Índice para búsquedas por folio
        $this->forge->addKey('fecha'); // Índice para búsquedas por fecha
        $this->forge->addKey('acopiador'); // Índice para búsquedas por acopiador
        $this->forge->addKey('centro'); // Índice para búsquedas por centro
        
        $this->forge->createTable('cierres_cuenta', true);
    }

    public function down()
    {
        $this->forge->dropTable('cierres_cuenta', true);
    }
}