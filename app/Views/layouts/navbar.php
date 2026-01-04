<!-- app/Views/layouts/navbar.php -->
<nav class="navbar navbar-expand-lg" style="background-color:#28a745;">
  <div class="container-fluid">
    <a class="navbar-brand text-white fw-bold" href="<?= site_url('/') ?>">🌿 Sistema Pimienta</a>

    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <!-- Siempre visible -->
        <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('/') ?>">Inicio</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('acercade') ?>">Acerca de</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('servicios') ?>">Servicios</a></li>

        <?php if (!session()->get('isLoggedIn')): ?>
          <!-- Invitados -->
          <li class="nav-item">
            <a class="btn btn-sm text-white ms-2" style="background-color:#fd7e14;" href="<?= site_url('login') ?>">Iniciar Sesión</a>
          </li>
        <?php else: ?>
          <!-- Usuarios logueados -->
          <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('dashboard') ?>">Dashboard</a></li>
          <li class="nav-item">
            <form method="post" action="<?= site_url('logout') ?>">
              <button type="submit" class="btn btn-sm text-white ms-2" style="background-color:#fd7e14;">Cerrar Sesión</button>
            </form>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
