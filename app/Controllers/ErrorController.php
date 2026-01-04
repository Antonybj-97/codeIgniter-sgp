<?php namespace App\Controllers;

use CodeIgniter\Controller;

class ErrorController extends Controller
{
    // Recibe $message como argumento
    public function show404($message = null)
    {
        if (session()->get('isLoggedIn')) {
            return view('errors/404'); // Vista para usuarios logueados
        } else {
            return redirect()->route('auth.login')
                             ->with('error', 'Página no encontrada. Por favor, inicia sesión.');
        }
    }
}
