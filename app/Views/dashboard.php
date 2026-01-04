<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
    Dashboard
<?= $this->endSection() ?>

<?= $this->section('section_title') ?>
    Dashboard del Sistema
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Array de nombres de meses en español para la selección del filtro
$meses_espanol = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];

// Validate $mes and $anio
$mes = isset($mes) && is_numeric($mes) && $mes >= 1 && $mes <= 12 ? $mes : date('n');
$anio = isset($anio) && is_numeric($anio) ? $anio : date('Y');
?>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <h1 class="h3 mb-4">Bienvenido, <?= esc($username ?? 'Usuario') ?>!</h1>
                <p class="text-muted">Rol: <?= esc($role ?? 'N/A') ?></p>

                <?php if ($success = session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= esc($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>
                <?php if ($error = session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= esc($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                <form method="get" class="mb-4">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-2">
                            <label for="mes" class="form-label visually-hidden">Mes</label>
                            <select name="mes" id="mes" class="form-select">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= $mes == $i ? 'selected' : '' ?>>
                                        <?= $meses_espanol[$i] ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="anio" class="form-label visually-hidden">Año</label>
                            <select name="anio" id="anio" class="form-select">
                                <?php for ($i = date('Y') - 5; $i <= date('Y') + 1; $i++): ?>
                                    <option value="<?= $i ?>" <?= $anio == $i ? 'selected' : '' ?>>
                                        <?= $i ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>

                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card shadow-sm p-3 text-center">
                            <h5>Entradas del Mes</h5>
                            <p class="fs-3 mb-0"><?= esc($entradas_mes ?? 0) ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm p-3 text-center">
                            <h5>Salidas del Mes</h5>
                            <p class="fs-3 mb-0"><?= esc($salidas_mes ?? 0) ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm p-3 text-center">
                            <h5>Lotes Pendientes</h5>
                            <p class="fs-3 mb-0"><?= esc($lotes_pendientes ?? 0) ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm p-3 text-center">
                            <h5>Total Lotes</h5>
                            <p class="fs-3 mb-0"><?= esc($total_lotes ?? 0) ?></p>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header">
                        <h5>Movimientos del Mes</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="movimientosChart" height="100"></canvas>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header">
                        <h5>Últimos Movimientos</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($ultimos_movimientos ?? [])): ?>
                            <p class="text-muted">No hay movimientos recientes.</p>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($ultimos_movimientos as $movimiento): ?>
                                    <li class="list-group-item">
                                        <span class="fw-bold"><?= esc($movimiento['tipo']) ?></span> -
                                        <?= date('d/m/Y H:i', strtotime($movimiento['fecha'])) ?> -
                                        <?= esc($movimiento['monto']) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('movimientosChart').getContext('2d');

        // Preparar el nombre del mes en español para el título del gráfico
        const meses_espanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        const mes_nombre = meses_espanol[<?= esc($mes) ?> - 1] || 'Mes no válido';
        const anio_actual = <?= esc($anio) ?>;
        const titulo_grafico = `Movimientos del Mes (${mes_nombre} ${anio_actual})`;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Entradas', 'Salidas'],
                datasets: [{
                    label: titulo_grafico,
                    data: [<?= esc($entradas_mes ?? 0) ?>, <?= esc($salidas_mes ?? 0) ?>],
                    backgroundColor: ['rgba(13, 110, 253, 0.7)', 'rgba(220, 53, 69, 0.7)'],
                    borderColor: ['#0d6efd', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Cantidad' }
                    }
                },
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: titulo_grafico
                    }
                }
            }
        });
    </script>
<?= $this->endSection() ?>