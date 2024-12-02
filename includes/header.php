<?php  
    if(!isset($_SESSION['id_usuario']) || !isset($_SESSION['usuario'])) {
        header("Location: ". BASE_URL . "login/inicio_sesion/inicio_sesion.php");
        exit();
    }
    function obtenerSucursalPorEmpleado($username, $id_persona) {
        global $conexion;
        $sql_sucursal_empleado = "
            SELECT s.id_sucursal 
                FROM empleado e
                JOIN sucursal s ON e.rela_sucursal = s.id_sucursal
                JOIN usuarios u ON u.id_usuario = e.rela_usuario 
                WHERE e.rela_persona = ?
                AND u.username = ?";
        $stmt_sucursal_empleado = $conexion->prepare($sql_sucursal_empleado);
        $stmt_sucursal_empleado->bind_param("is", $id_persona, $username);

        if ($stmt_sucursal_empleado->execute()) {
            $id_sucursal = $stmt_sucursal_empleado->get_result()->fetch_assoc()['id_sucursal'];
            return $id_sucursal;
        }
        return false;
    }

    function obtenerSucursalesPorPropietario($username, $id_persona, $id_usuario) {
        global $conexion;

        $sql_sucursal_empleado = "
            SELECT s.id_sucursal 
            FROM sucursal s
            JOIN complejo c ON s.rela_complejo = c.id_complejo
            JOIN asignacion_persona_complejo apc ON c.id_complejo = apc.rela_complejo
            JOIN contacto co ON co.rela_persona = apc.rela_persona
            JOIN usuarios u ON u.rela_contacto = co.id_contacto
            WHERE username = ? AND apc.rela_persona = ? AND apc.rela_usuario = ?";
        $stmt_sucursal_empleado = $conexion->prepare($sql_sucursal_empleado);
        $stmt_sucursal_empleado->bind_param("sii", $username, $id_persona, $id_usuario);

        if ($stmt_sucursal_empleado->execute()) {
            $sucursales = $stmt_sucursal_empleado->get_result();
            return $sucursales;
        }
        return false;
    }

    if ($_SESSION['id_perfil'] == 3) {
        //obtenemos los parametros
        $username = $_SESSION['usuario'];
        $id_persona = $_SESSION['id_persona'];

        //obtenemos el idsucursal del empleado
        $sucursal_del_empleado = obtenerSucursalPorEmpleado($username,$id_persona);

        $query_notificacion = "SELECT * FROM notificacion WHERE estado = 'no leido' AND rela_sucursal IN($sucursal_del_empleado) LIMIT 3";
    }

    if ($_SESSION['id_perfil']  == 23) {
        //obtenemos los parametros
        $username = $_SESSION['usuario'];
        $id_persona = $_SESSION['id_persona'];
        $id_usuario = $_SESSION['id_usuario'];

        //obtenemos el idsucursal del empleado
        $sucursales_del_propietario = obtenerSucursalesPorPropietario($username,$id_persona, $id_usuario);

        $array_sucursales = [];
        foreach ($sucursales_del_propietario as $reg) {
            // Agregar directamente el ID de la sucursal al array
            $array_sucursales[] = $reg['id_sucursal'];
        }

        // Ahora puedes usar implode para generar una lista separada por comas
        $lista_sucursales_del_propietario = implode(",", $array_sucursales);

        if($lista_sucursales_del_propietario) {

            $query_notificacion = "SELECT * FROM notificacion WHERE estado = 'no leido' AND rela_sucursal IN($lista_sucursales_del_propietario) LIMIT 3";
        }

    }


    if ($_SESSION['id_perfil'] == 1) {
        $id_usuario = $_SESSION['id_usuario'];
        $query_notificacion = "SELECT * FROM notificacion WHERE estado = 'no leido' AND rela_usuario = $id_usuario LIMIT 3";
    }

    if(isset($query_notificacion)) {

        $resultado_notificacion = $conexion->query($query_notificacion);

        $hay_notificacion = $resultado_notificacion->num_rows > 0;
    }   else {
        $hay_notificacion = false;
        $resultado_notificacion = false;
    }

?>
<header>
    <a href="<?php echo BASE_URL . 'index2.php' ?>" style="text-decoration: none;">
        <div class="titulo_inicio">
            <img src="<?php echo BASE_URL. "assets/icons/juego.png" ?>" alt="icono inicio">
            <h2>SportPlanner</h2>
        </div>
    </a>

    <div class="profile-dropdown">
        <?php if ($hay_notificacion) { ?>
            <span class="badge" style="background-color: red"></span>
        <?php } ?>
        <div class="profile-dropdown-btn">
            <div class="profile-img">
                <!-- Imagen de perfil aquí -->
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

            <li class="profile-dropdown-list-item" id="notificaciones">
                <a href="" id="notificaciones-btn">
                    <i class="fa-solid fa-envelope">
                        <?php if ($hay_notificacion) { ?>
                            <span class="badge" style="background-color: red"></span>
                        <?php } ?>
                    </i>
                        
                    Notificaciones
                </a>
                <!-- Contenedor para la sublista -->
                <div class="notifications-dropdown">
                    <!-- Sublista oculta para notificaciones -->
                    <ul class="sub-notifications-list">

                        <?php 
                            if($resultado_notificacion) :
                                foreach ($resultado_notificacion as $reg) : 
                        ?>
                            <li 
                                data-id-notificacion="<?php echo $reg['id_notificacion']; ?>"
                            >
                                <?php echo $reg['titulo']; ?>
                                
                            </li>

                        <?php 
                                endforeach; 
                                $resultado_notificacion = false;
                            endif;
                        ?>
                        <li class="ver-mas-notificaciones">
                            <a 
                                href="<?php echo BASE_URL . "php/notificacion/notificacion.php" ?>"
                            >Ver m&aacute;s...</a>
                        </li>
                    </ul>
                </div>
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
                <a href="<?php echo BASE_URL. "login/cerrar_sesion/cerrar_sesion.php" ?>">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>
</header>
<script>const base_url = <?php echo json_encode(BASE_URL); ?>;</script>