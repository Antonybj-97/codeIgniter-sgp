<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary-green: #2d5016;
        --accent-green: #4a7c2f;
        --light-green: #6b9b37;
        --earth-yellow: #d97706;
        --soft-bg: #fdfdfb;
    }

    .form-container {
        animation: fadeInScale 0.5s ease-out;
    }

    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.98) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .premium-card {
        background: var(--card-bg);
        border: none;
        border-radius: 1.25rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    /* Header con Estilo Orgánico */
    .card-header-gradient {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-green) 100%);
        padding: 2.5rem 2rem;
        position: relative;
    }

    .header-title {
        color: white;
        font-size: 1.8rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* Estilización de Secciones */
    .section-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 2.5rem 0 1.5rem;
        color: var(--accent-green);
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 800;
    }

    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, rgba(74, 124, 47, 0.2), transparent);
    }

    /* Inputs Mejorados */
    .form-group-enhanced {
        position: relative;
        margin-bottom: 0.5rem;
    }

    .form-control-enhanced, .form-select-enhanced {
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 1.2rem 1rem 0.5rem; /* Espacio para el label flotante */
        font-size: 1rem;
        height: 3.8rem;
        transition: all 0.2s ease;
        background: white;
    }

    .form-control-enhanced:focus {
        border-color: var(--accent-green);
        box-shadow: 0 0 0 4px rgba(74, 124, 47, 0.1);
        transform: translateY(-1px);
    }

    .form-label-floating {
        position: absolute;
        left: 1.1rem;
        top: 0.6rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--accent-green);
        text-transform: uppercase;
        pointer-events: none;
        transition: all 0.2s ease;
    }

    /* Campo Calculado Especial */
    .calculated-container {
        background: #fffbeb;
        border: 2px solid #fde68a;
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .calculated-container.pulse {
        animation: pulseGold 0.4s ease-in-out;
    }

    @keyframes pulseGold {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); background: #fef3c7; }
        100% { transform: scale(1); }
    }

    .calculated-label {
        font-size: 0.65rem;
        font-weight: 800;
        color: #b45309;
        text-transform: uppercase;
    }

    .calculated-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: #92400e;
        display: block;
    }

    /* Botonera */
    .action-bar {
        background: #f8fafc;
        padding: 1.5rem 2rem;
        border-top: 1px solid #edf2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-custom {
        padding: 0.8rem 1.8rem;
        border-radius: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-save { background: var(--primary-green); color: white; }
    .btn-save:hover { background: #1a330d; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(45,80,22,0.3); }

    .btn-back { background: #e2e8f0; color: #475569; }
    .btn-back:hover { background: #cbd5e1; }
</style>

<div class="container py-5 form-container">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="premium-card">
                <div class="card-header-gradient">
                    <h4 class="header-title">
                        <i class="bi bi-box-seam-fill"></i>
                        Ingreso de Nuevo Lote
                    </h4>
                    <p class="text-white-50 m-0 mt-2">Complete la información técnica y financiera del lote entrante.</p>
                </div>

                <div class="card-body p-0">
                    <form id="formLoteEntrada" class="p-4 p-md-5">
                        <?= csrf_field() ?>

                        <div class="section-divider">
                            <i class="bi bi-shield-check"></i> Control de Inventario
                        </div>

                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="form-group-enhanced">
                                    <label class="form-label-floating">Folio de Registro</label>
                                    <input type="text" name="folio" id="folio" class="form-control-enhanced" placeholder="PIM-000" required value="<?= old('folio') ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-enhanced">
                                    <label class="form-label-floating">Fecha Entrada</label>
                                    <input type="date" name="fecha_entrada" id="fecha_entrada" class="form-control-enhanced" required value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-enhanced">
                                    <label class="form-label-floating">Centro de Acopio</label>
                                    <select name="centro_id" id="centro_id" class="form-select-enhanced" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($centros as $c): ?>
                                            <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider">
                            <i class="bi bi-tags"></i> Clasificación y Origen
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group-enhanced">
                                    <label class="form-label-floating">Tipo de Pimienta</label>
                                    <select name="tipo_pimienta_id" id="tipo_pimienta_id" class="form-select-enhanced" required>
                                        <?php foreach ($tipos_pimienta as $tp): ?>
                                            <option value="<?= $tp['id'] ?>"><?= $tp['nombre'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-enhanced">
                                    <label class="form-label-floating">Modalidad de Entrada</label>
                                    <select name="tipo_entrada_id" id="tipo_entrada_id" class="form-select-enhanced" required>
                                        <?php foreach ($tipos_entrada as $te): ?>
                                            <option value="<?= $te['id'] ?>"><?= $te['nombre'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group-enhanced">
                                    <label class="form-label-floating">Nombre del Proveedor / Productor</label>
                                    <input type="text" name="proveedor" class="form-control-enhanced" placeholder="Nombre completo">
                                </div>
                            </div>
                        </div>

                        <div class="section-divider">
                            <i class="bi bi-currency-exchange"></i> Métricas Financieras
                        </div>

                        <div class="row g-4 align-items-center">
                            <div class="col-md-4">
                                <div class="form-group-enhanced">
                                    <label class="form-label-floating">Peso Bruto (kg)</label>
                                    <input type="number" step="0.01" name="peso_bruto_kg" id="peso_bruto_kg" class="form-control-enhanced" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-enhanced">
                                    <label class="form-label-floating">Precio x Kg ($)</label>
                                    <input type="number" step="0.01" name="precio_compra" id="precio_compra" class="form-control-enhanced" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="calculated-container" id="total_box">
                                    <span class="calculated-label">Costo Total Estimado</span>
                                    <span class="calculated-value">$ <span id="display_total">0.00</span></span>
                                    <input type="hidden" name="costo_total" id="costo_total" value="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="form-group-enhanced mt-4">
                            <label class="form-label-floating">Observaciones Técnicas</label>
                            <textarea name="observaciones" class="form-control-enhanced" style="height: 100px; padding-top: 1.5rem;" placeholder="Detalles sobre la calidad o estado del producto..."></textarea>
                        </div>
                    </form>

                    <div class="action-bar">
                        <a href="<?= site_url('lotes-entrada') ?>" class="btn-custom btn-back">
                            <i class="bi bi-chevron-left"></i> Regresar
                        </a>
                        <div class="d-flex gap-3">
                            <button type="button" id="btnNuevoLote" class="btn-custom" style="background: white; border: 2px solid var(--accent-green); color: var(--accent-green);">
                                <i class="bi bi-plus-circle"></i> Guardar y Continuar
                            </button>
                            <button type="submit" form="formLoteEntrada" class="btn-custom btn-save">
                                <i class="bi bi-check-all"></i> Finalizar Registro
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        const $peso = $('#peso_bruto_kg');
        const $precio = $('#precio_compra');
        const $totalDisplay = $('#display_total');
        const $totalInput = $('#costo_total');
        const $totalBox = $('#total_box');

        function updateCost() {
            const p = parseFloat($peso.val()) || 0;
            const pr = parseFloat($precio.val()) || 0;
            const total = (p * pr).toFixed(2);
            
            $totalDisplay.text(total);
            $totalInput.val(total);

            if (p > 0 && pr > 0) {
                $totalBox.addClass('pulse');
                setTimeout(() => $totalBox.removeClass('pulse'), 400);
            }
        }

        $peso.on('input', updateCost);
        $precio.on('input', updateCost);

        // Envío AJAX
        $('#formLoteEntrada').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this);
            
            $.post("<?= site_url('lotes-entrada/store') ?>", $form.serialize(), function(r) {
                if(r.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Registrado!',
                        text: 'El lote ha sido almacenado correctamente.',
                        confirmButtonColor: '#2d5016'
                    }).then(() => window.location.href = "<?= site_url('lotes-entrada') ?>");
                } else {
                    Swal.fire('Error', 'Verifique los campos obligatorios', 'error');
                }
            }, 'json');
        });
    });
</script>

<?= $this->endSection() ?>