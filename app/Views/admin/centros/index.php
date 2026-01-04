<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-4">

    <h2>Centros de Acopio</h2>

    <a href="<?= base_url('admin/centros/create') ?>" class="btn btn-success mb-3">+ Nuevo Centro</a>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($centros)): ?>
                <?php foreach($centros as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= esc($c['nombre']) ?></td>
                    <td><?= esc($c['ubicacion']) ?></td>
                    <td><?= $c['is_active'] ? 'Sí' : 'No' ?></td>
                    <td>
                        <a href="<?= base_url('admin/centros/edit/'.$c['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                        <form action="<?= base_url('admin/centros/delete/'.$c['id']) ?>" method="post" style="display:inline-block;" onsubmit="return confirm('¿Seguro de eliminar?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No hay centros registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?= $this->endSection() ?>
