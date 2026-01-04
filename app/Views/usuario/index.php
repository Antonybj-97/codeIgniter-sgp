<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?> Usuarios <?= $this->endSection() ?>

<?= $this->section('section_title') ?> Gestión de Usuarios <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        
        <div class="card-header bg-white py-3 border-bottom border-2 border-orange-subtle">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h5 class="mb-0 fw-bold text-green-dark">
                        <i class="bi bi-people-fill me-2"></i>Usuarios del Sistema
                    </h5>
                    <small class="text-muted">Administra los accesos, roles y disponibilidad de la plataforma</small>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <a href="<?= site_url('usuario/create') ?>" class="btn btn-orange shadow-sm text-white">
                        <i class="bi bi-plus-lg me-1"></i> Nuevo Usuario
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="bg-green-light">
                            <th class="text-uppercase small fw-bold text-green-dark" style="width: 50px;">ID</th>
                            <th class="text-uppercase small fw-bold text-green-dark">Usuario</th>
                            <th class="text-uppercase small fw-bold text-green-dark">Contacto</th>
                            <th class="text-uppercase small fw-bold text-green-dark">Rol</th>
                            <th class="text-uppercase small fw-bold text-green-dark text-center">Estado</th>
                            <th class="text-uppercase small fw-bold text-green-dark text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="text-muted small">#<?= esc($user['id']) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3 bg-green-subtle text-green-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; border: 1px solid rgba(74, 124, 47, 0.2);">
                                                <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= esc($user['nombre_completo'] ?? 'Sin nombre') ?></div>
                                                <small class="text-muted">@<?= esc($user['username']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark small">
                                            <i class="bi bi-envelope me-1 text-orange"></i> <?= esc($user['email']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                            $rol = strtolower($user['rol'] ?? 'user');
                                            $badgeStyle = ($rol === 'admin') 
                                                ? 'background-color: #fff3e0; color: #e65100; border: 1px solid #ffe0b2;' 
                                                : 'background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9;';
                                        ?>
                                        <span class="badge rounded-pill px-3 py-2" style="<?= $badgeStyle ?>">
                                            <i class="bi bi-shield-check me-1" style="font-size: 0.7rem;"></i>
                                            <?= ucfirst($rol) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if (isset($user['active']) && $user['active'] == 1): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                                                <i class="bi bi-check-circle-fill me-1"></i> Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill">
                                                <i class="bi bi-x-circle-fill me-1"></i> Inactivo
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm border rounded">
                                            <a href="<?= site_url('usuario/edit/' . $user['id']) ?>" 
                                               class="btn btn-white text-green-dark btn-sm border-0 border-end" 
                                               title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <button type="button" 
                                                    class="btn btn-white text-danger btn-sm border-0" 
                                                    title="Eliminar"
                                                    onclick="confirmDelete(<?= $user['id'] ?>)">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="py-5 text-center text-muted">
                                    <i class="bi bi-person-exclamation fs-1 d-block mb-2 text-orange" style="opacity: 0.5;"></i>
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-light border-0 py-3 text-center text-md-start">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i> 
                Hay <strong><?= count($users) ?></strong> usuario(s) en total.
            </small>
        </div>
    </div>
</div>

<style>
    :root {
        --green-dark: #1e3a0f;
        --green-main: #4a7c2f;
        --green-soft: #f1f8ed;
        --orange-main: #d97706;
        --orange-soft: #fff7ed;
    }

    .text-green-dark { color: var(--green-dark) !important; }
    .text-orange { color: var(--orange-main) !important; }
    .bg-green-light { background-color: var(--green-soft); }
    .bg-green-subtle { background-color: #e8f0e4; }
    .border-orange-subtle { border-color: #ffedd5 !important; }
    
    .btn-orange {
        background-color: var(--orange-main);
        border-color: var(--orange-main);
        transition: all 0.3s ease;
    }
    .btn-orange:hover {
        background-color: #b45309;
        border-color: #b45309;
        transform: translateY(-1px);
        color: white;
    }

    .table thead th {
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        padding-top: 15px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e2e8f0;
    }

    .table-hover tbody tr:hover {
        background-color: var(--orange-soft);
    }

    .card { border-radius: 15px; overflow: hidden; }
    
    .btn-group .btn-white {
        background: white;
        transition: background 0.2s;
    }
    .btn-group .btn-white:hover {
        background: #f8fafc;
    }

    /* Badges de estado específicos */
    .bg-success-subtle { background-color: #dcfce7 !important; }
    .bg-secondary-subtle { background-color: #f1f5f9 !important; }
</style>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar usuario?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e3a0f',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `<?= site_url('usuario/delete/') ?>${id}`;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '<?= csrf_token() ?>';
                csrf.value = '<?= csrf_hash() ?>';
                
                form.appendChild(csrf);
                document.body.appendChild(form);
                
                // Usamos fetch para seguir con la lógica AJAX si prefieres, 
                // o simplemente enviamos el form:
                form.submit();
            }
        });
    }
</script>

<?= $this->endSection() ?>