<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>
        <?php 
            $title = $this->renderSection('title'); 
            echo !empty($title) ? $title : 'Dashboard'; 
        ?>
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --bg-main: #f9faf8;
            --bg-card: #ffffff;
            --text-main: #2d3319;
            --text-muted: #8b9c6f;
            --green: #4a7c2f;
            --green-dark: #1e3a0f;
            --orange: #d97706;
            --shadow: 0 4px 12px rgba(0,0,0,.08);
        }

        body {
            background: var(--bg-main);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--text-main);
        }

        /* NAVBAR */
        .navbar {
            background: linear-gradient(90deg, var(--green-dark), var(--green), var(--orange));
            box-shadow: var(--shadow);
            z-index: 1030;
        }

        .navbar-brand { font-weight: 700; color: #fff !important; }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 260px;
            padding-top: 75px;
            background: var(--green-dark);
            transition: all .3s ease;
            z-index: 1020;
        }

        .sidebar.collapsed { transform: translateX(-100%); }

        .sidebar a {
            color: rgba(255,255,255,.8);
            padding: .8rem 1.25rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-radius: .5rem;
            margin: .2rem .75rem;
            transition: .2s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,.15);
            color: #fff;
        }

        .sidebar i { margin-right: 10px; font-size: 1.1rem; }

        /* MAIN CONTENT */
        .main {
            margin-left: 260px;
            padding-top: 90px;
            padding-bottom: 40px;
            transition: all .3s ease;
            min-height: 90vh;
        }

        .main.expanded { margin-left: 0; }

        footer {
            margin-left: 260px;
            transition: all .3s ease;
            background: #f1f3ef;
            border-top: 1px solid #e0e3d9;
        }

        footer.expanded { margin-left: 0; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main, footer { margin-left: 0; }
        }
    </style>
    
    <?= $this->renderSection('styles') ?>
</head>
<body>

<?php 
    $session = session();
    $rol = strtolower($session->get('rol') ?? '');
    $segment = service('uri')->getSegment(1);
?>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <button class="btn btn-outline-light me-3" id="btnSidebar" type="button">
            <i class="bi bi-list"></i>
        </button>

        <a class="navbar-brand" href="<?= site_url('dashboard') ?>">
            Sistema de Gestión de Pimienta
        </a>

        <div class="ms-auto">
            <?php if ($session->get('isLoggedIn')): ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown" href="#" role="button">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= esc($session->get('username')) ?> 
                        <span class="badge bg-light text-dark ms-1" style="font-size: 0.7rem;">
                            <?= ($rol === 'admin') ? 'Admin' : 'Usuario' ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="<?= site_url('perfil') ?>"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?= site_url('logout') ?>">
                                <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                            </a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<aside class="sidebar" id="sidebar">
    <a href="<?= site_url('dashboard') ?>" class="<?= in_array($segment, ['dashboard', 'admin', 'usuario-dashboard']) ? 'active' : '' ?>">
        <i class="bi bi-house"></i> Inicio
    </a>

    <a href="<?= site_url('lotes-entrada') ?>" class="<?= $segment === 'lotes-entrada' ? 'active' : '' ?>">
        <i class="bi bi-box-arrow-in-down"></i> Entradas
    </a>

    <?php if ($rol === 'admin'): ?>
        <a href="<?= site_url('lotes-salida') ?>" class="<?= $segment === 'lotes-salida' ? 'active' : '' ?>">
            <i class="bi bi-box-arrow-up"></i> Salidas
        </a>

        <a href="<?= site_url('procesos') ?>" class="<?= $segment === 'procesos' ? 'active' : '' ?>">
            <i class="bi bi-gear"></i> Procesos
        </a>

        <a href="<?= site_url('centros') ?>" class="<?= $segment === 'centros' ? 'active' : '' ?>">
            <i class="bi bi-building"></i> Centros
        </a>

        <a href="<?= site_url('usuario') ?>" class="<?= ($segment === 'usuario' && service('uri')->getSegment(2) !== 'dashboard') ? 'active' : '' ?>">
            <i class="bi bi-people"></i> Usuarios
        </a>
    <?php endif; ?>
</aside>

<main class="main" id="main">
    <div class="container-fluid">
        
        <?php foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $color): ?>
            <?php if (session()->getFlashdata($key)): ?>
                <div class="alert alert-<?= $color ?> alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi <?= $key === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle' ?>-fill me-2"></i>
                    <?= esc(session()->getFlashdata($key)) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <?= $this->renderSection('content') ?>
    </div>
</main>

<footer class="py-4 text-center small mt-auto" id="footer">
    <span class="text-muted">
        &copy; <?= date('Y') ?> Sistema de Gestión de Pimienta | <b>Rol: <?= ucfirst($rol) ?></b>
    <div class="mt-1 opacity-75">
        Desarrollado por: <strong>Dev. Antonio BJ</strong>
    </div>
    </span>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main');
        const footer = document.getElementById('footer');
        const btn = document.getElementById('btnSidebar');

        if(btn) {
            btn.addEventListener('click', () => {
                // En móviles usamos una lógica distinta si quieres que se encime
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('collapsed');
                    main.classList.toggle('expanded');
                    footer.classList.toggle('expanded');
                }
            });
        }

        // Auto-cerrar alertas después de 5 segundos
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                if (bsAlert) bsAlert.close();
            });
        }, 5000);
    });
</script>

<?= $this->renderSection('scripts') ?>

</body>
</html>