<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary-earth: #2d5016;
        --secondary-earth: #4a7c2f;
        --accent-amber: #d97706;
        --soft-bg: #f8faf5;
        --glass-border: rgba(255, 255, 255, 0.4);
    }

    .form-container {
        animation: fadeInScale 0.5s ease-out;
        max-width: 1100px;
        margin: auto;
    }

    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.98) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* Card Estilizada */
    .premium-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .header-gradient {
        background: linear-gradient(135deg, var(--primary-earth), var(--secondary-earth));
        padding: 2.5rem 2rem;
        color: white;
        text-align: center;
        position: relative;
    }

    .header-gradient i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
        opacity: 0.9;
    }

    /* Divisiones de sección */
    .section-label {
        display: flex;
        align-items: center;
        margin: 2rem 0 1.5rem;
        font-weight: 700;
        color: var(--primary-earth);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }

    .section-label::after {
        content: "";
        flex: 1;
        height: 1px;
        background: linear-gradient(to right, #ddd, transparent);
        margin-left: 15px;
    }

    /* Inputs Modernos */
    .input-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-control-modern {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1rem 1rem 3rem;
        transition: all 0.3s;
        background-color: #fff;
    }

    .form-control-modern:focus {
        border-color: var(--secondary-earth);
        box-shadow: 0 0 0 4px rgba(74, 124, 47, 0.1);
        transform: translateY(-1px);
    }

    .input-icon-left {
        position: absolute;
        left: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary-earth);
        font-size: 1.2rem;
        z-index: 5;
    }

    .floating-label {
        position: absolute;
        top: -10px;
        left: 12px;
        background: white;
        padding: 0 8px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--secondary-earth);
        border-radius: 4px;
    }

    /* Widget de Costo Total */
    .total-widget {
        background: linear-gradient(135deg, #fff9f0, #fff);
        border: 2px dashed var(--accent-amber);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s;
    }

    .total-widget label {
        display: block;
        color: var(--accent-amber);
        font-weight: 800;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .total-amount {
        font-size: 2rem;
        font-weight: 900;
        color: #92400e;
        margin: 0;
    }

    /* Botones */
    .btn-glass-secondary {
        background: #f1f5f9;
        color: #475569;
        border-radius: 12px;
        padding: 12px 25px;
        font-weight: 600;
        transition: 0.3s;
        border: none;
    }

    .btn-premium-save {
        background: var(--primary-earth);
        color: white;
        border-radius: 12px;
        padding: 12px 35px;
        font-weight: 600;
        box-shadow: 0 10px 20px rgba(45, 80, 22, 0.2);
        transition: 0.3s;
        border: none;
    }

    .btn-premium-save:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(45, 80, 22, 0.3);
        background: #1e3a0f;
    }
</style>

<div class="container py-5 form-container">
    <div class="premium-card">
        <div class="header-gradient">
            <i class="bi bi-pencil-square"></i>
            <h2 class="mb-0">Edición de Lote</h2>
            <p class="opacity-75 mb-0">Gestión de inventario y costos de entrada</p>
        </div>

        <div class="card-body p-4 p-lg-5">
            <form id="formLoteEntrada">
                <?= csrf_field() ?>

                <div class="section-label">Información de Origen</div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="input-wrapper">
                            <span class="floating-label">Folio de Registro</span>
                            <i class="bi bi-hash input-icon-left"></i>
                            <input type="text" name="folio" id="folio" class="form-control form-control-modern" 
                                   value="<?= esc($lote['folio'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-wrapper">
                            <span class="floating-label">Fecha de Ingreso</span>
                            <i class="bi bi-calendar-check input-icon-left"></i>
                            <input type="date" name="fecha_entrada" id="fecha_entrada" class="form-control form-control-modern" 
                                   value="<?= esc(date('Y-m-d', strtotime($lote['fecha_entrada'] ?? date('Y-m-d')))) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-wrapper">
                            <span class="floating-label">Centro de Acopio</span>
                            <i class="bi bi-geo-alt input-icon-left"></i>
                            <select name="centro_id" id="centro_id" class="form-select form-control-modern" required>
                                <?php foreach ($centros as $centro): ?>
                                    <option value="<?= esc($centro['id']) ?>" <?= ($lote['centro_id'] == $centro['id']) ? 'selected' : '' ?>>
                                        <?= esc($centro['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="section-label">Especificaciones del Producto</div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="input-wrapper">
                            <span class="floating-label">Variedad de Pimienta</span>
                            <i class="bi bi-tags input-icon-left"></i>
                            <select name="tipo_pimienta_id" id="tipo_pimienta_id" class="form-select form-control-modern" required>
                                <?php foreach ($tipos_pimienta as $tipo): ?>
                                    <option value="<?= esc($tipo['id']) ?>" <?= ($lote['tipo_pimienta_id'] == $tipo['id']) ? 'selected' : '' ?>>
                                        <?= esc($tipo['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-wrapper">
                            <span class="floating-label">Nombre del Proveedor / Productor</span>
                            <i class="bi bi-person-badge input-icon-left"></i>
                            <input type="text" name="proveedor" id="proveedor" class="form-control form-control-modern" 
                                   value="<?= esc($lote['proveedor'] ?? '') ?>" placeholder="Ej. Juan Pérez">
                        </div>
                    </div>
                </div>

                <div class="section-label">Liquidación y Pesaje</div>
                <div class="row g-4 align-items-center">
                    <div class="col-md-4">
                        <div class="input-wrapper">
                            <span class="floating-label">Peso Bruto (kg)</span>
                            <i class="bi bi-scaled input-icon-left"></i>
                            <input type="number" step="0.01" name="peso_bruto_kg" id="peso_bruto_kg" 
                                   class="form-control form-control-modern" value="<?= esc($lote['peso_bruto_kg'] ?? 0) ?>" required>
                        </div>
                        <div class="input-wrapper">
                            <span class="floating-label">Precio por Kilo ($)</span>
                            <i class="bi bi-cash input-icon-left"></i>
                            <input type="number" step="0.01" name="precio_compra" id="precio_compra" 
                                   class="form-control form-control-modern" value="<?= esc($lote['precio_compra'] ?? 0) ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="total-widget" id="total-container">
                            <label>COSTO TOTAL DE ADQUISICIÓN</label>
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <span class="total-amount">$</span>
                                <input type="number" id="costo_total" name="costo_total" 
                                       style="background:transparent; border:none; width:200px; outline:none;" 
                                       class="total-amount" readonly value="<?= esc($lote['costo_total'] ?? 0) ?>">
                            </div>
                            <small class="text-muted"><i class="bi bi-info-circle"></i> Este valor se calcula automáticamente</small>
                        </div>
                    </div>
                </div>

                <div class="section-label">Notas Adicionales</div>
                <div class="input-wrapper">
                    <i class="bi bi-chat-right-text input-icon-left" style="top: 25px;"></i>
                    <textarea name="observaciones" id="observaciones" class="form-control form-control-modern" 
                              rows="3" style="padding-top: 15px;"><?= esc($lote['observaciones'] ?? '') ?></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5">
                    <a href="<?= site_url('lotes-entrada') ?>" class="btn btn-glass-secondary">
                        <i class="bi bi-x-circle me-2"></i>Descartar
                    </a>
                    <button type="submit" class="btn btn-premium-save">
                        <i class="bi bi-cloud-check me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const peso = document.getElementById('peso_bruto_kg');
        const precio = document.getElementById('precio_compra');
        const total = document.getElementById('costo_total');
        const totalContainer = document.getElementById('total-container');

        const calcularCosto = () => {
            const p = parseFloat(peso.value) || 0;
            const pr = parseFloat(precio.value) || 0;
            const res = (p * pr).toFixed(2);
            total.value = res;

            // Efecto visual al calcular
            totalContainer.style.transform = 'scale(1.02)';
            totalContainer.style.borderColor = 'var(--secondary-earth)';
            setTimeout(() => {
                totalContainer.style.transform = 'scale(1)';
                totalContainer.style.borderColor = 'var(--accent-amber)';
            }, 300);
        };

        peso.addEventListener('input', calcularCosto);
        precio.addEventListener('input', calcularCosto);

        $('#formLoteEntrada').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            
            Swal.fire({
                title: 'Procesando...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: "<?= site_url('lotes-entrada/update/' . $lote['id']) ?>",
                type: "POST",
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'El lote ha sido actualizado correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "<?= site_url('lotes-entrada') ?>";
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                    }
                },
                error: () => {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Error de comunicación con el servidor.' });
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>