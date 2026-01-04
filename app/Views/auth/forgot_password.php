<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña | SGP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2d6a2d;
            --accent: #ff9f1c;
            --bg: linear-gradient(135deg, #e8f5e9, #f1f8e9);
            --glass: rgba(255, 255, 255, 0.88);
        }

        body {
            min-height: 100vh;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", sans-serif;
        }

        .recover-card {
            background: var(--glass);
            backdrop-filter: blur(12px);
            border-radius: 18px;
            padding: 2.2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 15px 35px rgba(0,0,0,.15);
            animation: fadeIn .6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .recover-title {
            font-weight: 700;
            color: var(--primary);
        }

        .form-control {
            border-radius: 10px;
        }

        .input-group-text {
            background: none;
            border-radius: 10px 0 0 10px;
        }

        .btn-recover {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: transform .2s, box-shadow .2s;
        }

        .btn-recover:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(45,106,45,.3);
        }

        .brand {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .brand i {
            font-size: 3rem;
            color: var(--primary);
        }
    </style>
</head>
<body>

<div class="recover-card">

    <div class="brand">
        <i class="bi bi-envelope-check"></i>
        <h4 class="recover-title mt-2">Recuperar Contraseña</h4>
        <small class="text-muted">
            Te enviaremos un enlace para restablecer tu acceso
        </small>
    </div>

    <!-- Mensajes -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('forgot-password'); ?>" method="post" novalidate>

        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                </span>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    required
                    autocomplete="email"
                    placeholder="usuario@correo.com">
            </div>
        </div>

        <button type="submit" class="btn btn-recover w-100 text-white">
            <i class="bi bi-send"></i> Enviar instrucciones
        </button>

        <div class="text-center mt-3">
            <a href="<?= site_url('login') ?>" class="text-decoration-none">
                Volver al inicio de sesión
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
