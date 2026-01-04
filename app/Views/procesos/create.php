<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4 text-center">🧾 Crear Proceso para Lote #<?= esc($lote['id'] ?? 'N/A') ?></h2>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <h5 class="fw-bold mb-2">⚠️ Corrige los siguientes errores:</h5>
            <ul class="mb-0 ps-3">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('procesos/store') ?>" method="post" class="card shadow-sm p-4 border-0">
        <?= csrf_field() ?>

        <input type="hidden" name="lote_entrada_id" value="<?= esc($lote['id'] ?? '') ?>">

        <!-- Tipo de proceso -->
        <div class="mb-3">
            <label for="tipo_proceso" class="form-label fw-bold">
                Tipo de Proceso <span class="text-danger">*</span>
            </label>
            <input
                type="text"
                name="tipo_proceso"
                id="tipo_proceso"
                class="form-control <?= isset(session()->getFlashdata('errors')['tipo_proceso']) ? 'is-invalid' : '' ?>"
                value="<?= old('tipo_proceso') ?>"
                placeholder="Ej. Secado, Molido"
                required
            >
        </div>

        <!-- Responsable -->
        <div class="mb-3">
            <label for="responsable" class="form-label fw-bold">Responsable (opcional)</label>
            <input
                type="text"
                name="responsable"
                id="responsable"
                class="form-control <?= isset(session()->getFlashdata('errors')['responsable']) ? 'is-invalid' : '' ?>"
                value="<?= old('responsable') ?>"
                placeholder="Nombre del encargado del proceso (opcional)"
            >
        </div>

        <!-- Observaciones -->
        <div class="mb-3">
            <label for="observaciones" class="form-label fw-bold">Observaciones</label>
            <textarea
                name="observaciones"
                id="observaciones"
                class="form-control <?= isset(session()->getFlashdata('errors')['observaciones']) ? 'is-invalid' : '' ?>"
                rows="3"
                placeholder="Anota observaciones o detalles importantes"><?= old('observaciones') ?></textarea>
        </div>

        <!-- Datos del lote -->
        <div class="mb-4">
            <label class="form-label fw-bold">📦 Datos del Lote</label>
            <ul class="list-group list-group-flush border rounded">
                <li class="list-group-item"><strong>Proveedor:</strong> <?= esc($lote['proveedor'] ?? 'No especificado') ?></li>
                <li class="list-group-item"><strong>Peso Bruto:</strong> <?= esc($lote['peso_bruto_kg'] ?? '0') ?> kg</li>
                <?php if (!empty($lote['precio_compra'])): ?>
                    <li class="list-group-item"><strong>Precio Compra:</strong> $<?= number_format($lote['precio_compra'], 2) ?></li>
                <?php endif; ?>
                <li class="list-group-item"><strong>Fecha de Entrada:</strong> <?= esc($lote['fecha_entrada'] ?? 'N/D') ?></li>
            </ul>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-between">
            <a href="<?= site_url('lotes-entrada') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Volver
            </a>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Guardar Proceso
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
