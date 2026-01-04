<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Reporte de Entradas<?= $this->endSection() ?>

<?= $this->section('section_title') ?>Entradas Agrupadas por Tipo de Pimienta<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-3">
    <a href="<?= base_url('reportes/entradas-pdf') ?>" class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
    </a>
    <a href="<?= base_url('reportes/entradas-excel') ?>" class="btn btn-success">
        <i class="bi bi-file-earmark-excel"></i> Descargar Excel
    </a>
</div>

<div class="table-responsive shadow-sm rounded">
    <table class="table table-bordered table-striped align-middle">
        <thead style="background: linear-gradient(90deg,#28a745,#fd7e14); color:white;">
            <tr>
                <th>Tipo Pimienta</th>
                <th class="text-end">Total Peso (kg)</th>
                <th class="text-end">Precio Base ($)</th>
                <th class="text-end">Total Entradas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($entradas)): ?>
                <?php foreach ($entradas as $e): ?>
                <tr>
                    <td><?= esc($e['tipo_pimienta']) ?></td>
                    <td class="text-end"><?= esc(number_format($e['total_peso'], 2)) ?></td>
                    <td class="text-end">$<?= esc(number_format($e['precio_base'], 2)) ?></td>
                    <td class="text-end"><?= esc($e['total_entradas']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No hay datos disponibles</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
