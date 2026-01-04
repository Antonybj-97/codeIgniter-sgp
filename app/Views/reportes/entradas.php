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

<table class="table table-bordered table-striped">
    <thead style="background: linear-gradient(90deg,#28a745,#fd7e14); color:white;">
        <tr>
            <th>Tipo Pimienta</th>
            <th>Total Peso (kg)</th>
            <th>Precio Base</th>
            <th>Total Entradas</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($entradas as $e): ?>
        <tr>
            <td><?= esc($e['tipo_pimienta']) ?></td>
            <td><?= esc($e['total_peso']) ?></td>
            <td><?= esc(number_format($e['precio_base'], 2)) ?></td>
            <td><?= esc($e['total_entradas']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
