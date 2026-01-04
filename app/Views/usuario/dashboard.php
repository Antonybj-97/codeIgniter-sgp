<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard Usuario - Sistema de Gestión de Pimienta<?= $this->endSection() ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
:root {
    --green-light: #4CAF50;
    --green-dark: #1B5E20;
    --orange: #FF9800;
    --card-bg: #ffffff;
    --shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.card-kpi {
    background: var(--card-bg);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: var(--shadow);
    color: #fff;
    text-align: center;
    border: none;
    transition: transform 0.3s ease;
}

.card-kpi:hover { transform: translateY(-5px); }

.card-kpi h6 { font-weight: 600; margin-bottom: .5rem; opacity: 0.9; text-transform: uppercase; font-size: 0.8rem; }
.card-kpi h3 { font-size: 1.8rem; font-weight: bold; margin: 0; }

.bg-green { background-color: var(--green-light) !important; }
.bg-orange { background-color: var(--orange) !important; }
.bg-darkgreen { background-color: var(--green-dark) !important; }

.table thead { background-color: var(--green-dark); color: #fff; }
.badge-entrada { background-color: var(--green-light); color: white; }
.badge-salida { background-color: var(--orange); color: white; }
</style>

<div class="container-fluid mt-4">

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card-kpi bg-green">
                <h6>Entradas este mes</h6>
                <h3 id="kpi-entradas">0.00 kg</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-kpi bg-orange">
                <h6>Salidas este mes</h6>
                <h3 id="kpi-salidas">0.00 kg</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-kpi bg-darkgreen">
                <h6>Lotes en Proceso</h6>
                <h3 id="kpi-pendientes">0</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-kpi bg-secondary">
                <h6>Total Histórico</h6>
                <h3 id="kpi-total">0.00 kg</h3>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="bi bi-bar-chart me-2"></i>Rendimiento Mensual</h6>
                    <select id="anioGrafico" class="form-select form-select-sm w-auto">
                        <?php for($y=date('Y'); $y>=date('Y')-4; $y--): ?>
                            <option value="<?= $y ?>"><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="card-body">
                    <div style="position: relative; height:300px;">
                        <canvas id="chartEntradasSalidas"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="bi bi-info-circle me-2"></i>Resumen de Centro</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Visualización rápida de los últimos movimientos registrados en su terminal.</p>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Estado del Servidor:</span>
                        <span class="badge bg-success">En línea</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Última Sincronización:</span>
                        <span class="text-muted small"><?= date('H:i:s') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4 mb-5">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-dark"><i class="bi bi-clock-history me-2"></i>Últimos Movimientos</h6>
        </div>
        <div class="card-body">
            <table class="table table-hover w-100" id="tblMovimientos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Peso (kg)</th>
                        <th>Productor/Cliente</th>
                        <th>Referencia</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/1.9.3/countUp.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
let chartES = null;
let dataTableMov = null;

function actualizarDashboard() {
    let anio = $('#anioGrafico').val();

    $.getJSON('<?= site_url('usuario/dashboard-ajax') ?>', {anio}, function(data) {
        
        // --- 1. KPIs con CountUp ---
        // (Asegurarse de usar la sintaxis correcta según la versión cargada)
        const options = { decimalPlaces: 2, suffix: ' kg' };
        
        new CountUp('kpi-entradas', 0, data.entradas_mes || 0, 2, 1.5, options).start();
        new CountUp('kpi-salidas', 0, data.salidas_mes || 0, 2, 1.5, options).start();
        new CountUp('kpi-pendientes', 0, data.lotes_pendientes || 0, 0, 1.5).start();
        new CountUp('kpi-total', 0, data.total_lotes || 0, 2, 1.5, options).start();

        // --- 2. Gráfico Chart.js ---
        if(chartES) chartES.destroy();
        const ctx = document.getElementById('chartEntradasSalidas').getContext('2d');
        chartES = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.meses,
                datasets: [
                    {
                        label: 'Entradas',
                        data: data.entradas,
                        backgroundColor: '#4CAF50',
                        borderRadius: 5
                    },
                    {
                        label: 'Salidas',
                        data: data.salidas,
                        backgroundColor: '#FF9800',
                        borderRadius: 5
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // --- 3. Tabla de Movimientos ---
        if(dataTableMov) {
            dataTableMov.clear().destroy();
        }

        let rows = '';
        data.ultimos_movimientos.forEach(mov => {
            const badge = mov.tipo.toLowerCase() === 'entrada' ? 'badge-entrada' : 'badge-salida';
            rows += `<tr>
                <td>${mov.fecha}</td>
                <td><span class="badge ${badge}">${mov.tipo.toUpperCase()}</span></td>
                <td class="fw-bold">${parseFloat(mov.peso).toFixed(2)} kg</td>
                <td>${mov.cliente}</td>
                <td class="text-muted small">${mov.referencia || 'N/A'}</td>
            </tr>`;
        });
        
        $('#tblMovimientos tbody').html(rows);
        dataTableMov = $('#tblMovimientos').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            order: [[0, 'desc']],
            pageLength: 5,
            lengthMenu: [5, 10, 25]
        });
    });
}

$(document).ready(function() {
    actualizarDashboard();
    
    $('#anioGrafico').on('change', function() {
        actualizarDashboard();
    });

    // Actualización automática suave cada 60 segundos
    setInterval(actualizarDashboard, 60000);
});
</script>

<?= $this->endSection() ?>