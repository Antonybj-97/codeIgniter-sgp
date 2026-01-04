<?php

namespace App\Controllers;

use App\Models\TipoPimientaModel;
use CodeIgniter\Controller;

class TipoPimientaController extends Controller
{
    protected $tipoPimientaModel;

    public function __construct()
    {
        $this->tipoPimientaModel = new TipoPimientaModel();
    }

    public function index()
    {
        $data['tipos'] = $this->tipoPimientaModel->findAll();
        return view('tipopimienta/index', $data);
    }

    public function create()
    {
        return view('tipopimienta/create');
    }

    public function store()
    {
        $this->tipoPimientaModel->insert([
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio_base' => $this->request->getPost('precio_base'),
            'is_active'   => $this->request->getPost('is_active') ?? 1,
        ]);

        return redirect()->to('/tipopimienta')->with('success', 'Tipo de pimienta creado ✅');
    }

    public function edit($id)
    {
        $data['tipo'] = $this->tipoPimientaModel->find($id);
        if (!$data['tipo']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("No se encontró el tipo de pimienta con ID: $id");
        }
        return view('tipopimienta/edit', $data);
    }

    public function update($id)
    {
        $this->tipoPimientaModel->update($id, [
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio_base' => $this->request->getPost('precio_base'),
            'is_active'   => $this->request->getPost('is_active') ?? 1,
        ]);

        return redirect()->to('/tipopimienta')->with('success', 'Tipo de pimienta actualizado ✅');
    }

    public function delete($id)
    {
        $this->tipoPimientaModel->delete($id);
        return redirect()->to('/tipopimienta')->with('success', 'Tipo de pimienta eliminado ✅');
    }
}
