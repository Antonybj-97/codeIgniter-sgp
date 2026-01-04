<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Registrar Lote de Entrada</h2>

    <form id="formLoteEntrada">
        <?= csrf_field() ?>

        <!-- Centro de Acopio -->
        <div class="mb-3">
            <label for="centro_id" class="form-label">Centro de Acopio</label>
            <select name="centro_id" id="centro_id" class="form-select" required>
                <option value="">Seleccione un centro</option>
                <?php foreach($centros as $centro): ?>
                    <option value="<?= $centro['id'] ?>" <?= old('centro_id')==$centro['id'] ? 'selected' : '' ?>>
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
                    <option value="<?= $tipo['id'] ?>" <?= old('tipo_pimienta_id')==$tipo['id'] ? 'selected' : '' ?>>
                        <?= esc($tipo['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tipo de Entrada -->
        <div class="mb-3">
            <label for="tipo_entrada_id" class="form-label">Tipo de Entrada</label>
            <select name="tipo_entrada_id" id="tipo_entrada_id" class="form-select" required>
                <option value="">Seleccione un tipo</option>
                <?php foreach($tipos_entrada as $entrada): ?>
                    <option value="<?= $entrada['id'] ?>" <?= old('tipo_entrada_id')==$entrada['id'] ? 'selected' : '' ?>>
                        <?= esc($entrada['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Proveedor opcional -->
        <div class="mb-3">
            <label for="proveedor" class="form-label">Proveedor</label>
            <input type="text" name="proveedor" id="proveedor" class="form-control" value="<?= old('proveedor') ?>">
            <!--<textarea name="proveedor" id="proveedor" class="form-control"><?= old('proveedor') ?></textarea>-->
        </div>


        <!-- Peso -->
        <div class="mb-3">
            <label for="peso_bruto_kg" class="form-label">Peso Bruto (kg)</label>
            <input type="number" step="0.01" name="peso_bruto_kg" id="peso_bruto_kg" class="form-control" required value="<?= old('peso_bruto_kg') ?>">
        </div>

        <!-- Precio -->
        <div class="mb-3">
            <label for="precio_compra" class="form-label">Precio Compra ($/kg)</label>
            <input type="number" step="0.01" name="precio_compra" id="precio_compra" class="form-control" required value="<?= old('precio_compra') ?>">
        </div>

        <!-- Costo total -->
        <div class="mb-3">
            <label for="costo_total" class="form-label">Costo Total ($)</label>
            <input type="number" step="0.01" name="costo_total" id="costo_total" class="form-control" readonly value="<?= old('costo_total') ?>">
        </div>

        <!-- Observaciones -->
        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea name="observaciones" id="observaciones" class="form-control"><?= old('observaciones') ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
</div>

<!-- jQuery y SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pesoInput = document.getElementById('peso_bruto_kg');
        const precioInput = document.getElementById('precio_compra');
        const costoTotalInput = document.getElementById('costo_total');

        const calcularCosto = () => {
            const peso = parseFloat(pesoInput.value) || 0;
            const precio = parseFloat(precioInput.value) || 0;
            costoTotalInput.value = (peso * precio).toFixed(2);
        };

        pesoInput.addEventListener('input', calcularCosto);
        precioInput.addEventListener('input', calcularCosto);
        calcularCosto();

        // AJAX para enviar formulario
        $('#formLoteEntrada').on('submit', function(e){
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: "<?= base_url('lote-entrada/store') ?>",
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response){
                    if(response.success){
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "<?= base_url('lote-entrada') ?>";
                        });
                    } else {
                        if(response.data){
                            let lista = Object.values(response.data).map(e => `• ${e}`).join('<br>');
                            Swal.fire({
                                icon: 'error',
                                title: 'Errores de validación',
                                html: lista
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }
                },
                error: function(){
                    Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
