<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-3">Editar Proceso #<?= $proceso['id'] ?></h2>

    <a href="<?= base_url('proceso-pimienta') ?>" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Volver
    </a>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?= base_url('procesos/saveUpdate/' . $proceso['id']) ?>" method="POST">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="tipo_proceso" class="form-label">Tipo de Proceso</label>
                    <select class="form-select" id="tipo_proceso" name="tipo_proceso" required>
                        <option value="">Selecciona</option>
                        <option value="Secado" <?= $proceso['tipo_proceso'] === 'Secado' ? 'selected' : '' ?>>Secado</option>
                        <option value="Empacado" <?= $proceso['tipo_proceso'] === 'Empacado' ? 'selected' : '' ?>>Empacado</option>
                        <option value="Otro" <?= $proceso['tipo_proceso'] === 'Otro' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="peso_salida_kg" class="form-label">Peso de Salida (kg)</label>
                    <input type="number" step="0.01" class="form-control" id="peso_salida_kg" name="peso_salida_kg" 
                           value="<?= old('peso_salida_kg', $proceso['peso_salida_kg']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?= old('observaciones', $proceso['observaciones']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
