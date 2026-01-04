<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 5px solid #2e7d32 !important; background-color: #e8f5e9;">
            <i class="bi bi-check-circle-fill me-2 text-success"></i>
            <strong>¡Logrado!</strong> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
        
        <div class="card-header bg-white py-4 border-bottom border-2 border-orange-subtle">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h3 class="mb-1 fw-bold text-green-dark">
                        <i class="bi bi-building-check me-2 text-orange"></i>Centros de Acopio
                    </h3>
                    <p class="text-muted mb-0 small">Listado y gestión de puntos de recepción de materia prima</p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <a href="<?= site_url('centros/create') ?>" class="btn btn-green shadow-sm px-4 py-2">
                        <i class="bi bi-plus-circle me-1"></i> Registrar Nuevo Centro
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-green-soft">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-green-dark" style="width: 80px;">ID</th>
                            <th class="py-3 text-uppercase small fw-bold text-green-dark">Nombre del Centro</th>
                            <th class="py-3 text-uppercase small fw-bold text-green-dark">Ubicación</th>
                            <th class="py-3 text-uppercase small fw-bold text-green-dark text-center">Estado</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold text-green-dark text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($centros)): ?>
                            <?php foreach($centros as $c): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-muted border">#<?= $c['id'] ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark fs-6"><?= esc($c['nombre']) ?></div>
                                    <small class="text-muted">Centro Operativo</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="bi bi-geo-alt-fill text-orange me-2"></i>
                                        <?= esc($c['ubicacion']) ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if($c['is_active']): ?>
                                        <span class="status-badge status-active">
                                            <i class="bi bi-check2-circle me-1"></i> Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-inactive">
                                            <i class="bi bi-x-circle me-1"></i> Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-center">
                                    <div class="btn-group shadow-sm border rounded-pill overflow-hidden">
                                        <a href="<?= site_url('centros/edit/'.$c['id']) ?>" 
                                           class="btn btn-white btn-sm px-3 text-primary border-0 border-end" 
                                           title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= site_url('centros/delete/'.$c['id']) ?>" 
                                           class="btn btn-white btn-sm px-3 text-danger border-0" 
                                           onclick="return confirm('¿Seguro de eliminar este centro?')"
                                           title="Eliminar">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-building-exclamation fs-1 text-orange opacity-25"></i>
                                        <p class="text-muted mt-2">No se encontraron centros de acopio en la base de datos.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3 border-top-0">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">Total: <strong><?= count($centros) ?></strong> centros registrados</span>
                </div>
        </div>
    </div>
</div>

<style>
    :root {
        --green-dark: #1e3a0f;
        --green-main: #4a7c2f;
        --green-soft: #f8faf7;
        --orange: #d97706;
    }

    .text-green-dark { color: var(--green-dark) !important; }
    .text-orange { color: var(--orange) !important; }
    .bg-green-soft { background-color: var(--green-soft); }
    .border-orange-subtle { border-color: #ffedd5 !important; }

    /* Botón Verde Personalizado */
    .btn-green {
        background-color: var(--green-dark);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-green:hover {
        background-color: var(--green-main);
        color: white;
        transform: translateY(-2px);
    }

    /* Badges de Estado */
    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    .status-active {
        background-color: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }
    .status-inactive {
        background-color: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
    }

    /* Estilo de Tabla */
    .table thead th {
        border-bottom: 0;
        letter-spacing: 0.5px;
    }
    .table tbody tr:hover {
        background-color: #fffbeb; /* Un tono naranja muy suave al pasar el mouse */
    }

    /* Grupo de botones */
    .btn-white {
        background-color: #fff;
    }
    .btn-white:hover {
        background-color: #f8fafc;
    }
</style>

<?= $this->endSection() ?>