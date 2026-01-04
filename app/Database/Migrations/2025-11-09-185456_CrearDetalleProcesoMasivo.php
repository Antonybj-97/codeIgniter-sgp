<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearTablaDetalleProceso extends Migration
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
            'proceso_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'lote_entrada_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'tipo_proceso' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'peso_parcial_kg' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'peso_estimado_kg' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'Pendiente',
            ],
            'fecha_registro' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Claves primarias y foráneas
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('proceso_id', 'procesos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lote_entrada_id', 'lote_entrada', 'id', 'RESTRICT', 'CASCADE');

        $this->forge->createTable('detalle_proceso');
    }

    public function down()
    {
        // Eliminar claves foráneas antes de borrar la tabla (evita errores en rollback)
        $this->db->query('ALTER TABLE detalle_proceso DROP FOREIGN KEY detalle_proceso_proceso_id_foreign');
        $this->db->query('ALTER TABLE detalle_proceso DROP FOREIGN KEY detalle_proceso_lote_entrada_id_foreign');

        // Eliminar la tabla
        $this->forge->dropTable('detalle_proceso', true);
    }
}
