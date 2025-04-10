<div class="d-flex flex-column align-items-start h-100">
    <!-- Logo / Título del sistema -->
    <div class="text-center w-100 mb-4">
        <h4 class="text-white">Mi Sistema</h4>
    </div>

    <!-- Menú de navegación -->
    <ul class="nav flex-column w-100">
        <li class="nav-item">
            <a href="/" class="nav-link text-white">
                <i class="fas fa-home me-2"></i> Inicio
            </a>
        </li>
        <li class="nav-item">
            <a href="/usuarios" class="nav-link text-white">
                <i class="fas fa-users me-2"></i> Usuarios
            </a>
        </li>
        <li class="nav-item">
            <a href="/reservas" class="nav-link text-white">
                <i class="fas fa-calendar-alt me-2"></i> Reservas
            </a>
        </li>
        <li class="nav-item">
            <a href="/tablasMaestras" class="nav-link text-white">
                <i class="fas fa-calendar-alt me-2"></i> Tablas maestras
            </a>
        </li>
        <li class="nav-item">
            <a href="/reportes" class="nav-link text-white">
                <i class="fas fa-chart-line me-2"></i> Reportes
            </a>
        </li>
        <li class="nav-item">
            <a href="/configuracion" class="nav-link text-white">
                <i class="fas fa-cogs me-2"></i> Configuración
            </a>
        </li>
    </ul>

    <!-- Botón cerrar sesión abajo -->
    <div class="w-100 mt-auto">
        <a href="{{ route('logout') }}" class="nav-link text-white">
            <i class="fas fa-sign-out-alt me-2"></i> Cerrar sesión
        </a>
    </div>
</div>

