<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard - Sistema de Gestión de Pimienta<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Dependencias actualizadas -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/countup.js@2.2.0/dist/countUp.umd.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
/* Modern Premium Dashboard Theme - Earth Tones (Pepper Theme) */
:root {
  --bg-gradient: linear-gradient(135deg, #f7f9f5 0%, #e8f0e3 100%);
  --bg-solid: #f9faf8;
  --card-bg: #ffffff;
  --text-primary: #2d3319;
  --text-secondary: #5a6c3d;
  --text-muted: #8b9c6f;
  
  /* Earth tone gradients - Green, Orange, Brown */
  --gradient-primary: linear-gradient(135deg, #2d5016 0%, #1e3a0f 100%);
  --gradient-green: linear-gradient(135deg, #4a7c2f 0%, #3a6124 100%);
  --gradient-lime: linear-gradient(135deg, #6b9b37 0%, #5a8529 100%);
  --gradient-orange: linear-gradient(135deg, #d97706 0%, #b45309 100%);
  --gradient-brown: linear-gradient(135deg, #92400e 0%, #78350f 100%);
  --gradient-amber: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
  
  --shadow-sm: 0 2px 8px rgba(45, 80, 22, 0.08);
  --shadow-md: 0 4px 12px rgba(45, 80, 22, 0.12);
  --shadow-lg: 0 8px 24px rgba(45, 80, 22, 0.15);
  --shadow-xl: 0 12px 32px rgba(45, 80, 22, 0.18);
  
  --border-radius-sm: 8px;
  --border-radius-md: 12px;
  --border-radius-lg: 16px;
  --border-radius-xl: 20px;
}

[data-theme="dark"] {
  --bg-gradient: linear-gradient(135deg, #1a1f15 0%, #242b1e 100%);
  --bg-solid: #1a1f15;
  --card-bg: #242b1e;
  --text-primary: #f0f4e8;
  --text-secondary: #c9d4b8;
  --text-muted: #8b9c6f;
  
  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.4);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.5);
  --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.6);
  --shadow-xl: 0 12px 32px rgba(0, 0, 0, 0.7);
}

body {
  background: var(--bg-solid);
  color: var(--text-primary);
  font-family: 'Inter', 'Segoe UI', Roboto, -apple-system, BlinkMacSystemFont, sans-serif;
  transition: all 0.3s ease;
  margin: 0;
  padding: 0;
  min-height: 100vh;
}

/* Header Styles */
.dashboard-header {
  background: var(--gradient-primary);
  border-radius: var(--border-radius-lg);
  padding: 25px 30px;
  margin-bottom: 30px;
  box-shadow: var(--shadow-lg);
  color: white;
}

.dashboard-header .logo-wrapper {
  background: rgba(255, 255, 255, 0.2);
  padding: 8px;
  border-radius: var(--border-radius-md);
  backdrop-filter: blur(10px);
}

.dashboard-header .header-logo {
  width: 56px;
  height: 56px;
  border-radius: var(--border-radius-sm);
  object-fit: cover;
}

.dashboard-header h1 {
  font-size: 1.75rem;
  font-weight: 700;
  margin: 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.dashboard-header .subtitle {
  font-size: 0.95rem;
  opacity: 0.9;
  margin: 0;
}

/* Card Styles */
.card-premium {
  background: var(--card-bg);
  border-radius: var(--border-radius-lg);
  box-shadow: var(--shadow-md);
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 1px solid rgba(45, 80, 22, 0.08);
}

.card-premium:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-xl);
}

/* KPI Cards */
.kpi-card {
  background: var(--card-bg);
  border-radius: var(--border-radius-lg);
  padding: 24px;
  box-shadow: var(--shadow-md);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(45, 80, 22, 0.08);
  height: 100%;
}

.kpi-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: var(--gradient-green);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.kpi-card:hover::before {
  opacity: 1;
}

.kpi-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-lg);
}

.kpi-card.success::before { background: var(--gradient-green); }
.kpi-card.primary::before { background: var(--gradient-lime); }
.kpi-card.warning::before { background: var(--gradient-orange); }
.kpi-card.info::before { background: var(--gradient-brown); }

.kpi-label {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--text-secondary);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 8px;
}

.kpi-value {
  font-size: 2rem;
  font-weight: 700;
  color: var(--text-primary);
  margin: 8px 0;
  line-height: 1;
}

.kpi-icon-wrapper {
  width: 56px;
  height: 56px;
  border-radius: var(--border-radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.75rem;
}

.kpi-icon-wrapper.success { 
  background: linear-gradient(135deg, rgba(74, 124, 47, 0.15) 0%, rgba(58, 97, 36, 0.15) 100%); 
  color: #3a6124; 
}
.kpi-icon-wrapper.primary { 
  background: linear-gradient(135deg, rgba(107, 155, 55, 0.15) 0%, rgba(90, 133, 41, 0.15) 100%); 
  color: #5a8529; 
}
.kpi-icon-wrapper.warning { 
  background: linear-gradient(135deg, rgba(217, 119, 6, 0.15) 0%, rgba(180, 83, 9, 0.15) 100%); 
  color: #b45309; 
}
.kpi-icon-wrapper.info { 
  background: linear-gradient(135deg, rgba(146, 64, 14, 0.15) 0%, rgba(120, 53, 15, 0.15) 100%); 
  color: #78350f; 
}

/* Chart Cards */
.chart-card {
  background: var(--card-bg);
  border-radius: var(--border-radius-lg);
  padding: 24px;
  box-shadow: var(--shadow-md);
  height: 100%;
  border: 1px solid rgba(45, 80, 22, 0.08);
}

.chart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 16px;
  border-bottom: 2px solid rgba(45, 80, 22, 0.08);
}

.chart-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--text-primary);
  display: flex;
  align-items: center;
  gap: 10px;
}

.chart-title i {
  color: #4a7c2f;
}

/* Table Styles */
.table-card {
  background: var(--card-bg);
  border-radius: var(--border-radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-md);
  border: 1px solid rgba(45, 80, 22, 0.08);
}

.table-header {
  background: var(--gradient-green);
  padding: 20px 24px;
  color: white;
}

.table-header h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 10px;
}

.table-responsive {
  padding: 24px;
}

.table {
  margin: 0;
  width: 100% !important;
}

.table thead th {
  background: #f7f9f5;
  color: var(--text-secondary);
  font-weight: 600;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid #e8f0e3;
  padding: 12px 16px;
  white-space: nowrap;
}

.table tbody td {
  padding: 14px 16px;
  vertical-align: middle;
  color: var(--text-primary);
  white-space: nowrap;
}

.table-hover tbody tr:hover {
  background: rgba(74, 124, 47, 0.05);
  transition: background 0.2s ease;
}

/* Badges */
.badge-entrada {
  background: var(--gradient-green);
  color: white;
  padding: 6px 14px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.8rem;
  box-shadow: 0 2px 6px rgba(74, 124, 47, 0.3);
  display: inline-block;
}

.badge-salida {
  background: var(--gradient-orange);
  color: white;
  padding: 6px 14px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.8rem;
  box-shadow: 0 2px 6px rgba(217, 119, 6, 0.3);
  display: inline-block;
}

/* Controls */
.form-select-custom {
  border: 2px solid #e8f0e3;
  border-radius: var(--border-radius-sm);
  padding: 8px 12px;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  background: white;
  color: var(--text-primary);
  cursor: pointer;
}

.form-select-custom:focus {
  border-color: #4a7c2f;
  box-shadow: 0 0 0 3px rgba(74, 124, 47, 0.1);
  outline: none;
}

.btn-refresh {
  background: white;
  border: 2px solid rgba(255, 255, 255, 0.3);
  color: var(--gradient-primary);
  padding: 8px 16px;
  border-radius: var(--border-radius-sm);
  font-weight: 600;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}

.btn-refresh:hover {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.5);
  color: white;
  transform: translateY(-2px);
  box-shadow: var(--shadow-sm);
}

/* Dark Mode Toggle */
.theme-toggle {
  background: rgba(255, 255, 255, 0.2);
  padding: 8px 16px;
  border-radius: 20px;
  backdrop-filter: blur(10px);
  display: flex;
  align-items: center;
  gap: 8px;
}

.form-check-input {
  cursor: pointer;
  width: 18px;
  height: 18px;
}

.form-check-input:checked {
  background-color: #4a7c2f;
  border-color: #4a7c2f;
}

/* Sparkline Container */
.sparkline-container {
  margin-top: 12px;
  padding-top: 12px;
  border-top: 1px solid rgba(45, 80, 22, 0.08);
  min-height: 50px;
}

.spark-canvas {
  height: 50px !important;
  width: 100% !important;
}

/* Animations */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.kpi-card, .chart-card, .table-card {
  animation: fadeInUp 0.5s ease;
}

.kpi-card:nth-child(1) { animation-delay: 0.1s; }
.kpi-card:nth-child(2) { animation-delay: 0.2s; }
.kpi-card:nth-child(3) { animation-delay: 0.3s; }
.kpi-card:nth-child(4) { animation-delay: 0.4s; }

/* Responsive */
@media (max-width: 768px) {
  .dashboard-header {
    padding: 20px;
  }
  
  .dashboard-header h1 {
    font-size: 1.5rem;
  }
  
  .kpi-value {
    font-size: 1.75rem;
  }
  
  .chart-header {
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
  }
  
  .table-responsive {
    padding: 10px;
    overflow-x: auto;
  }
  
  .table thead th,
  .table tbody td {
    padding: 8px 10px;
    font-size: 0.85rem;
  }
}

/* Loading State */
.loading-spinner {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Smooth transitions for all interactive elements */
* {
  transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

/* Dark mode adjustments */
[data-theme="dark"] .table thead th {
  background: #1a1f15;
  color: var(--text-secondary);
  border-bottom: 2px solid #2d3319;
}

[data-theme="dark"] .form-select-custom {
  background: #2d3319;
  border-color: #4a7c2f;
  color: var(--text-primary);
}

[data-theme="dark"] .kpi-card {
  border-color: rgba(255, 255, 255, 0.05);
}

[data-theme="dark"] .btn-refresh {
  color: white;
}

/* Loading overlay */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  display: none;
}

.loading-overlay .spinner {
  width: 50px;
  height: 50px;
  border: 5px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}
</style>

<div class="container-fluid py-4">

  <!-- Loading overlay -->
  <div id="loadingOverlay" class="loading-overlay">
    <div class="spinner"></div>
  </div>

  <!-- Header / Controls -->
  <div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-3">
        <div class="logo-wrapper">
          <img src="<?= base_url('assets/img/logo01.jpg') ?>" alt="Logo" class="header-logo" onerror="this.style.display='none'">
        </div>
        <div>
          <h1>Sistema de Gestión de Pimienta</h1>
          <p class="subtitle">Panel de control · Dashboard</p>
        </div>
      </div>

      <div class="d-flex align-items-center gap-3">
        <div class="theme-toggle">
          <input class="form-check-input" type="checkbox" id="toggleDark">
          <label class="form-check-label" for="toggleDark" style="color: white; font-size: 0.9rem;">Modo oscuro</label>
        </div>
        <button id="btnRefresh" class="btn-refresh">
          <i class="bi bi-arrow-clockwise"></i> Refrescar
        </button>
      </div>
    </div>
  </div>

  <!-- KPIs -->
  <div class="row g-3">
    <?php 
    $kpis = [
      ['id'=>'entradas','label'=>'Entradas del Año','icon'=>'bi-box-arrow-in-down','color'=>'success','suffix'=>' kg'],
      ['id'=>'salidas','label'=>'Salidas del Año','icon'=>'bi-box-arrow-up','color'=>'primary','suffix'=>' kg'],
      ['id'=>'pendientes','label'=>'Lotes Pendientes','icon'=>'bi-clock-history','color'=>'warning','suffix'=>''],
      ['id'=>'total','label'=>'Total de Lotes','icon'=>'bi-stack','color'=>'info','suffix'=>'']
    ]; 
    ?>

    <?php foreach($kpis as $k): ?>
    <div class="col-12 col-sm-6 col-md-3">
      <div class="kpi-card <?= esc($k['color']) ?>">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="kpi-label"><?= esc($k['label']) ?></div>
            <div id="kpi-<?= esc($k['id']) ?>" class="kpi-value">0<?= esc($k['suffix']) ?></div>
          </div>
          <div class="kpi-icon-wrapper <?= esc($k['color']) ?>">
            <i class="bi <?= esc($k['icon']) ?>"></i>
          </div>
        </div>

        <?php if(in_array($k['id'], ['entradas','salidas'])): ?>
        <div class="sparkline-container">
          <canvas id="spark-<?= esc($k['id']) ?>" class="spark-canvas"></canvas>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Gráficos principales -->
  <div class="row g-3 mt-3">
    <div class="col-lg-4">
      <div class="chart-card">
        <div class="chart-header">
          <span class="chart-title"><i class="bi bi-bar-chart"></i> Entradas vs Salidas</span>
          <select id="anioGrafico" class="form-select form-select-custom w-auto">
            <?php 
            $anioActual = date('Y');
            for($y = $anioActual - 5; $y <= $anioActual; $y++): 
            ?>
              <option value="<?= $y ?>" <?= $y == $anioActual ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div style="height:260px; position: relative;">
          <canvas id="chartMain"></canvas>
          <div id="chartMainEmpty" class="text-muted text-center" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: none;">
            <i class="bi bi-bar-chart" style="font-size: 3rem; opacity: 0.3;"></i>
            <p class="mt-2">No hay datos para mostrar</p>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="chart-card">
        <div class="chart-header">
          <span class="chart-title"><i class="bi bi-box-seam"></i> Inventario por Tipo</span>
          <select id="anioInventarioTipoSelect" class="form-select form-select-custom w-auto">
            <?php for($y = $anioActual - 5; $y <= $anioActual; $y++): ?>
              <option value="<?= $y ?>" <?= $y == $anioActual ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div style="height:240px; position: relative;">
          <canvas id="chartInventarioTipo"></canvas>
          <div id="chartInventarioTipoEmpty" class="text-muted text-center" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: none;">
            <i class="bi bi-pie-chart" style="font-size: 3rem; opacity: 0.3;"></i>
            <p class="mt-2">No hay datos para mostrar</p>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="chart-card">
        <div class="chart-header">
          <span class="chart-title"><i class="bi bi-building"></i> Inventario por Centros</span>
          <select id="anioInventarioCentroSelect" class="form-select form-select-custom w-auto">
            <?php for($y = $anioActual - 5; $y <= $anioActual; $y++): ?>
              <option value="<?= $y ?>" <?= $y == $anioActual ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div style="height:240px; position: relative;">
          <canvas id="chartInventarioCentro"></canvas>
          <div id="chartInventarioCentroEmpty" class="text-muted text-center" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: none;">
            <i class="bi bi-building" style="font-size: 3rem; opacity: 0.3;"></i>
            <p class="mt-2">No hay datos para mostrar</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Inventario combinado -->
  <div class="row g-3 mt-3">
    <div class="col-12">
      <div class="chart-card">
        <div class="chart-header">
          <span class="chart-title"><i class="bi bi-bar-chart-fill"></i> Inventario por Tipo y Centro</span>
          <select id="anioInventarioCombinado" class="form-select form-select-custom w-auto">
            <?php for($y = $anioActual - 5; $y <= $anioActual; $y++): ?>
              <option value="<?= $y ?>" <?= $y == $anioActual ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div style="height:360px; position: relative;">
          <canvas id="chartInventarioCombinado"></canvas>
          <div id="chartInventarioCombinadoEmpty" class="text-muted text-center" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: none;">
            <i class="bi bi-bar-chart-fill" style="font-size: 3rem; opacity: 0.3;"></i>
            <p class="mt-2">No hay datos para mostrar</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Últimos movimientos -->
  <div class="row g-3 mt-3">
    <div class="col-12">
      <div class="table-card">
        <div class="table-header">
          <h3><i class="bi bi-clock-history"></i> Últimos Movimientos</h3>
        </div>
        <div class="table-responsive">
          <table id="tablaMovimientos" class="table table-hover align-middle text-center" style="width:100%">
            <thead>
              <tr>
                <th width="5%">#</th>
                <th width="10%">Tipo</th>
                <th width="15%">Peso (kg)</th>
                <th width="15%">Fecha</th>
                <th width="55%">Proveedor / Cliente</th>
              </tr>
            </thead>
            <tbody>
              <!-- Los datos se cargarán dinámicamente -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- JS: lógica del dashboard -->
<script>
/**
 * Módulo de Aplicación para el Dashboard (IIFE: Immediately Invoked Function Expression)
 */
const DashboardApp = (function ($) {
    'use strict';

    // 1. Configuraciones
    const CONFIG = {
        urls: {
            dashboard: '<?= site_url('admin/dashboard-ajax') ?>',
            inventario: '<?= site_url('admin/dashboard-inventario-ajax') ?>',
            inventarioCombinado: '<?= site_url('admin/dashboard-inventario-combinado-ajax') ?>'
        },
        PALETTE: [
            '#4a7c2f', // Verde oscuro principal
            '#6b9b37', // Verde lima brillante
            '#d97706', // Naranja brillante
            '#b45309', // Naranja oscuro
            '#2d5016', // Verde muy oscuro
            '#92400e', // Café/marrón
            '#5a8529', // Verde oliva
            '#f59e0b'  // Ámbar/Amarillo-Naranja
        ],
        DATATABLES_LANGUAGE: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
        THEME_STORAGE_KEY: 'sgp-theme'
    };

    // 2. Variables de Estado
    const charts = { 
        main: null, 
        inventarioTipo: null, 
        inventarioCentro: null, 
        inventarioCombinado: null, 
        sparks: {} 
    };
    let tablaMovimientos = null;

    // 3. Helpers y Utilidades

    /**
     * Devuelve un conjunto de N colores de la paleta.
     */
    function getColorSet(n) {
        const colors = [];
        for (let i = 0; i < n; i++) {
            colors.push(CONFIG.PALETTE[i % CONFIG.PALETTE.length]);
        }
        return colors;
    }

    /**
     * Destruye una instancia de Chart.js y limpia la referencia.
     */
    function destroyChart(name) {
        // Si la gráfica está en el objeto sparks
        if (charts.sparks && charts.sparks[name]) {
            charts.sparks[name].destroy();
            delete charts.sparks[name];
            return;
        }
        // Si la gráfica está en el nivel superior de charts
        if (charts[name]) {
            charts[name].destroy();
            charts[name] = null;
        }
    }

    /**
     * Muestra/oculta mensaje de datos vacíos
     */
    function toggleEmptyMessage(chartId, isEmpty) {
        const emptyDiv = document.getElementById(chartId + 'Empty');
        if (emptyDiv) {
            emptyDiv.style.display = isEmpty ? 'block' : 'none';
        }
    }

    /**
     * Inicializa la tabla DataTables.
     */
    function initDataTable() {
        // Si ya existe una instancia, la destruimos
        if ($.fn.DataTable.isDataTable('#tablaMovimientos')) {
            tablaMovimientos.destroy();
            $('#tablaMovimientos').empty();
        }
        
        // Opciones de DataTables
        const dtOptions = {
            paging: true,
            searching: true,
            ordering: true,
            pageLength: 8,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            language: {
                url: CONFIG.DATATABLES_LANGUAGE
            },
            columnDefs: [
                { orderable: true, targets: [0, 1, 2, 3, 4] }
            ]
        };
        
        tablaMovimientos = $('#tablaMovimientos').DataTable(dtOptions);
    }
    
    // 4. Renderizadores de UI

    /**
     * Renderiza los KPIs y los gráficos Sparkline.
     */
    function renderKPIs(resp) {
        // Verificar que resp existe
        if (!resp) {
            console.error('No hay datos para renderizar KPIs');
            return;
        }

        const kpiMap = {
            entradas: 'entradas_mes_total',
            salidas: 'salidas_mes_total',
            pendientes: 'lotes_pendientes',
            total: 'total_lotes'
        };

        Object.entries(kpiMap).forEach(([id, key]) => {
            // Usar valor por defecto si no existe en resp
            const val = resp[key] !== undefined ? resp[key] : 0;
            const el = document.getElementById('kpi-' + id);
            if (!el) return;
            const suffix = (id === 'entradas' || id === 'salidas') ? ' kg' : '';

            try {
                // Verificar que countUp está disponible
                if (typeof countUp !== 'undefined' && countUp.CountUp) {
                    new countUp.CountUp(el, Number(val), { 
                        suffix: suffix, 
                        decimalPlaces: 2 
                    }).start();
                } else {
                    el.textContent = val + suffix;
                }
            } catch(e) {
                console.warn('Error con countUp:', e);
                el.textContent = val + suffix;
            }
        });

        // Sparks
        ['entradas', 'salidas'].forEach(k => {
            destroyChart(k);
            const ctx = document.getElementById('spark-' + k);
            if (!ctx) return;
            
            // Obtener datos de resp
            let data = [];
            if (resp[k + '_mes'] && Array.isArray(resp[k + '_mes'])) {
                data = resp[k + '_mes'];
            } else {
                data = Array(12).fill(0);
            }
            
            const ctx2d = ctx.getContext('2d');
            const color = (k === 'entradas') ? CONFIG.PALETTE[0] : CONFIG.PALETTE[2]; 
            
            charts.sparks[k] = new Chart(ctx2d, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 
                            'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{ 
                        data: data, 
                        borderColor: color, 
                        backgroundColor: 'rgba(0,0,0,0)', 
                        pointRadius: 0, 
                        tension: 0.35 
                    }]
                },
                options: { 
                    plugins: { legend: { display: false } }, 
                    scales: { 
                        x: { display: false }, 
                        y: { display: false } 
                    }, 
                    elements: { line: { borderWidth: 2 } },
                    maintainAspectRatio: false 
                }
            });
        });
    }

    /**
     * Renderiza la tabla de Últimos Movimientos.
     */
    function renderMovimientos(movs) {
        if (!Array.isArray(movs)) {
            console.warn('Los movimientos no son un array:', movs);
            return;
        }
        
        // Limpiar la tabla
        if (tablaMovimientos) {
            tablaMovimientos.clear();
            
            // Si no hay movimientos
            if (movs.length === 0) {
                tablaMovimientos.row.add([
                    '', 
                    '<span class="text-muted">Sin datos</span>', 
                    '<span class="text-muted">-</span>', 
                    '<span class="text-muted">-</span>', 
                    '<span class="text-muted">No hay movimientos registrados</span>'
                ]);
            } else {
                // Agregar filas
                movs.forEach((m, i) => {
                    if (!m) return;
                    
                    const isEntrada = m.tipo === 'entrada';
                    const tipoBadge = isEntrada ? 
                        '<span class="badge-entrada">Entrada</span>' : 
                        '<span class="badge-salida">Salida</span>';
                    
                    const peso = m.peso ? Number(m.peso).toFixed(2) : '0.00';
                    const fecha = m.fecha ? new Date(m.fecha).toLocaleDateString('es-ES') : '-';
                    const tercero = m.cliente || m.proveedor || m.cliente_proveedor || '-';
                    
                    tablaMovimientos.row.add([i + 1, tipoBadge, peso, fecha, tercero]);
                });
            }
            
            // Redibujar
            tablaMovimientos.draw();
        }
    }

    /**
     * Renderiza el gráfico principal (Entradas vs Salidas).
     */
    function renderChartMain(resp) {
        if (!resp) return;
        
        destroyChart('main');
        const ctx = document.getElementById('chartMain');
        if (!ctx) return;
        
        // Datos por defecto
        const labels = resp.meses || ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 
                                     'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        const entradasData = resp.entradas || Array(12).fill(0);
        const salidasData = resp.salidas || Array(12).fill(0);
        
        // Verificar si hay datos
        const hasData = entradasData.some(val => val > 0) || salidasData.some(val => val > 0);
        toggleEmptyMessage('chartMain', !hasData);
        
        if (!hasData) return;
        
        const ctx2d = ctx.getContext('2d');
        const colors = [CONFIG.PALETTE[0], CONFIG.PALETTE[2]]; 
        
        charts.main = new Chart(ctx2d, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { 
                        label: 'Entradas (kg)', 
                        data: entradasData, 
                        backgroundColor: colors[0], 
                        borderRadius: 6 
                    },
                    { 
                        label: 'Salidas (kg)', 
                        data: salidasData, 
                        backgroundColor: colors[1], 
                        borderRadius: 6 
                    }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                scales: { 
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' kg';
                            }
                        }
                    } 
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                }
            }
        });
    }

    /**
     * Renderiza gráficos Doughnut.
     */
    function renderInventario(resp, canvasId, chartName) {
        if (!resp || !resp.labels || !Array.isArray(resp.labels)) {
            console.warn(`No hay datos para ${chartName}`);
            toggleEmptyMessage(canvasId, true);
            return;
        }
        
        // Verificar si hay datos
        const hasData = resp.labels.length > 0 && 
                       resp.datasets && 
                       resp.datasets[0] && 
                       resp.datasets[0].data && 
                       resp.datasets[0].data.some(val => val > 0);
        
        toggleEmptyMessage(canvasId, !hasData);
        
        if (!hasData) {
            destroyChart(chartName);
            return;
        }
        
        destroyChart(chartName);
        const ctx = document.getElementById(canvasId);
        if (!ctx) return;
        
        const ctx2d = ctx.getContext('2d');
        const colors = getColorSet(resp.labels.length);
        const data = resp.datasets[0].data;
        
        charts[chartName] = new Chart(ctx2d, {
            type: 'doughnut',
            data: { 
                labels: resp.labels, 
                datasets: [{ 
                    data: data, 
                    backgroundColor: colors,
                    borderWidth: 1,
                    borderColor: 'rgba(255,255,255,0.1)'
                }] 
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                        }
                    }
                }
            }
        });
    }

    /**
     * Renderiza el gráfico de Inventario Combinado.
     */
    function renderInventarioCombinado(resp) {
        if (!resp || !resp.labels || !resp.datasets) {
            console.warn('No hay datos para inventario combinado');
            toggleEmptyMessage('chartInventarioCombinado', true);
            return;
        }
        
        // Verificar si hay datos
        const hasData = resp.labels.length > 0 && 
                       resp.datasets.length > 0 && 
                       resp.datasets.some(dataset => 
                           dataset.data && dataset.data.some(val => val > 0)
                       );
        
        toggleEmptyMessage('chartInventarioCombinado', !hasData);
        
        if (!hasData) {
            destroyChart('inventarioCombinado');
            return;
        }
        
        destroyChart('inventarioCombinado');
        const ctx = document.getElementById('chartInventarioCombinado');
        if (!ctx) return;
        
        const ctx2d = ctx.getContext('2d');
        const colors = getColorSet(resp.datasets.length || 1);

        charts.inventarioCombinado = new Chart(ctx2d, {
            type: 'bar',
            data: {
                labels: resp.labels,
                datasets: resp.datasets.map((d, i) => ({
                    label: d.label || `Dataset ${i+1}`,
                    data: d.data || [],
                    backgroundColor: colors[i % colors.length],
                    borderRadius: 4,
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { 
                    x: { 
                        stacked: true 
                    }, 
                    y: { 
                        stacked: true, 
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' kg';
                            }
                        }
                    } 
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                }
            }
        });
    }
    
    // 5. Carga de Datos AJAX

    /**
     * Muestra/oculta overlay de carga
     */
    function toggleLoading(show) {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = show ? 'flex' : 'none';
        }
    }

    /**
     * Carga y renderiza el Dashboard principal.
     */
    function loadDashboard() {
        const anio = $('#anioGrafico').val();
        
        toggleLoading(true);
        
        $.ajax({
            url: CONFIG.urls.dashboard,
            method: 'GET',
            data: { anio: anio },
            dataType: 'json'
        })
        .done(resp => {
            if (resp && !resp.error) {
                renderKPIs(resp);
                renderMovimientos(resp.ultimos_movimientos || []);
                renderChartMain(resp);
            } else {
                console.error('Error en respuesta del dashboard:', resp?.error);
                showToast('Error al cargar datos del dashboard', 'error');
            }
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            console.error('Error al cargar dashboard:', textStatus, errorThrown);
            showToast('Error de conexión con el servidor', 'error');
        })
        .always(() => {
            toggleLoading(false);
        });
    }

    /**
     * Carga y renderiza el Inventario por Tipo.
     */
    function loadInventarioTipo() {
        const anio = $('#anioInventarioTipoSelect').val();
        
        $.ajax({
            url: CONFIG.urls.inventario,
            method: 'GET',
            data: { anio: anio, tipo: 'tipo' },
            dataType: 'json'
        })
        .done(resp => {
            if (resp && !resp.error) {
                renderInventario(resp, 'chartInventarioTipo', 'inventarioTipo');
            }
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            console.error('Error al cargar inventario tipo:', textStatus, errorThrown);
        });
    }

    /**
     * Carga y renderiza el Inventario por Centro.
     */
    function loadInventarioCentro() {
        const anio = $('#anioInventarioCentroSelect').val();
        
        $.ajax({
            url: CONFIG.urls.inventario,
            method: 'GET',
            data: { anio: anio, tipo: 'centro' },
            dataType: 'json'
        })
        .done(resp => {
            if (resp && !resp.error) {
                renderInventario(resp, 'chartInventarioCentro', 'inventarioCentro');
            }
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            console.error('Error al cargar inventario centro:', textStatus, errorThrown);
        });
    }

    /**
     * Carga y renderiza el Inventario Combinado.
     */
    function loadInventarioCombinado() {
        const anio = $('#anioInventarioCombinado').val();
        
        $.ajax({
            url: CONFIG.urls.inventarioCombinado,
            method: 'GET',
            data: { anio: anio },
            dataType: 'json'
        })
        .done(resp => {
            if (resp && !resp.error) {
                renderInventarioCombinado(resp);
            }
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            console.error('Error al cargar inventario combinado:', textStatus, errorThrown);
        });
    }
    
    /**
     * Carga todos los datos al inicio o al refrescar.
     */
    function loadAllData() {
        toggleLoading(true);
        
        // Usar Promise.all para cargar todo simultáneamente
        Promise.all([
            new Promise(resolve => {
                loadDashboard();
                setTimeout(resolve, 100);
            }),
            new Promise(resolve => {
                loadInventarioTipo();
                setTimeout(resolve, 100);
            }),
            new Promise(resolve => {
                loadInventarioCentro();
                setTimeout(resolve, 100);
            }),
            new Promise(resolve => {
                loadInventarioCombinado();
                setTimeout(resolve, 100);
            })
        ]).finally(() => {
            setTimeout(() => {
                toggleLoading(false);
                showToast('Datos actualizados correctamente', 'success');
            }, 300);
        });
    }

    /**
     * Muestra un toast/notificación
     */
    function showToast(message, type = 'info') {
        // Crear toast dinámicamente
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Agregar al documento
        $('body').append(toastHtml);
        
        // Mostrar toast
        const toastEl = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 3000
        });
        toast.show();
        
        // Remover del DOM después de ocultarse
        toastEl.addEventListener('hidden.bs.toast', function () {
            $(this).remove();
        });
    }

    // 6. Inicialización y Eventos

    /**
     * Configura el manejo de eventos.
     */
    function setupEvents() {
        // Selectores de año
        $('#anioGrafico').on('change', loadDashboard);
        $('#anioInventarioTipoSelect').on('change', loadInventarioTipo);
        $('#anioInventarioCentroSelect').on('change', loadInventarioCentro);
        $('#anioInventarioCombinado').on('change', loadInventarioCombinado);

        // Botón de refrescar
        $('#btnRefresh').on('click', function() {
            const $this = $(this);
            const icon = $this.find('i');
            
            // Agregar efecto visual de carga
            icon.addClass('loading-spinner');
            $this.prop('disabled', true);
            
            loadAllData();
            
            // Quitar el spinner después de un tiempo
            setTimeout(() => {
                icon.removeClass('loading-spinner');
                $this.prop('disabled', false);
            }, 1500);
        });
    }

    /**
     * Configura el modo oscuro persistente.
     */
    function setupDarkMode() {
        const themeStored = localStorage.getItem(CONFIG.THEME_STORAGE_KEY) || '';
        const toggle = $('#toggleDark');
        
        if (themeStored === 'dark') { 
            document.body.dataset.theme = 'dark'; 
            toggle.prop('checked', true); 
        }

        toggle.on('change', function () {
            const t = $(this).is(':checked') ? 'dark' : '';
            document.body.dataset.theme = t;
            localStorage.setItem(CONFIG.THEME_STORAGE_KEY, t);
        });
    }

    /**
     * Punto de entrada principal.
     */
    function init() {
        try {
            initDataTable();
            setupEvents();
            setupDarkMode();
            
            // Cargar datos iniciales con un pequeño delay
            setTimeout(() => {
                loadAllData();
            }, 500);
            
            // Cargar datos cada 5 minutos
            setInterval(loadAllData, 300000);
            
        } catch (error) {
            console.error('Error al inicializar dashboard:', error);
            showToast('Error al inicializar el dashboard', 'error');
        }
    }

    // API pública del módulo
    return {
        init: init,
        reload: loadAllData
    };

})(jQuery);

// Inicializa el dashboard cuando el documento esté listo
$(document).ready(function() {
    DashboardApp.init();
});
</script>

<?= $this->endSection() ?>