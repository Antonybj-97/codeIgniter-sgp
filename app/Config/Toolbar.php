<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Debug\Toolbar\Collectors\Database;
use CodeIgniter\Debug\Toolbar\Collectors\Events;
use CodeIgniter\Debug\Toolbar\Collectors\Files;
use CodeIgniter\Debug\Toolbar\Collectors\Logs;
use CodeIgniter\Debug\Toolbar\Collectors\Routes;
use CodeIgniter\Debug\Toolbar\Collectors\Timers;
use CodeIgniter\Debug\Toolbar\Collectors\Views;

class Toolbar extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Toolbar Collectors
     * --------------------------------------------------------------------------
     *
     * Solo se activan en desarrollo (ENVIRONMENT === 'development').
     * En producción, se desactiva completamente.
     */
    public array $collectors = [];

    public function __construct()
    {
        if (ENVIRONMENT === 'development' && CI_DEBUG) {
            $this->collectors = [
                Timers::class,
                Database::class,
                Logs::class,
                Views::class,
                Files::class,
                Routes::class,
                Events::class,
            ];
        }
    }

    /**
     * --------------------------------------------------------------------------
     * Collect Var Data
     * --------------------------------------------------------------------------
     *
     * Desactivado en producción para reducir memoria y requests.
     */
    public bool $collectVarData = false;

    /**
     * --------------------------------------------------------------------------
     * Max History
     * --------------------------------------------------------------------------
     *
     * Número de requests guardados. 0 evita requests adicionales.
     */
    public int $maxHistory = 0;

    /**
     * --------------------------------------------------------------------------
     * Toolbar Views Path
     * --------------------------------------------------------------------------
     */
    public string $viewsPath = SYSTEMPATH . 'Debug/Toolbar/Views/';

    /**
     * --------------------------------------------------------------------------
     * Max Queries
     * --------------------------------------------------------------------------
     */
    public int $maxQueries = 100;

    /**
     * --------------------------------------------------------------------------
     * Watched Directories
     * --------------------------------------------------------------------------
     */
    public array $watchedDirectories = [
        'app',
    ];

    /**
     * --------------------------------------------------------------------------
     * Watched File Extensions
     * --------------------------------------------------------------------------
     */
    public array $watchedExtensions = [
        'php', 'css', 'js', 'html', 'svg', 'json', 'env',
    ];
}
