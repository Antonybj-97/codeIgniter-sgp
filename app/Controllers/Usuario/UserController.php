<?php

namespace App\Controllers\Usuario;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * VISTA DEL DASHBOARD PRINCIPAL
     */
    public function dashboard(): string
    {
        return view('usuario/dashboard');
    }

    /**
     * API PARA EL DASHBOARD (AJAX)
     * Procesa las estadísticas de Entradas, Salidas y Lotes
     */
    public function dashboardAjax()
    {
        $anio = $this->request->getGet('anio') ?? date('Y');
        $mesActual = date('m');

        // 1. Estadísticas de Pesos (KPIs)
        // Nota: Asumiendo que tienes una tabla 'lotes' o 'movimientos'
        $entradasMes = $this->db->table('lotes')
            ->selectSum('peso_neto')
            ->where('tipo', 'entrada')
            ->where('MONTH(fecha_registro)', $mesActual)
            ->where('YEAR(fecha_registro)', $anio)
            ->get()->getRow()->peso_neto ?? 0;

        $salidasMes = $this->db->table('lotes')
            ->selectSum('peso_neto')
            ->where('tipo', 'salida')
            ->where('MONTH(fecha_registro)', $mesActual)
            ->where('YEAR(fecha_registro)', $anio)
            ->get()->getRow()->peso_neto ?? 0;

        $lotesPendientes = $this->db->table('lotes')
            ->where('estado', 'pendiente')
            ->countAllResults();

        $totalLotes = $this->db->table('lotes')
            ->countAllResults();

        // 2. Datos para el Gráfico de Barras (Mes a Mes)
        $mesesLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $dataEntradas = array_fill(0, 12, 0);
        $dataSalidas = array_fill(0, 12, 0);

        $movimientosAnuales = $this->db->table('lotes')
            ->select('MONTH(fecha_registro) as mes, tipo, SUM(peso_neto) as total')
            ->where('YEAR(fecha_registro)', $anio)
            ->groupBy('mes, tipo')
            ->get()->getResult();

        foreach ($movimientosAnuales as $mov) {
            if ($mov->tipo == 'entrada') $dataEntradas[$mov->mes - 1] = (float)$mov->total;
            if ($mov->tipo == 'salida') $dataSalidas[$mov->mes - 1] = (float)$mov->total;
        }

        // 3. Últimos 10 movimientos para la tabla
        $ultimosMovimientos = $this->db->table('lotes')
            ->select('lotes.*, clientes.nombre as cliente')
            ->join('clientes', 'clientes.id = lotes.cliente_id', 'left')
            ->orderBy('lotes.fecha_registro', 'DESC')
            ->limit(10)
            ->get()->getResult();

        return $this->response->setJSON([
            'entradas_mes'        => (float)$entradasMes,
            'salidas_mes'         => (float)$salidasMes,
            'lotes_pendientes'    => $lotesPendientes,
            'total_lotes'         => $totalLotes,
            'meses'               => $mesesLabels,
            'entradas'            => $dataEntradas,
            'salidas'             => $dataSalidas,
            'ultimos_movimientos' => $ultimosMovimientos
        ]);
    }

    /**
     * CRUD DE USUARIOS
     */
    public function index()
    {
        $data = [
            'title'         => 'Usuarios',
            'section_title' => 'Gestión de Usuarios',
            'users'         => $this->userModel->findAll()
        ];
        return view('usuario/index', $data);
    }

    public function create()
    {
        return view('usuario/create', [
            'title'         => 'Nuevo Usuario',
            'section_title' => 'Registrar Usuario'
        ]);
    }

    public function store()
    {
        $rules = [
            'nombre_completo' => 'required|min_length[3]',
            'username'        => 'required|min_length[3]|is_unique[users.username]',
            'email'           => 'required|valid_email|is_unique[users.email]',
            'password'        => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
        }

        $this->userModel->insert([
            'nombre_completo' => $this->request->getPost('nombre_completo'),
            'username'        => $this->request->getPost('username'),
            'email'           => $this->request->getPost('email'),
            'rol'             => $this->request->getPost('rol') ?? 'user',
            'password'        => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'active'          => 1
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Usuario creado correctamente']);
    }

    public function edit(int $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(site_url('usuario'))->with('error', 'Usuario no encontrado');
        }

        return view('usuario/create', [
            'title'         => 'Editar Usuario',
            'section_title' => 'Editar Usuario',
            'user'          => $user
        ]);
    }

    public function update(int $id)
    {
        if (!$this->userModel->find($id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Usuario no encontrado']);
        }

        $rules = [
            'nombre_completo' => 'required|min_length[3]',
            'username'        => "required|min_length[3]|is_unique[users.username,id,{$id}]",
            'email'           => "required|valid_email|is_unique[users.email,id,{$id}]",
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
        }

        $data = [
            'nombre_completo' => $this->request->getPost('nombre_completo'),
            'username'        => $this->request->getPost('username'),
            'email'           => $this->request->getPost('email'),
            'rol'             => $this->request->getPost('rol') ?? 'user',
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);
        return $this->response->setJSON(['success' => true, 'message' => 'Usuario actualizado']);
    }

    public function delete(int $id)
    {
        if ($this->userModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Usuario eliminado']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar']);
    }

   
}