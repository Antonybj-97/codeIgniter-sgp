<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso | SGP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2d6a2d;
            --bg: linear-gradient(135deg, #e8f5e9, #f1f8e9);
            --glass: rgba(255,255,255,.92);
        }

        body {
            min-height: 100vh;
            background: var(--bg);
            font-family: "Segoe UI", system-ui, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* CONTENEDOR PRINCIPAL */
        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 900px;
            background: var(--glass);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,.2);
            animation: fadeIn .6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* PANEL IZQUIERDO */
        .login-image {
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .login-image img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .login-image::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                rgba(0,0,0,.45),
                rgba(0,0,0,.65)
            );
            z-index: 1;
        }

        .login-image h2 {
            position: absolute;
            bottom: 30px;
            left: 30px;
            color: #fff;
            font-weight: 700;
            line-height: 1.2;
            z-index: 2;
        }

        /* PANEL DERECHO */
        .login-form {
            flex: 1;
            padding: 3rem;
        }

        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand img {
            width: 80px;
            margin-bottom: .8rem;
        }

        .brand h4 {
            font-weight: 700;
            color: var(--primary);
        }

        .form-label {
            font-weight: 600;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
        }

        .input-group-text {
            background: transparent;
            border-radius: 12px 0 0 12px;
        }

        .btn-login {
            background: var(--primary);
            border: none;
            border-radius: 14px;
            padding: 12px;
            font-weight: 600;
            transition: all .3s ease;
        }

        .btn-login:hover {
            background: #245824;
            transform: translateY(-1px);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }
            .login-image {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- IMAGEN -->
    <div class="login-image">
        <img src="<?= site_url('assets/img/login.jpg') ?>" alt="Acceso SGP">
        <h2>Sistema de Gestión<br>de Pimienta</h2>
    </div>

    <!-- FORMULARIO -->
    <div class="login-form">
        <div class="brand">
            <img src="<?= site_url('assets/img/user.png') ?>" alt="Usuario">
            <h4>Acceso al Sistema</h4>
            <small class="text-muted">Ingrese sus credenciales</small>
        </div>

        <div id="alert-container"></div>

        <form id="loginForm" novalidate>

            <div class="mb-3">
                <label class="form-label">Usuario o correo</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100 text-white">
                <i class="bi bi-box-arrow-in-right"></i> Ingresar
            </button>
        </form>
    </div>
</div>

<!-- SCRIPT -->
<script>
(() => {
    const form = document.getElementById('loginForm');
    const alerts = document.getElementById('alert-container');

    form.addEventListener('submit', async e => {
        e.preventDefault();
        alerts.innerHTML = '';

        const btn = form.querySelector('button');
        btn.disabled = true;

        const data = {
            username: form.username.value.trim(),
            password: form.password.value
        };

        try {
            const res = await fetch('<?= site_url('login') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const json = await res.json();
            showAlert(json.message, json.success ? 'success' : 'danger');

            if (json.success && json.redirect) {
                setTimeout(() => location.href = json.redirect, 1200);
            }

        } catch {
            showAlert('Error de conexión con el servidor', 'danger');
        } finally {
            btn.disabled = false;
        }
    });

    function showAlert(msg, type) {
        alerts.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
        setTimeout(() => alerts.innerHTML = '', 3500);
    }
})();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
