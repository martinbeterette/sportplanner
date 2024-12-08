<?php  

function esEmpleadoDeLaSucursal($id_persona, $id_sucursal, $conexion) {

    $sql = "SELECT COUNT(id_empleado) as es_empleado
            FROM empleado
            WHERE rela_persona = ? AND rela_sucursal = ? AND estado IN(1)";

    $stmtEsEmpleado = $conexion->prepare($sql);
    $stmtEsEmpleado->bind_param("ii", $id_persona, $id_sucursal);
    if ($stmtEsEmpleado->execute()) {
        $registros = $stmtEsEmpleado->get_result();
        if ($registros->fetch_assoc()['es_empleado'] > 0) {
            return true;
        }
    }

    return false;
} 

function registrarControlAsistencia($conexion, $id_reserva, $estado, $observacion = null) {
    $fecha = date('Y-m-d');
    $horario = date('H:i:s');
        
    
    // Insertar el registro de control
    $stmtControlAsistencia = $conexion->prepare("INSERT INTO control (rela_reserva, fecha_alta, horario,estado, observacion) VALUES (?, ?, ?, ?, ?)");
    $stmtControlAsistencia->bind_param("issss", $id_reserva, $fecha, $horario, $estado, $observacion);
    
    if ($stmtControlAsistencia->execute()) {
        return true;
    } else {
        return false;
    }
    
    $stmtControlAsistencia->close();
}

function obtenerSucursalDelEmpleado($id_persona, $id_usuario) {
    global $conexion;

    $sql_sucursal_empleado = "
        SELECT s.id_sucursal 
        FROM empleado e
        JOIN sucursal s ON e.rela_sucursal = s.id_sucursal
        JOIN persona p ON e.rela_persona = p.id_persona
        JOIN contacto c ON p.id_persona = c.rela_persona
        JOIN usuarios u ON c.id_contacto = u.rela_contacto 
        WHERE u.id_usuario = ? AND e.rela_persona = ?";

    $stmt_obtener_complejo = $conexion->prepare($sql_sucursal_empleado);
    $stmt_obtener_complejo->bind_param("ii", $id_usuario, $id_persona);

    if ($stmt_obtener_complejo->execute()) {
        $datos_complejo = $stmt_obtener_complejo->get_result()->fetch_assoc()['id_sucursal'] ?? false;
        return $datos_complejo;
    }
    return false;
}

function obtenerSucursalesDelPropietario($id_usuario) {
    global $conexion;
    $sql = "
        SELECT id_sucursal
        FROM sucursal s JOIN complejo ON id_complejo = s.rela_complejo
        JOIN asignacion_persona_complejo apc ON id_complejo = apc.rela_complejo
        WHERE apc.rela_usuario = ?
    ";

    $stmt_sucursales_propietario = $conexion->prepare($sql);
    $stmt_sucursales_propietario->bind_param("i",$id_usuario);
    if($stmt_sucursales_propietario->execute()){
        $registros = $stmt_sucursales_propietario->get_result();
        return $registros;
    }
    return false;
}



?>