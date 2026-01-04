<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    /* =========================
        LOGIN
    ========================= */

    public function login()
    {
        return view('auth/login');
    }

    public function loginProcess()
    {
        $userModel = new UserModel();

        $input    = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $userModel
            ->groupStart()
                ->where('username', $input)
                ->orWhere('email', $input)
            ->groupEnd()
            ->first();

        // Verificamos existencia, estado activo y contraseña
        if (
            $user &&
            (int)$user['active'] === 1 &&
            password_verify($password, $user['password'])
        ) {
            /** * NORMALIZACIÓN DE ROL: 
             * Si en la BD es "Admin", aquí se guarda como "admin".
             * Esto es vital para que coincida con el AuthFilter.
             */
            $rolOriginal = $user['rol'] ?? 'usuario';
            $rol = (strtolower($rolOriginal) === 'admin' || strtolower($rolOriginal) === 'administrador') 
                   ? 'admin' 
                   : 'usuario';

            session()->set([
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'rol'        => $rol, // Siempre 'admin' o 'usuario'
                'name'       => $user['nombre_completo'] ?? '',
                'email'      => $user['email'],
                'isLoggedIn' => true
            ]);

            // Redirección basada en el rol normalizado
            $redirect = ($rol === 'admin')
                ? site_url('admin/dashboard')
                : site_url('usuario/dashboard');

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Bienvenido al sistema',
                'redirect' => $redirect
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Credenciales incorrectas o usuario inactivo'
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }

    /* =========================
        DASHBOARD (Distribuidor Central)
    ========================= */

    public function dashboard()
    {
        // Este método actúa como un "router" interno después del login
        $rol = session()->get('rol');

        if ($rol === 'admin') {
            return redirect()->to(site_url('admin/dashboard'));
        }

        return redirect()->to(site_url('usuario/dashboard'));
    }

    /* =========================
        FORGOT & RESET PASSWORD (Mantenido)
    ========================= */

    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function processForgotPassword()
    {
        $email = $this->request->getVar('email');
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'No existe un usuario con ese correo')
                ->withInput();
        }

        $token = bin2hex(random_bytes(32));

        $userModel->update($user['id'], [
            'reset_token'   => $token,
            'reset_expires' => date('Y-m-d H:i:s', strtotime('+1 hour')),
        ]);

        $emailService = \Config\Services::email();
        $emailService->setTo($user['email']);
        $emailService->setSubject('Restablecer contraseña');
        $emailService->setMessage(
            view('emails/reset_password', [
                'token' => $token,
                'name'  => $user['nombre_completo'] ?? 'Usuario'
            ])
        );
        
        if ($emailService->send()) {
            return redirect()->to(site_url('login'))
                ->with('success', 'Te enviamos un correo con instrucciones');
        }

        return redirect()->back()->with('error', 'Error al enviar el correo.');
    }

    public function resetPassword($token = null)
    {
        if (!$token) return redirect()->to(site_url('login'));

        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)->first();

        if (!$user || strtotime($user['reset_expires']) < time()) {
            return redirect()->to(site_url('login'))->with('error', 'El enlace ha expirado');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function processResetPassword()
    {
        $rules = [
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $token = $this->request->getVar('token');
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)->first();

        if (!$user || strtotime($user['reset_expires']) < time()) {
            return redirect()->to(site_url('login'))->with('error', 'Token inválido o expirado');
        }

        $userModel->update($user['id'], [
            'password'      => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'reset_token'   => null,
            'reset_expires' => null,
        ]);

        return redirect()->to(site_url('login'))->with('success', 'Contraseña actualizada correctamente');
    }

    /* =========================
        PERFIL DE USUARIO
    ========================= */
    public function perfil()
    {
        // 1. Verificar que el usuario esté logueado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        // 2. Obtener el ID desde la sesión (usaste 'user_id' en loginProcess)
        $userId = session()->get('user_id');

        // 3. Consultar la base de datos para obtener datos frescos
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to(site_url('login'))->with('error', 'Usuario no encontrado');
        }

        // 4. Preparar datos para la vista
        $data = [
            'title' => 'Mi Perfil',
            'user'  => $user
        ];

        return view('auth/perfil', $data);
    }
}