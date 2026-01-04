<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-4">

    <h2>Editar Centro de Acopio</h2>

    <form action="<?= base_url('admin/centros/update/'.$centro['id']) ?>" method="post">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?= esc($centro['nombre']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicación</label>
            <input type="text" name="ubicacion" id="ubicacion" value="<?= esc($centro['ubicacion']) ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control"><?= esc($centro['descripcion']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="is_active" class="form-label">Activo</label>
            <select name="is_active" id="is_active" class="form-select">
                <option value="1" <?= $centro['is_active'] ? 'selected' : '' ?>>Sí</option>
                <option value="0" <?= !$centro['is_active'] ? 'selected' : '' ?>>No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-warning">Actualizar</button>
        <a href="<?= base_url('centros') ?>" class="btn btn-secondary">Cancelar</a>

    </form>

</div>

<?= $this->endSection() ?>
