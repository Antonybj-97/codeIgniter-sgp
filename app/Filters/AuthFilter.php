<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Lógica de validación antes de acceder a la ruta
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Verificar sesión activa
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'))
                             ->with('error', 'Sesión expirada. Por favor, ingresa de nuevo.');
        }

        // 2. Control de Acceso por Roles (RBAC)
        if (!empty($arguments)) {
            // Obtenemos el rol de la sesión (ej: "admin" o "usuario")
            $rolUsuario = strtolower(session()->get('rol') ?? '');
            
            // Normalizamos los roles permitidos en la ruta a minúsculas
            $rolesPermitidos = array_map('strtolower', $arguments);

            // Si el rol del usuario no está en la lista de permitidos
            if (!in_array($rolUsuario, $rolesPermitidos)) {
                
                // Redirección inteligente basada en el rol actual del usuario
                $urlRetorno = ($rolUsuario === 'admin') ? 'admin/dashboard' : 'usuario/dashboard';
                
                return redirect()->to(site_url($urlRetorno))
                                 ->with('error', 'Acceso denegado: Tu perfil (' . ucfirst($rolUsuario) . ') no tiene permisos para esta área.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere acción después de la ejecución
    }
}