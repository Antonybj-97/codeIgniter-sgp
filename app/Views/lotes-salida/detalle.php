<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
    Detalle Lote de Salida - <?= esc($lote['id'] ?? 'N/A') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-4">

        <!-- HEADER -->
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h3 class="mb-0 text-center">
                <i class="fas fa-boxes me-2"></i>
                Detalle del Lote de Salida #<?= esc($lote['id'] ?? 'N/A') ?>
            </h3>
        </div>

        <div class="card-body p-4">

            <?php if (empty($lote)): ?>
                <div class="alert alert-warning text-center">
                    No se encontró el detalle del lote solicitado.
                </div>
            <?php else: ?>

                <!-- DATOS PRINCIPALES -->
                <div class="row mb-4">
                    <div class="col-md-6">

                        <p class="mb-2">
                            <strong>Lote Entrada Asociado:</strong>
                            <?= esc($lote['lote_entrada_id'] ?? 'N/A') ?>
                            <span class="badge bg-secondary">
                                <?= esc($lote['peso_entrada'] ?? 0) ?> kg
                            </span>
                        </p>

                        <p class="mb-2">
                            <strong>Tipo de Proceso:</strong>
                            <?= esc($lote['tipo_proceso'] ?? 'N/A') ?>
                        </p>

                        <p class="mb-2">
                            <strong>Peso Neto de Salida:</strong>
                            <span class="text-success fw-bold">
                                <?= esc($lote['peso_neto_kg'] ?? 0) ?> kg
                            </span>
                        </p>

                        <p class="mb-2">
                            <strong>Fecha de Salida:</strong>
                            <?= esc($lote['fecha_salida'] ?? 'N/A') ?>
                        </p>

                    </div>

                    <div class="col-md-6">

                        <p class="mb-2">
                            <strong>Cliente Destino:</strong>
                            <?= esc($lote['cliente'] ?? 'N/A') ?>
                        </p>

                        <p class="mb-2">
                            <strong>Estado Actual:</strong>

                            <?php 
                                $estado = strtoupper($lote['estado'] ?? '');
                                $statusClass = [
                                    'COMPLETADO' => 'bg-success',
                                    'PENDIENTE'  => 'bg-warning text-dark',
                                    'CANCELADO'  => 'bg-danger'
                                ][$estado] ?? 'bg-info';
                            ?>

                            <span class="badge <?= $statusClass ?>">
                                <?= esc($lote['estado'] ?? 'Desconocido') ?>
                            </span>
                        </p>

                    </div>
                </div>

                <!-- OBSERVACIONES -->
                <div class="mb-4">
                    <h5>Observaciones</h5>
                    <div class="p-3 bg-light border rounded">
                        <p class="mb-0 text-muted">
                            <?= nl2br(esc($lote['observaciones'] ?? 'Sin observaciones.')) ?>
                        </p>
                    </div>
                </div>

                <hr>

                <!-- BOTONES -->
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('lotes-salida') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver a Lotes
                    </a>

                    <?php if (!empty($lote['id'])): ?>
                        <a href="<?= site_url('lotes-salida/editar/' . esc($lote['id'])) ?>" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>
                            Editar Lote
                        </a>
                    <?php endif; ?>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>
<?= $this->endSection() ?>
