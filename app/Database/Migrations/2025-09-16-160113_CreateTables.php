<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTables extends Migration
{
    public function up()
    {
        // -------------------
        // Tabla: users
        // -------------------
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'nombre_completo' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'rol' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'user'],
            'active' => ['type' => 'TINYINT', 'default' => 1],
            'reset_token' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'reset_expires' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
            
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);

        // -------------------
        // Tabla: centro
        // -------------------
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre' => ['type' => 'VARCHAR', 'constraint' => 100],
            'ubicacion' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'descripcion' => ['type' => 'TEXT', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('centro', true);

        // -------------------
        // Tabla: tipo_pimienta
        // -------------------
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre' => ['type' => 'VARCHAR', 'constraint' => 50],
            'descripcion' => ['type' => 'TEXT', 'null' => true],
            'precio_base' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tipo_pimienta', true);

        // -------------------
        // Tabla: tipo_entrada
        // -------------------
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre' => ['type' => 'VARCHAR', 'constraint' => 50],
            'descripcion' => ['type' => 'TEXT', 'null' => true],
            'factor_transformacion' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 1],
            'is_active' => ['type' => 'TINYINT', 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tipo_entrada', true);

        // -------------------
        // Tabla: entrada
        // -------------------
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'fecha_entrada' => ['type' => 'DATE', 'null' => true],
            'centro_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'tipo_entrada_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'proveedor' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'observaciones' => ['type' => 'TEXT', 'null' => true],
            'usuario_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('centro_id', 'centro', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tipo_entrada_id', 'tipo_entrada', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('entrada', true);

        // -------------------
        // Tabla: lote_entrada
        // -------------------
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'entrada_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'tipo_pimienta_id' => ['type' => 'INT', 'unsigned' => true],
            'usuario_id' => ['type' => 'INT', 'unsigned' => true],
            'fecha_entrada' => ['type' => 'DATETIME', 'null' => false],
            'proveedor' => ['type' => 'VARCHAR', 'constraint' => 200],
            'peso_bruto_kg' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'precio_compra' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'costo_total' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'observaciones' => ['type' => 'TEXT', 'null' => true],
            'estado' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Recibido'],
            'peso_usado_kg' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('entrada_id', 'entrada', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tipo_pimienta_id', 'tipo_pimienta', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lote_entrada', true);

        // -------------------
        // Tabla: procesos
        // -------------------
        $this->forge->addField([
            'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'lote_entrada_id' => ['type'=>'INT','unsigned'=>true],
            'tipo_proceso' => ['type'=>'VARCHAR','constraint'=>100],
            'observacion_proceso'=> ['type'=>'TEXT','null'=>true],
            'proveedor' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'peso_bruto_kg' => ['type'=>'DECIMAL','constraint'=>'10,2','default'=>0],
            'peso_estimado_kg' => ['type'=>'DECIMAL','constraint'=>'10,2','default'=>0],
            'estado_proceso' => ['type'=>'VARCHAR','constraint'=>50,'default'=>'Pendiente'],
            'fecha_proceso' => ['type'=>'DATETIME','null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('lote_entrada_id','lote_entrada','id','CASCADE','CASCADE');
        $this->forge->createTable('procesos', true);

        // -------------------
        // Tabla: lote_salida
        // -------------------
        $this->forge->addField([
            // Clave Primaria y Folio
            'id_salida' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'folio_salida' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => false, 'unique' => true],

            // Relación con el Proceso (Materia Prima)
            // Se asume que el lote de salida está vinculado a un registro en la tabla 'procesos'
            'proceso_id' => ['type' => 'INT', 'unsigned' => true, 'null' => false], 

            // Información de la Salida
            'fecha_embarque' => ['type' => 'DATE', 'null' => false],
            'nombre_cliente' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            
            // Detalle del Producto
            'producto' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => false], // e.g., Pimienta gorda convencional
            'tipo_producto' => ['type' => 'ENUM', 'constraint' => ['Orgánico', 'Convencional'], 'null' => false],
            'unidad' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false], // e.g., kg
            'cantidad' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false], // Peso neto en kg

            // Información de Trazabilidad y Documentación
            'no_maquila' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'no_factura' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'certificado' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'clave_lote' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'dotos_transporte' => ['type' => 'VARCHAR', 'constraint' => 180, 'null' => true],
            'atoriza_salida' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'recibe_producto' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            // Campos de Auditoría
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        
        $this->forge->addKey('id_salida', true);
        // Clave Foránea al proceso del que proviene el material
        $this->forge->addForeignKey('proceso_id', 'procesos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lote_salida', true);
    }
    

    public function down()
    {
        $this->forge->dropTable('procesos', true);
        $this->forge->dropTable('lote_entrada', true);
        $this->forge->dropTable('entrada', true);
        $this->forge->dropTable('tipo_entrada', true);
        $this->forge->dropTable('tipo_pimienta', true);
        $this->forge->dropTable('lote_salida', true);
        $this->forge->dropTable('centro', true);
        $this->forge->dropTable('users', true);
    }
}
