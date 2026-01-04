<?php

use CodeIgniter\Boot;
use Config\Paths;

// --------------------------------------------------------------------
// CHECK PHP VERSION
// --------------------------------------------------------------------
$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    header('HTTP/1.1 503 Service Unavailable', true, 503);
    exit(sprintf(
        'Your PHP version must be %s or higher. Current: %s',
        $minPhpVersion,
        PHP_VERSION
    ));
}

// --------------------------------------------------------------------
// SET THE FRONT CONTROLLER PATH
// --------------------------------------------------------------------
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// --------------------------------------------------------------------
// LOAD PATHS CONFIG
// --------------------------------------------------------------------
// Asegúrate de que app/ y system/ estén al mismo nivel que public/
require FCPATH . '../app/Config/Paths.php';
$paths = new \Config\Paths();

// --------------------------------------------------------------------
// LOAD THE FRAMEWORK BOOTSTRAP
// --------------------------------------------------------------------
require $paths->systemDirectory . '/Boot.php';

// --------------------------------------------------------------------
// START APPLICATION
// --------------------------------------------------------------------
exit(Boot::bootWeb($paths));
