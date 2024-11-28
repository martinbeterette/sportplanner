<div class="profile-dropdown">
    <div onclick="toggle()" class="profile-dropdown-btn">
        <div class="profile-img">
        </div>

        <span>Messi
            <i class="fa-solid fa-angle-down"></i>
        </span>
    </div>

    <ul class="profile-dropdown-list">
        <li class="profile-dropdown-list-item">
            <a href="#">
                <i class="fa-regular fa-user"></i>
                Perfil
            </a>
        </li>

        <li class="profile-dropdown-list-item">
            <a href="#">
                <i class="fa-regular fa-envelope"></i>
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