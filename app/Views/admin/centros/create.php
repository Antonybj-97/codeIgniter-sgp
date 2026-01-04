<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-4">

    <h2>Nuevo Centro de Acopio</h2>

    <form action="<?= base_url('centros/store') ?>" method="post">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicación</label>
            <input type="text" name="ubicacion" id="ubicacion" class="form-control">
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="is_active" class="form-label">Activo</label>
            <select name="is_active" id="is_active" class="form-select">
                <option value="1" selected>Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="<?= base_url('centros') ?>" class="btn btn-secondary">Cancelar</a>

    </form>

</div>

<?= $this->endSection() ?>
