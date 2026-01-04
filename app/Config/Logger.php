<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Log\Handlers\FileHandler;
use CodeIgniter\Log\Handlers\HandlerInterface;

class Logger extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Error Logging Threshold
     * --------------------------------------------------------------------------
     *
     * Determina qué nivel de mensajes se registrarán en los logs.
     *
     * - 0 = Logging deshabilitado
     * - 1 = Solo Emergencia
     * - 2 = Alertas
     * - 3 = Críticos
     * - 4 = Errores de runtime
     * - 5 = Warnings
     * - 6 = Notices
     * - 7 = Info
     * - 8 = Debug
     * - 9 = Todos los mensajes
     *
     * Configuración recomendada:
     * - Producción: solo errores importantes
     * - Desarrollo: todo para depuración
     */
    public $threshold = (ENVIRONMENT === 'production') ? 3 : 9;

    /**
     * --------------------------------------------------------------------------
     * Formato de fecha para los logs
     * --------------------------------------------------------------------------
     */
    public string $dateFormat = 'Y-m-d H:i:s';

    /**
     * --------------------------------------------------------------------------
     * Log Handlers
     * --------------------------------------------------------------------------
     */
    public array $handlers = [
        /*
         * --------------------------------------------------------------------
         * File Handler
         * --------------------------------------------------------------------
         * Guarda los logs en archivos en WRITEPATH/logs/
         */
        FileHandler::class => [
            'handles' => [
                'critical',
                'alert',
                'emergency',
                'error',
                // En producción puedes quitar info, notice, warning y debug
                'warning',
                'notice',
                'info',
                'debug',
            ],
            'fileExtension'   => '', // vacío = extensión por defecto .log
            'filePermissions' => 0644,
            'path'            => '', // se usa WRITEPATH/logs/
        ],

        /*
         * --------------------------------------------------------------------
         * ChromeLoggerHandler
         * --------------------------------------------------------------------
         * Para desarrollo con extensión Chrome Logger
         */
        // 'CodeIgniter\Log\Handlers\ChromeLoggerHandler' => [
        //     'handles' => ['critical','alert','emergency','debug','error','info','notice','warning'],
        // ],

        /*
         * --------------------------------------------------------------------
         * ErrorlogHandler
         * --------------------------------------------------------------------
         * Envía logs a error_log() de PHP
         */
        // 'CodeIgniter\Log\Handlers\ErrorlogHandler' => [
        //     'handles'     => ['critical','alert','emergency','debug','error','info','notice','warning'],
        //     'messageType' => 0,
        // ],
    ];
}
