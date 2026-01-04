<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="<?= base_url('centros') ?>" class="text-green-dark text-decoration-none">Centros</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nuevo Registro</li>
                </ol>
            </nav>

            <div class="card shadow-lg border-0" style="border-radius: 1.25rem; overflow: hidden;">
                
                <div class="card-header py-4 text-center border-0" style="background: linear-gradient(135deg, #1e3a0f 0%, #4a7c2f 100%); color: white;">
                    <div class="display-6 mb-2">
                        <i class="bi bi-plus-circle-dotted text-orange"></i>
                    </div>
                    <h3 class="fw-bold mb-0">Nuevo Centro de Acopio</h3>
                    <p class="small opacity-75 mb-0">Registra un nuevo punto logístico en el sistema</p>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="<?= base_url('centros/store') ?>" method="post" id="formNuevoCentro">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="nombre" class="form-label fw-bold text-green-dark">
                                <i class="bi bi-card-heading me-1 text-orange"></i> Nombre Oficial
                            </label>
                            <input type="text" name="nombre" id="nombre" 
                                   class="form-control form-control-lg border-2 shadow-sm" 
                                   placeholder="Ej: Acopio Central del Valle" required>
                        </div>

                        <div class="mb-4">
                            <label for="ubicacion" class="form-label fw-bold text-green-dark">
                                <i class="bi bi-geo-alt-fill me-1 text-orange"></i> Dirección o Referencia
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-2 border-end-0 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="ubicacion" id="ubicacion" 
                                       class="form-control border-2 border-start-0 ps-0 shadow-sm" 
                                       placeholder="Calle, Ciudad, Estado...">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label fw-bold text-green-dark">
                                <i class="bi bi-file-earmark-text-fill me-1 text-orange"></i> Notas Adicionales
                            </label>
                            <textarea name="descripcion" id="descripcion" rows="3" 
                                      class="form-control border-2 shadow-sm" 
                                      placeholder="Capacidad máxima, horarios de recepción, etc."></textarea>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold d-block text-green-dark mb-2">Estado Inicial</label>
                            <div class="bg-light p-3 rounded-3 border d-flex align-items-center">
                                <div class="form-check form-switch ms-2">
                                    <input class="form-check-input custom-switch" type="checkbox" name="is_active" id="is_active" value="1" checked>
                                    <label class="form-check-label fw-semibold ms-2" for="is_active" id="statusLabel">Operativo (Habilitado)</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between pt-3 border-top">
                            <a href="<?= base_url('centros') ?>" class="btn btn-light px-4 py-2 fw-bold text-muted border">
                                <i class="bi bi-arrow-left me-1"></i> Regresar
                            </a>
                            <button type="submit" class="btn btn-orange-main px-5 py-2 fw-bold text-white shadow">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Confirmar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --green-dark: #1e3a0f;
        --green-mid: #4a7c2f;
        --orange: #d97706;
        --bg-soft: #fcfdfc;
    }

    body { background-color: #f4f7f4; }
    .text-green-dark { color: var(--green-dark); }
    .text-orange { color: var(--orange) !important; }

    /* Inputs */
    .form-control, .input-group-text {
        border-radius: 0.75rem;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: var(--green-mid);
        box-shadow: 0 0 0 0.25rem rgba(74, 124, 47, 0.1);
    }

    /* Switch Personalizado */
    .custom-switch {
        width: 3em !important;
        height: 1.5em !important;
        cursor: pointer;
    }
    .custom-switch:checked {
        background-color: var(--green-mid);
        border-color: var(--green-mid);
    }

    /* Botón Naranja */
    .btn-orange-main {
        background-color: var(--orange);
        border: none;
        border-radius: 0.75rem;
        transition: all 0.3s;
    }
    .btn-orange-main:hover {
        background-color: #b45309;
        transform: scale(1.02);
    }

    .card { animation: slideUp 0.4s ease-out; }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    // Dinamismo para el label del switch
    const switchEl = document.getElementById('is_active');
    const labelEl = document.getElementById('statusLabel');
    
    switchEl.addEventListener('change', function() {
        if(this.checked) {
            labelEl.innerText = "Operativo (Habilitado)";
            labelEl.classList.replace('text-danger', 'text-dark');
        } else {
            labelEl.innerText = "Inactivo (Deshabilitado)";
            labelEl.classList.add('text-danger');
        }
    });
</script>

<?= $this->endSection() ?>