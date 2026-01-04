<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --primary-color: #1e3a0f; /* Verde Oscuro */
        --secondary-color: #4a7c2f; /* Verde Hoja */
        --accent-color: #d97706; /* Naranja */
        --bg-body: #f8fafc;
        --text-muted: #64748b;
    }

    .form-container {
        max-width: 650px;
        margin: 3rem auto;
    }

    .card-custom {
        background: #ffffff;
        border-radius: 1.25rem;
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .card-header-accent {
        background: var(--primary-color);
        padding: 2rem;
        text-align: center;
        color: white;
    }

    .card-header-accent h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.5rem;
        letter-spacing: -0.5px;
    }

    .card-body-custom {
        padding: 2.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .input-group-text {
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: var(--secondary-color);
    }

    .form-control, .form-select {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 4px rgba(74, 124, 47, 0.1);
    }

    /* Estilo del Switch de Estado */
    .status-badge-container {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 0.75rem;
        border: 1px dashed #e2e8f0;
    }

    .form-check-input:checked {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }

    .btn-submit {
        background-color: var(--primary-color);
        border: none;
        color: white;
        padding: 0.8rem;
        border-radius: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(30, 58, 15, 0.2);
    }

    .btn-submit:hover {
        background-color: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(30, 58, 15, 0.3);
    }

    .btn-cancel {
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.9rem;
        transition: 0.2s;
    }

    .btn-cancel:hover {
        color: var(--accent-color);
    }
</style>

<div class="form-container">
    <div class="card-custom">
        <div class="card-header-accent">
            <h2><?= isset($user) ? '<i class="bi bi-person-gear"></i> Editar Usuario' : '<i class="bi bi-person-plus"></i> Registrar Nuevo Usuario'; ?></h2>
            <p class="mb-0 mt-2 opacity-75">Configuración de credenciales y estado de cuenta</p>
        </div>

        <div class="card-body-custom">
            <form id="userForm" 
                  action="<?= isset($user) ? site_url('usuario/update/' . $user['id']) : site_url('usuario/store'); ?>" 
                  method="post">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label class="form-label" for="nombre_completo">Nombre Completo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo"
                                   placeholder="Ej. Juan Pérez"
                                   value="<?= old('nombre_completo', $user['nombre_completo'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="email">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="juan@ejemplo.com"
                                   value="<?= old('email', $user['email'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="username">Nombre de Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-at"></i></span>
                            <input type="text" class="form-control" id="username" name="username"
                                   placeholder="jperez"
                                   value="<?= old('username', $user['username'] ?? '') ?>" required>
                        </div>
                    </div>

                    <?php if (!isset($user)): ?>
                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="password">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="confirm_password">Confirmar</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="rol">Rol del Sistema</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-layers"></i></span>
                            <select name="rol" id="rol" class="form-select">
                                <option value="user" <?= isset($user) && $user['rol'] === 'user' ? 'selected' : '' ?>>Acopiador</option>
                                <option value="admin" <?= isset($user) && $user['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Estado de Cuenta</label>
                        <div class="status-badge-container d-flex align-items-center">
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" value="1" 
                                    <?= (!isset($user) || (isset($user) && $user['active'] == 1)) ? 'checked' : '' ?>>
                                <label class="form-check-label ms-2" for="active" id="statusLabel">
                                    <?= (!isset($user) || (isset($user) && $user['active'] == 1)) ? 'Activo' : 'Inactivo' ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-submit w-100 mb-3">
                        <?= isset($user) ? 'Guardar Cambios' : 'Registrar Usuario'; ?>
                    </button>
                    <a href="<?= site_url('usuario') ?>" class="btn-cancel">
                        <i class="bi bi-arrow-left"></i> Regresar al listado
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('userForm');
    const statusSwitch = document.getElementById('active');
    const statusLabel = document.getElementById('statusLabel');

    // Cambiar texto dinámico del switch
    statusSwitch.addEventListener('change', () => {
        statusLabel.textContent = statusSwitch.checked ? 'Activo' : 'Inactivo';
    });

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        // Si el checkbox no está marcado, FormData no lo incluye. 
        // Forzamos el valor 0 si no está checked para que el controlador lo reciba.
        if (!statusSwitch.checked) {
            formData.set('active', '0');
        }

        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
        btn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    html: data.message,
                    confirmButtonColor: '#1e3a0f'
                }).then(() => window.location.href = "<?= site_url('usuario') ?>");
            } else {
                btn.innerHTML = originalText;
                btn.disabled = false;
                let errors = data.errors ? Object.values(data.errors).join('<br>') : data.message;
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo guardar',
                    html: errors,
                    confirmButtonColor: '#d97706'
                });
            }
        })
        .catch(err => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'Hubo un problema al conectar con el servidor.'
            });
        });
    });
});
</script>

<?= $this->endSection(); ?>