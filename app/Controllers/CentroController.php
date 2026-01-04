<?php

namespace App\Controllers;

use App\Models\CentroModel;
use CodeIgniter\Controller;

class CentroController extends Controller
{
    protected $centroModel;

    public function __construct()
    {
        $this->centroModel = new CentroModel();
    }

    // Listar todos los centros
    public function index()
    {
        $data = [
            'centros'     => $this->centroModel->findAll(),
            'currentPage' => 'centros',
        ];
        return view('centros/index', $data);
    }

    // Formulario para crear
    public function create()
    {
        return view('centros/create', ['currentPage' => 'centros']);
    }

    // Guardar nuevo centro
    public function store()
    {
        $this->centroModel->save([
            'nombre'      => $this->request->getPost('nombre'),
            'ubicacion'   => $this->request->getPost('ubicacion'),
            'descripcion' => $this->request->getPost('descripcion'),
            'is_active'   => $this->request->getPost('is_active') ?? 1,
        ]);

        return redirect()->to(site_url('centros'))
                         ->with('success', 'Centro creado correctamente.');
    }

    // Formulario para editar
    public function edit(int $id)
    {
        $centro = $this->centroModel->find($id);
        if (!$centro) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("No se encontró el centro con ID: $id");
        }

        return view('centros/edit', [
            'centro'      => $centro,
            'currentPage' => 'centros',
        ]);
    }

    // Actualizar centro
    public function update(int $id)
    {
        $this->centroModel->update($id, [
            'nombre'      => $this->request->getPost('nombre'),
            'ubicacion'   => $this->request->getPost('ubicacion'),
            'descripcion' => $this->request->getPost('descripcion'),
            'is_active'   => $this->request->getPost('is_active') ?? 1,
        ]);

        return redirect()->to(site_url('centros'))
                         ->with('success', 'Centro actualizado correctamente.');
    }

    // Eliminar centro
    public function delete(int $id)
    {
        $this->centroModel->delete($id);

        return redirect()->to(site_url('centros'))
                         ->with('success', 'Centro eliminado correctamente.');
    }
}
