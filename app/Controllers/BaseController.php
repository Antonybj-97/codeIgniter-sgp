<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * Helpers autoload
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Variables comunes para todas las vistas
     */
    protected $data = [];

    /**
     * InitController
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Session y variables comunes
        $session = session();
        $this->data['currentPage'] = '';
        $this->data['isLoggedIn']  = $session->get('isLoggedIn') ?? false;
        $this->data['username']    = $session->get('username') ?? '';
    }

    /**
     * Renderiza una vista dentro del layout principal
     */
    protected function render(string $view, array $data = [])
    {
        $this->data = array_merge($this->data, $data);
        echo view('layouts/main', $this->data + ['content' => view($view, $this->data)]);
    }
}
