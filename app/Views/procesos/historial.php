<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Historial del Lote<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">

    <h3 class="text-center mb-4">
        📦 Historial de Procesos del Lote <?= esc($loteId) ?>
    </h3>

    <!-- Datos del lote -->
    <?php if (!empty($lote)): ?>
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">

                <h5><strong>Proveedor:</strong> <?= esc($lote['proveedor'] ?? 'Desconocido') ?></h5>
                <p><strong>Centro:</strong> <?= esc($lote['centro_nombre'] ?? 'No asignado') ?></p>
                <p><strong>Tipo de Pimienta:</strong> <?= esc($lote['tipo_pimienta'] ?? 'Desconocido') ?></p>

                <!-- Barra de progreso -->
                <?php if (isset($porcentaje)): ?>
                    <div class="progress mt-3" style="height: 25px;">
                        <div class="progress-bar bg-success fw-bold" role="progressbar"
                            style="width: <?= $porcentaje ?>%;">
                            <?= $porcentaje ?>%
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    <?php endif; ?>

    <!-- Mensaje de advertencia -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-warning text-center shadow-sm">
            <?= esc($mensaje) ?>
        </div>
    <?php endif; ?>

    <!-- Tabla -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tipo de Proceso</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Peso Bruto (kg)</th>
                        <th>Peso Estimado (kg)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($procesos)): ?>
                        <?php foreach ($procesos as $i => $proceso): ?>

                            <?php
                                $estado = $proceso['estado_proceso'];
                                $color = match ($estado) {
                                    'Pendiente'  => 'secondary',
                                    'Iniciado'   => 'warning',
                                    'Finalizado' => 'success',
                                    default      => 'dark'
                                };
                            ?>

                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($proceso['tipo_proceso']) ?></td>

                                <td><?= date('d/m/Y H:i', strtotime($proceso['fecha_proceso'])) ?></td>

                                <td>
                                    <span class="badge bg-<?= $color ?> fw-bold">
                                        <?= esc($estado) ?>
                                    </span>
                                </td>

                                <td><?= number_format($proceso['peso_bruto_kg'] ?? 0, 2) ?></td>
                                <td><?= number_format($proceso['peso_estimado_kg'] ?? 0, 2) ?></td>

                                <td class="d-flex justify-content-center gap-2">

                                    <a href="<?= site_url('procesos/pdf/' . $proceso['id']) ?>"
                                       class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>

                                    <?php if ($estado === 'Pendiente'): ?>
                                        <a href="<?= site_url('procesos/iniciar/' . $proceso['id']) ?>"
                                           class="btn btn-success btn-sm">
                                            Iniciar
                                        </a>
                                    <?php elseif ($estado === 'Iniciado'): ?>
                                        <a href="<?= site_url('procesos/finalizar/' . $proceso['id']) ?>"
                                           class="btn btn-primary btn-sm">
                                            Finalizar
                                        </a>
                                    <?php endif; ?>

                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="py-3 text-center">
                                No hay registros de procesos para este lote.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
