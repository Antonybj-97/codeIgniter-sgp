<?php
// Script de prueba para verificar la conexión a la base de datos
echo "<h1>Prueba de Conexión a Base de Datos</h1>";

// Cargar el entorno de CodeIgniter
require_once dirname(__DIR__) . '/app/Config/Paths.php';
$paths = new Config\Paths();
require_once FCPATH . '../app/Config/Bootstrap.php';

// Intentar conectar a la base de datos
try {
    $db = \Config\Database::connect();
    $query = $db->query("SELECT 1 as test");
    $row = $query->getRow();
    
    if ($row && $row->test == 1) {
        echo "<div style='color: green; font-weight: bold;'>✓ Conexión a la base de datos exitosa</div>";
    } else {
        echo "<div style='color: red; font-weight: bold;'>✗ Error en la consulta de prueba</div>";
    }
    
    // Probar consultas a tablas principales
    $tables = ['proceso_pimienta', 'lote_entrada', 'lote_salida', 'tipo_pimienta', 'users'];
    echo "<h2>Verificando tablas:</h2>";
    echo "<ul>";
    
    foreach ($tables as $table) {
        try {
            $query = $db->query("SELECT COUNT(*) as total FROM $table");
            $row = $query->getRow();
            echo "<li style='color: green;'>✓ Tabla <strong>$table</strong>: {$row->total} registros</li>";
        } catch (\Exception $e) {
            echo "<li style='color: red;'>✗ Error en tabla <strong>$table</strong>: " . $e->getMessage() . "</li>";
        }
    }
    
    echo "</ul>";
    
} catch (\Exception $e) {
    echo "<div style='color: red; font-weight: bold;'>✗ Error de conexión: " . $e->getMessage() . "</div>";
}

// Verificar configuración de entorno
echo "<h2>Configuración de entorno:</h2>";
echo "<ul>";
echo "<li>ENVIRONMENT: " . ENVIRONMENT . "</li>";
echo "<li>Base URL: " . base_url() . "</li>";
echo "<li>Site URL: " . site_url() . "</li>";
echo "</ul>";

// Verificar rutas
echo "<h2>Verificación de rutas:</h2>";
echo "<ul>";
$routes = [
    'procesos' => 'Lista de procesos',
    'lotes-entrada' => 'Lista de lotes de entrada',
    'lotes-salida' => 'Lista de lotes de salida'
];

foreach ($routes as $route => $description) {
    echo "<li><a href='" . site_url($route) . "' target='_blank'>$description</a></li>";
}
echo "</ul>";

echo "<p><a href='" . site_url() . "'>Volver al inicio</a></p>";
?>