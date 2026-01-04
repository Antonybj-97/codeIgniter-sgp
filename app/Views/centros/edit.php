<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="card shadow border-0" style="border-radius: 1.25rem; overflow: hidden;">
                
                <div class="card-header py-4 text-center" style="background-color: var(--green-dark, #1e3a0f); color: white;">
                    <i class="bi bi-pencil-square fs-1 mb-2 d-block text-orange"></i>
                    <h3 class="fw-bold mb-0">Editar Centro de Acopio</h3>
                    <p class="small opacity-75 mb-0">Actualiza los datos operativos del punto de recepción</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="<?= base_url('centros/update/'.$centro['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="nombre" class="form-label fw-bold text-green-dark">
                                <i class="bi bi-tag-fill me-1 text-orange"></i> Nombre del Centro
                            </label>
                            <input type="text" name="nombre" id="nombre" 
                                   value="<?= esc($centro['nombre']) ?>" 
                                   class="form-control form-control-lg border-2" 
                                   placeholder="Ej: Centro de Acopio Norte" required>
                        </div>

                        <div class="mb-4">
                            <label for="ubicacion" class="form-label fw-bold text-green-dark">
                                <i class="bi bi-geo-alt-fill me-1 text-orange"></i> Ubicación Geográfica
                            </label>
                            <input type="text" name="ubicacion" id="ubicacion" 
                                   value="<?= esc($centro['ubicacion']) ?>" 
                                   class="form-control border-2" 
                                   placeholder="Calle, ciudad o coordenadas">
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label fw-bold text-green-dark">
                                <i class="bi bi-info-circle-fill me-1 text-orange"></i> Descripción / Observaciones
                            </label>
                            <textarea name="descripcion" id="descripcion" rows="3" 
                                      class="form-control border-2" 
                                      placeholder="Detalles adicionales sobre el centro..."><?= esc($centro['descripcion']) ?></textarea>
                        </div>

                        <div class="mb-5 p-3 rounded-3 bg-light border border-dashed text-center">
                            <label class="form-label fw-bold d-block text-green-dark mb-3">Estado Operativo</label>
                            <div class="d-flex justify-content-center gap-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input custom-radio-green" type="radio" name="is_active" id="active_on" value="1" <?= $centro['is_active'] ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold" for="active_on">Activo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input custom-radio-orange" type="radio" name="is_active" id="active_off" value="0" <?= !$centro['is_active'] ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold" for="active_off">Inactivo</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <a href="<?= base_url('centros') ?>" class="btn btn-outline-secondary w-100 py-2 fw-bold">
                                    <i class="bi bi-x-lg me-1"></i> Cancelar
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-orange-main w-100 py-2 fw-bold text-white shadow-sm">
                                    <i class="bi bi-save me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <p class="text-center text-muted mt-4 small">
                ID del Registro: <span class="badge bg-secondary opacity-50">#<?= $centro['id'] ?></span>
            </p>
        </div>
    </div>
</div>

<style>
    :root {
        --green-dark: #1e3a0f;
        --orange: #d97706;
    }

    .text-green-dark { color: var(--green-dark); }
    .text-orange { color: var(--orange); }

    /* Estilo de los inputs */
    .form-control:focus, .form-select:focus {
        border-color: var(--orange);
        box-shadow: 0 0 0 0.25rem rgba(217, 119, 6, 0.15);
    }

    .form-control {
        border-radius: 0.6rem;
        background-color: #fcfdfc;
    }

    /* Botón Naranja Personalizado */
    .btn-orange-main {
        background-color: var(--orange);
        border: none;
        transition: all 0.3s;
    }
    .btn-orange-main:hover {
        background-color: #b45309;
        transform: translateY(-2px);
    }

    /* Estilo de Radio Buttons */
    .custom-radio-green:checked {
        background-color: #2e7d32;
        border-color: #2e7d32;
    }
    .custom-radio-orange:checked {
        background-color: #e11d48; /* Color rojo/naranja para inactivo */
        border-color: #e11d48;
    }

    .card {
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<?= $this->endSection() ?>