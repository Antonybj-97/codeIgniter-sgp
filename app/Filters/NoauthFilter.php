<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class NoauthFilter implements FilterInterface
{
    /**
     * Evita que usuarios ya logueados accedan a páginas de login/registro.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Usar helper de sesión de forma más limpia
        if (session()->get('logged_in')) {
            
            // 2. Obtener el rol una sola vez para mejorar rendimiento
            $rol = session()->get('rol');

            // 3. Redirección basada en el rol
            if ($rol === 'admin') {
                return redirect()->to(base_url('admin/dashboard'));
            }

            if ($rol === 'usuario') {
                return redirect()->to(base_url('usuario/dashboard'));
            }
            
            // 4. Fallback por si el rol no coincide con los anteriores
            return redirect()->to(base_url('/'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere acción después de la ejecución
    }
}