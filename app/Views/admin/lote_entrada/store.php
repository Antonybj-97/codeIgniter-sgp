<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Registrar Lote de Salida</h2>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('lote-salida/store') ?>" method="post" id="formLoteSalida">
        <?= csrf_field() ?>

        <!-- Centro de Acopio -->
        <div class="mb-3">
            <label for="centro_id" class="form-label">Centro de Acopio</label>
            <select name="centro_id" id="centro_id" class="form-select" required>
                <option value="">Seleccione un centro</option>
                <?php foreach($centros as $centro): ?>
                    <option value="<?= $centro['id'] ?>" <?= set_select('centro_id', $centro['id']) ?>>
                        <?= esc($centro['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tipo Pimienta -->
        <div class="mb-3">
            <label for="tipo_pimienta_id" class="form-label">Tipo de Pimienta</label>
            <select name="tipo_pimienta_id" id="tipo_pimienta_id" class="form-select" required>
                <option value="">Seleccione un tipo</option>
                <?php foreach($tipos_pimienta as $tipo): ?>
                    <option value="<?= $tipo['id'] ?>" <?= set_select('tipo_pimienta_id', $tipo['id']) ?>>
                        <?= esc($tipo['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tipo de Salida -->
        <div class="mb-3">
            <label for="tipo_salida_id" class="form-label">Tipo de Salida</label>
            <select name="tipo_salida_id" id="tipo_salida_id" class="form-select" required>
                <option value="">Seleccione un tipo</option>
                <?php foreach($tipos_salida as $salida): ?>
                    <option value="<?= $salida['id'] ?>" <?= set_select('tipo_salida_id', $salida['id']) ?>>
                        <?= esc($salida['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Destino / Cliente -->
        <div class="mb-3">
            <label for="destino" class="form-label">Destino / Cliente</label>
            <input type="text" name="destino" id="destino" class="form-control" value="<?= set_value('destino') ?>">
        </div>

        <!-- Peso de Salida -->
        <div class="mb-3">
            <label for="peso_salida_kg" class="form-label">Peso de Salida (kg)</label>
            <input type="number" step="0.01" name="peso_salida_kg" id="peso_salida_kg" class="form-control" required
                   value="<?= set_value('peso_salida_kg') ?>">
        </div>

        <!-- Observaciones -->
        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea name="observaciones" id="observaciones" class="form-control"><?= set_value('observaciones') ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const pesoInput = document.getElementById('peso_salida_kg');

    // Validación de stock disponible usando API
    const validarPesoDisponible = async () => {
        const peso = parseFloat(pesoInput.value) || 0;
        const centroId = document.getElementById('centro_id').value;
        const tipoPimientaId = document.getElementById('tipo_pimienta_id').value;

        if (!centroId || !tipoPimientaId) return;

        try {
            const response = await fetch(`<?= base_url('lote-entrada/pesoDisponible') ?>/${tipoPimientaId}`);
            const data = await response.json();

            if (data.success && peso > data.data.peso_disponible) {
                alert(`El peso ingresado supera el stock disponible (${data.data.peso_disponible} kg)`);
                pesoInput.value = data.data.peso_disponible;
            }
        } catch (error) {
            console.error('Error al consultar stock disponible:', error);
        }
    };

    pesoInput.addEventListener('input', validarPesoDisponible);
});
</script>

<?= $this->endSection() ?>
