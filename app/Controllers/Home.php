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
        return redirect()->to('/login');
    }

    // =====================================================
    // PÁGINAS PÚBLICAS (accesibles sin login)
    // =====================================================
    public function reglamento()
    {
        return view('reglamento'); // Debes tener app/Views/reglamento.php
    }

    public function servicios()
    {
        return view('servicios'); // Debes tener app/Views/servicios.php
    }

    public function acercade()
    {
        return view('acercade'); // Debes tener app/Views/acercade.php
    }

    // =====================================================
    // DASHBOARD - SOLO PARA USUARIOS LOGUEADOS
    // =====================================================
    public function dashboard()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Debes iniciar sesión para acceder al dashboard');
        }

        $loteEntradaModel = new LoteEntradaModel();
        $loteSalidaModel  = new LoteSalidaModel();

        // =====================================================
        // FILTROS DE MES Y AÑO
        // =====================================================
        $mes  = (int) ($this->request->getGet('mes') ?? date('n'));
        $anio = (int) ($this->request->getGet('anio') ?? date('Y'));

        if (!checkdate($mes, 1, $anio)) {
            $mes  = date('n');
            $anio = date('Y');
        }

        $inicioMes = "$anio-$mes-01";
        $finMes    = date('Y-m-t', strtotime($inicioMes));

        // =====================================================
        // ESTADÍSTICAS DEL MES
        // =====================================================
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

        // =====================================================
        // TOTALES GLOBALES
        // =====================================================
        $totalLotes = $loteEntradaModel->countAll() + $loteSalidaModel->countAll();

        // =====================================================
        // LOTES PENDIENTES
        // =====================================================
        $lotesPendientes = $loteEntradaModel
            ->whereNotIn('id', $loteSalidaModel->select('lote_entrada_id'))
            ->countAllResults(false);

        // =====================================================
        // ÚLTIMOS MOVIMIENTOS (5 entradas + 5 salidas)
        // =====================================================
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

        // =====================================================
        // RENDERIZAR VISTA
        // =====================================================
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
