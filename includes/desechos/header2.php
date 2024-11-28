
<header>
    <a href="<?php echo BASE_URL . '/index.php' ?>" style="text-decoration: none;">
        <div class="titulo_inicio">
            <img src="<?php echo BASE_URL . 'assets/icons/icono22.png'; ?>" alt="icono inicio">
            <h2>SportPlanner</h2>
        </div>
    </a>

    <div class="profile-dropdown">
        <div onclick="toggle()" class="profile-dropdown-btn">
            <div class="profile-img">
            </div>

            <span style="width: max-content;"><?php echo $_SESSION['usuario']; ?>
                <i class="fa-solid fa-angle-down"></i>
            </span>
        </div>

        <ul class="profile-dropdown-list">
            <li class="profile-dropdown-list-item">
                <a href="<?php echo BASE_URL; ?>login/miPerfil/mis_datos.php">
                    <i class="fa-regular fa-user"></i>
                    Perfil
                </a>
            </li>

            <li class="profile-dropdown-list-item">
                <a href="#">
                    <?php if ($hay_notificacion) { ?>
                        <i class="fa-solid fa-envelope"></i>
                    <?php } else {?>
                        <i class="fa-solid fa-envelope-open"></i>
                    <?php } ?>
                    Notificacion
                </a>
            </li>

            <li class="profile-dropdown-list-item">
                <a href="<?php echo BASE_URL . 'modules/misReservasUsuario/misReservas.php' ?>">
                    <i class="fa-solid fa-futbol"></i>
                    Mis Reservas
                </a>
            </li>

            <li class="profile-dropdown-list-item">
                <a href="#">
                    <i class="fa-solid fa-toggle-off"></i>
                    Modo Oscuro
                </a>
            </li>

            <li class="profile-dropdown-list-item">
                <a href="#">
                    <i class="fa-regular fa-circle-question"></i>
                    Ayuda
                </a>
            </li>
            <hr />

            <li class="profile-dropdown-list-item">
                <a href="#">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Cerrar Sesion
                </a>
            </li>
        </ul>
    </div>
</header>