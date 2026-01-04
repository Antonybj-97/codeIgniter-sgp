<?php

namespace App\Controllers;

use App\Models\LoteEntradaModel;
use App\Models\LoteSalidaModel;

class Home extends BaseController
{
    // =====================================================
    // MÉTODO PRINCIPAL - REDIRIGE A LOGIN
    // =====================================================
    public function index()
    {
        // Redirige automáticamente a la página de login
        return redirect()->to('/login');
    }
    
    // =====================================================
    // PÁGINAS PÚBLICAS (accesibles sin login)
    // =====================================================
    public function reglamento()
    {
        // Si quieres que esta página sea pública, déjala así
        return view('reglamento'); // Asegúrate de tener esta vista
    }
    
    public function servicios()
    {
        return view('servicios'); // Asegúrate de tener esta vista
    }
    
    public function acercade()
    {
        return view('acercade'); // Asegúrate de tener esta vista
    }
    
    // =====================================================
    // DASHBOARD - MOVER A AuthController (RECOMENDADO)
    // =====================================================
    public function dashboard()
    {
        // Esta función debería estar en AuthController o DashboardController
        // Por ahora la dejamos aquí pero con validación de sesión
        
        $session = session();

        // === Validar sesión ===
        if ($session->get('isLoggedIn') !== true) {
            return redirect()->to('/login')
                ->with('error', 'Debes iniciar sesión para acceder al dashboard');
        }

        $loteEntradaModel = new LoteEntradaModel();
        $loteSalidaModel  = new LoteSalidaModel();

        // === Filtros de mes y año ===
        $mes  = (int) ($this->request->getGet('mes') ?? date('n'));
        $anio = (int) ($this->request->getGet('anio') ?? date('Y'));

        if (!checkdate($mes, 1, $anio)) {
            $mes  = (int) date('n');
            $anio = (int) date('Y');
        }

        $inicioMes = date('Y-m-01', strtotime("$anio-$mes-01"));
        $finMes    = date('Y-m-t', strtotime("$anio-$mes-01"));

        // === Estadísticas del mes ===
        $entradasMes = (float) ($loteEntradaModel
            ->where('fecha_entrada >=', $inicioMes)
            ->where('fecha_entrada <=', $finMes)
            ->selectSum('peso_bruto_kg')
            ->first()['peso_bruto_kg'] ?? 0);

        $salidasMes = (float) ($loteSalidaModel
            ->where('fecha_salida >=', $inicioMes)
            ->where('fecha_salida <=', $finMes)
            ->selectSum('peso_neto_kg')
            ->first()['peso_neto_kg'] ?? 0);

        // === Totales globales ===
        $totalLotes = $loteEntradaModel->countAll() + $loteSalidaModel->countAll();

        // === Lotes pendientes ===
        $lotesPendientes = $loteEntradaModel
            ->whereNotIn('id', $loteSalidaModel->select('lote_entrada_id'))
            ->countAllResults(false);

        // === Últimos movimientos (5 entradas + 5 salidas) ===
        $ultimosMovimientos = $loteEntradaModel->db
            ->query("
                (SELECT id, fecha_entrada AS fecha, peso_bruto_kg AS monto, 'Entrada' AS tipo
                 FROM lote_entradas
                 ORDER BY fecha_entrada DESC
                 LIMIT 5)
                UNION ALL
                (SELECT id, fecha_salida AS fecha, peso_neto_kg AS monto, 'Salida' AS tipo
                 FROM lote_salidas
                 ORDER BY fecha_salida DESC
                 LIMIT 5)
                ORDER BY fecha DESC
            ")
            ->getResultArray();

        // === Renderizar vista ===
        return view('dashboard', [
            'currentPage'         => 'home',
            'username'            => $session->get('username'),
            'role'                => $session->get('rol'),
            'entradas_mes'        => $entradasMes,
            'salidas_mes'         => $salidasMes,
            'total_lotes'         => $totalLotes,
            'lotes_pendientes'    => $lotesPendientes,
            'ultimos_movimientos' => $ultimosMovimientos,
            'mes'                 => $mes,
            'anio'                => $anio,
        ]);
    }
}