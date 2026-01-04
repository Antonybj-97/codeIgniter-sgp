<?php

use CodeIgniter\Boot;
use Config\Paths;

$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    header('HTTP/1.1 503 Service Unavailable', true, 503);
    exit(sprintf(
        'Your PHP version must be %s or higher. Current: %s',
        $minPhpVersion,
        PHP_VERSION
    ));
}

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Carga Paths.php desde app/Config
require FCPATH . '../app/Config/Paths.php';
$paths = new \Config\Paths();

// Carga el núcleo de CI4
require $paths->systemDirectory . '/Boot.php';

// Inicia la aplicación
exit(Boot::bootWeb($paths));
