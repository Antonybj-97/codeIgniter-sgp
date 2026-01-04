<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// =====================================================
// FUNCIÓN AUXILIAR PARA CRUD
// =====================================================
if (! function_exists('addCrudRoutes')) {
    function addCrudRoutes(RouteCollection $routes, string $prefix, string $controller)
    {
        $base = trim($prefix, '/');
        $base = $base ? "$base/" : '';

        $routes->get("{$base}", "{$controller}::index");
        $routes->get("{$base}create", "{$controller}::create");
        $routes->post("{$base}store", "{$controller}::store");
        $routes->get("{$base}show/(:num)", "{$controller}::show/$1");
        $routes->get("{$base}edit/(:num)", "{$controller}::edit/$1");
        $routes->post("{$base}update/(:num)", "{$controller}::update/$1");
        $routes->post("{$base}delete/(:num)", "{$controller}::delete/$1");
    }
}

// =====================================================
// RUTA RAÍZ - REDIRIGE AUTOMÁTICAMENTE A LOGIN
// =====================================================
$routes->get('/', 'Home::index'); // ← Esto redirigirá a /login

// =====================================================
// RUTAS PÚBLICAS (accesibles sin login)
// =====================================================
$routes->get('login', 'Auth::login');
$routes->get('reglamento', 'Home::reglamento');
$routes->get('servicios', 'Home::servicios');
$routes->get('acercade', 'Home::acercade');

// =====================================================
// AUTENTICACIÓN
// =====================================================
$routes->group('', ['namespace' => 'App\Controllers'], static function ($routes) {
    // Rutas solo para usuarios NO autenticados
    $routes->group('', ['filter' => 'noauth'], static function ($routes) {
        $routes->post('login', 'Auth::loginProcess');
    });
    
    // Rutas solo para usuarios autenticados
    $routes->group('', ['filter' => 'auth'], static function ($routes) {
        $routes->get('logout', 'Auth::logout');
        $routes->get('dashboard', 'Home::dashboard'); // ← Ahora está protegida por 'auth'
        $routes->get('perfil', 'Auth::perfil');
    });
});

// =====================================================
// MÓDULO USUARIO / GESTIÓN PERSONAL
// =====================================================
$routes->group('usuario', [
    'namespace' => 'App\Controllers\Usuario',
    'filter'    => 'auth' 
], static function ($routes) {
    $routes->get('dashboard', 'UserController::dashboard');
    $routes->get('dashboard-ajax', 'UserController::dashboardAjax');
    
    // Rutas solo para administradores
    $routes->group('', ['filter' => 'auth:admin'], static function ($routes) {
        addCrudRoutes($routes, '', 'UserController');
    });
});

// =====================================================
// LOTES DE ENTRADA (AUTENTICADOS)
// =====================================================
$routes->group('lotes-entrada', ['namespace' => 'App\Controllers', 'filter' => 'auth'], static function ($routes) {
    addCrudRoutes($routes, '', 'LoteEntradaController');
    
    // Rutas adicionales específicas
    $routes->get('apiEntradas', 'LoteEntradaController::apiEntradas');
    $routes->get('peso-disponible/(:num)', 'LoteEntradaController::pesoDisponible/$1');
    $routes->get('lotePDF/(:num)', 'LoteEntradaController::lotePDF/$1');
    $routes->get('pdfFolio', 'LoteEntradaController::pdfFolio');
    $routes->get('pdfFolio/(:any)', 'LoteEntradaController::pdfFolio/$1');
});

// =====================================================
// MÓDULOS EXCLUSIVOS PARA ADMINISTRADOR
// =====================================================
$adminConfig = ['namespace' => 'App\Controllers', 'filter' => 'auth:admin'];

// LOTES DE SALIDA
$routes->group('lotes-salida', $adminConfig, static function ($routes) {
    addCrudRoutes($routes, '', 'LoteSalidaController');
    
    // Rutas adicionales específicas
    $routes->get('crear-desde-proceso/(:num)', 'LoteSalidaController::crearDesdeProceso/$1');
    $routes->get('api-salidas', 'LoteSalidaController::apiSalidas');
    $routes->get('lotePDF/(:num)', 'LoteSalidaController::lotePDF/$1');
    $routes->get('exportar/pdf', 'LoteSalidaController::exportarPdf');
});

// --- REPORTES GLOBALES ---
$routes->group('reportes', $adminConfig, static function ($routes) {
    $routes->get('/', 'ReporteController::index');
    $routes->get('acopio-pimienta', 'ReporteController::acopio');
    $routes->post('acopio_pdf', 'ReporteController::acopioPdf');
    
    // Exportación con filtros (parámetros opcionales)
    $routes->get('entradasPdf/(:any)?/(:any)?/(:any)?', 'ReporteController::entradasPdf/$1/$2/$3');
    $routes->get('entradasExcel/(:any)?/(:any)?/(:any)?', 'ReporteController::entradasExcel/$1/$2/$3');
    
    // Alias para compatibilidad
    $routes->get('entradas-pdf/(:any)?/(:any)?/(:any)?', 'ReporteController::entradasPdf/$1/$2/$3');
});

// --- DASHBOARD ADMIN ---
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth:admin'], static function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('dashboard-ajax', 'AdminController::dashboardAjax');
    $routes->get('dashboard-inventario-ajax', 'AdminController::dashboardInventarioAjax');
    
    // AJAX: Inventario combinado Tipo + Centro
    $routes->get('dashboard-inventario-combinado-ajax', 'AdminController::dashboardInventarioCombinadoAjax');

    // AJAX: Proporción anual Entradas/Salidas
    $routes->get('dashboard-proporcion-anual-ajax', 'AdminController::dashboardProporcionAnualAjax');
});

// --- PROCESOS ---
$routes->group('procesos', $adminConfig, static function ($routes) {
    $routes->get('/', 'ProcesoController::index');
    $routes->post('ajaxList', 'ProcesoController::ajaxList');
    $routes->get('create', 'ProcesoController::create');
    $routes->post('store', 'ProcesoController::store');
    $routes->get('iniciar/(:num)', 'ProcesoController::iniciar/$1');
    $routes->get('finalizar/(:num)', 'ProcesoController::finalizar/$1');
    $routes->get('pdf/(:num)', 'ProcesoController::pdf/$1');
});

// --- MÓDULOS CRUD BÁSICOS ---
$routes->group('centros', $adminConfig, static function ($routes) { 
    addCrudRoutes($routes, '', 'CentroController'); 
});

$routes->group('tipos-pimienta', $adminConfig, static function ($routes) { 
    addCrudRoutes($routes, '', 'TipoPimientaController'); 
});

// Tipos de entrada - AÑADIR SI FALTA
$routes->group('tipos-entrada', $adminConfig, static function ($routes) { 
    addCrudRoutes($routes, '', 'TipoEntradaController'); 
});

// --- CIERRE DE CUENTAS ---
$routes->group('cierre', $adminConfig, static function ($routes) {
    $routes->get('/', 'CierreCuentaController::index');
    $routes->post('guardar', 'CierreCuentaController::guardar');
    $routes->post('generar-pdf', 'CierreCuentaController::generarPDF');
});

// =====================================================
// PROCESOS DE PIMIENTA
// =====================================================
$routes->group('procesos', ['namespace' => 'App\Controllers', 'filter' => 'auth'], static function ($routes) {

    // Rutas para procesos masivos
    $routes->put('actualizarMasivo/(:num)', 'ProcesoController::updateMasivo/$1');
    
    // Listado principal y tabla AJAX
    $routes->get('/', 'ProcesoController::index');
    $routes->post('ajaxList', 'ProcesoController::ajaxList');

    // Creación y gestión de procesos masivos
    $routes->get('crearMasivo', 'ProcesoController::crearMasivo');
    $routes->post('storeMasivo', 'ProcesoController::storeMasivo');
    $routes->post('iniciarMasivo', 'ProcesoController::iniciarMasivo');

    // Edición y actualización individual
    $routes->get('edit/(:num)', 'ProcesoController::edit/$1');
    $routes->post('update/(:num)', 'ProcesoController::update/$1');

    // Control de estado de proceso
    $routes->get('iniciar/(:num)', 'ProcesoController::iniciar/$1');
    $routes->get('finalizar/(:num)', 'ProcesoController::finalizar/$1');

    // Historial de cambios y detalles
    $routes->get('historial/(:num)', 'ProcesoController::historial/$1');
    $routes->get('detalles/(:num)', 'ProcesoController::detalles/$1');
    $routes->post('agregarDetalle', 'ProcesoController::agregarDetalle');

    // Reportes en PDF
    $routes->group('pdf', static function($routes) {
        $routes->get('(:num)', 'ProcesoController::pdf/$1');            // PDF individual (descargar)
        $routes->get('(:num)/(:any)', 'ProcesoController::pdf/$1/$2');  // PDF individual (modo especial)
    });

    $routes->get('exportarPDF', 'ProcesoController::exportarPDF');     // PDF general

    // API de procesos finalizados
    $routes->get('api-finalizados', 'ProcesoController::apiFinalizados');
});