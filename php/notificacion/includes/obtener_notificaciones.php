<?php 
$sql = false; 
if ($_SESSION['id_perfil'] == 3) {
    //obtenemos los parametros
    $username = $_SESSION['usuario'];
    $id_persona = $_SESSION['id_persona'];

    //obtenemos el idsucursal del empleado
    $sucursal_de_empleado = obtenerSucursalDelEmpleado($username,$id_persona);

    if($sucursal_de_empleado) {

        $sql = "
            SELECT id_notificacion, titulo, mensaje, n.estado AS leido, n.rela_sucursal, rela_usuario, fecha_creacion, 
                TIMESTAMPDIFF(DAY, fecha_creacion, NOW()) AS dias, 
                TIMESTAMPDIFF(HOUR, fecha_creacion, NOW()) % 24 AS horas, r.*, p.*, h.*, d.*, z.*
                FROM notificacion n 
                JOIN reserva r ON n.rela_reserva = r.id_reserva
                JOIN horario h ON r.rela_horario = h.id_horario
                JOIN persona p ON r.rela_persona = p.id_persona
                JOIN documento d ON p.id_persona = d.rela_persona
                JOIN zona z ON r.rela_zona = z.id_zona
                WHERE n.rela_sucursal = $sucursal_de_empleado
                ORDER BY fecha_creacion DESC
        ";     

        $sql_unread_count = 
            "SELECT COUNT(*) as unread_count
                FROM notificacion n 
                JOIN reserva r ON n.rela_reserva = r.id_reserva
                JOIN horario h ON r.rela_horario = h.id_horario
                JOIN persona p ON r.rela_persona = p.id_persona
                JOIN documento d ON p.id_persona = d.rela_persona
                JOIN zona z ON r.rela_zona = z.id_zona
                WHERE n.rela_sucursal = $sucursal_de_empleado
                ORDER BY fecha_creacion DESC";

    }

}

if ($_SESSION['id_perfil'] == 23) {
    //obtenemos los parametros
        $username = $_SESSION['usuario'];
        $id_persona = $_SESSION['id_persona'];
        $id_usuario = $_SESSION['id_usuario'];

        $sucursales_del_propietario = obtenerSucursalesDelPropietario($username,$id_persona, $id_usuario);
        $array_sucursales = [];
        foreach ($sucursales_del_propietario as $reg) {
            // Agregar directamente el ID de la sucursal al array
            $array_sucursales[] = $reg['id_sucursal'];
        }

        // Ahora puedes usar implode para generar una lista separada por comas
        $lista_sucursales_del_propietario = implode(",", $array_sucursales);

        if($lista_sucursales_del_propietario) {

            $sql = "SELECT id_notificacion, titulo, mensaje, n.estado AS leido, n.rela_sucursal, rela_usuario, fecha_creacion, 
                    TIMESTAMPDIFF(DAY, fecha_creacion, NOW()) AS dias, 
                    TIMESTAMPDIFF(HOUR, fecha_creacion, NOW()) % 24 AS horas, r.*, p.*, h.*, d.*, z.*
                    FROM notificacion n 
                    JOIN reserva r ON n.rela_reserva = r.id_reserva
                    JOIN horario h ON r.rela_horario = h.id_horario
                    JOIN persona p ON r.rela_persona = p.id_persona
                    JOIN documento d ON p.id_persona = d.rela_persona
                    JOIN zona z ON r.rela_zona = z.id_zona
                    WHERE n.rela_sucursal IN($lista_sucursales_del_propietario)
                    ORDER BY fecha_creacion DESC";

            $sql_unread_count = 
                "SELECT COUNT(*) as unread_count
                    FROM notificacion n 
                    JOIN reserva r ON n.rela_reserva = r.id_reserva
                    JOIN horario h ON r.rela_horario = h.id_horario
                    JOIN persona p ON r.rela_persona = p.id_persona
                    JOIN documento d ON p.id_persona = d.rela_persona
                    JOIN zona z ON r.rela_zona = z.id_zona
                    WHERE n.rela_sucursal IN($lista_sucursales_del_propietario)
                    ORDER BY fecha_creacion DESC";
                    
        }

}

if($_SESSION['id_perfil'] == 1) {
    $id_usuario = $_SESSION['id_usuario'];
    $sql = "SELECT id_notificacion, titulo, mensaje, n.estado AS leido, n.rela_sucursal, rela_usuario, fecha_creacion, 
            TIMESTAMPDIFF(DAY, fecha_creacion, NOW()) AS dias, 
            TIMESTAMPDIFF(HOUR, fecha_creacion, NOW()) % 24 AS horas, r.*, p.*, h.*, d.*, z.*
            FROM notificacion n 
            JOIN reserva r ON n.rela_reserva = r.id_reserva
            JOIN horario h ON r.rela_horario = h.id_horario
            JOIN persona p ON r.rela_persona = p.id_persona
            JOIN documento d ON p.id_persona = d.rela_persona
            JOIN zona z ON r.rela_zona = z.id_zona
            WHERE n.rela_usuario = $id_usuario
            ORDER BY fecha_creacion DESC";

    $sql_unread_count = 
        "
        SELECT COUNT(*) as unread_count
            FROM notificacion n 
            JOIN reserva r ON n.rela_reserva = r.id_reserva
            JOIN horario h ON r.rela_horario = h.id_horario
            JOIN persona p ON r.rela_persona = p.id_persona
            JOIN documento d ON p.id_persona = d.rela_persona
            JOIN zona z ON r.rela_zona = z.id_zona
            WHERE n.rela_usuario = $id_usuario
            ORDER BY fecha_creacion DESC";
}
// Consultar notificaciones con el tipo de notificación

if ($sql) {
    $Notificaciones = $conexion->query($sql);
} else {
    $Notificaciones = null; // O maneja una redirección aquí si prefieres
}


// Contador de notificaciones no leídas
if($sql) {
    $unread_result = $conexion->query($sql_unread_count);
    $unread_count = ($unread_result->num_rows > 0) ? $unread_result->fetch_assoc()['unread_count'] : 0;
} else {
    $unread_count = 0;
}

if(isset($_GET['notificacion_seleccionada'])) {
    $notificacion_seleccionada = $_GET['id_notificacion'];
}

?>