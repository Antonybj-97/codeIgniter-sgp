<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Perfil de Usuario</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center border-end">
                    <img src="<?= base_url('images/avatar-placeholder.png') ?>" 
                         class="img-fluid rounded-circle mb-3 shadow-sm" 
                         alt="Avatar" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                    
                    <h4 class="fw-bold"><?= esc($user['nombre_completo']) ?></h4>
                    
                    <?php if ($user['active']): ?>
                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Activo</span>
                    <?php else: ?>
                        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Inactivo</span>
                    <?php endif; ?>
                </div>

                <div class="col-md-8">
                    <table class="table table-hover mt-3">
                        <tbody>
                            <tr>
                                <th style="width: 30%">ID de Usuario:</th>
                                <td><span class="badge bg-light text-dark border">#<?= esc($user['id']) ?></span></td>
                            </tr>
                            <tr>
                                <th>Username:</th>
                                <td class="text-primary fw-bold">@<?= esc($user['username']) ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?= esc($user['email']) ?></td>
                            </tr>
                            <tr>
                                <th>Rol:</th>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <?= strtoupper(esc($user['rol'])) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Seguridad:</th>
                                <td>
                                    <span class="text-muted">••••••••</span>
                                    <a href="<?= base_url('users/change-password') ?>" class="btn btn-sm btn-link">Cambiar clave</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-end">
            <a href="<?= base_url('users') ?>" class="btn btn-secondary">Volver</a>
            <a href="<?= base_url('users/edit/' . $user['id']) ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar Perfil
            </a>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>