<?php  
function obtenerSucursalPorPersona($id_persona, $id_usuario) {
    global $conexion;

    $sql_sucursal_empleado = "
        SELECT s.id_sucursal 
        FROM empleado e
        JOIN sucursal s ON e.rela_sucursal = s.id_sucursal
        JOIN persona p ON e.rela_persona = p.id_persona
        JOIN contacto c ON p.id_persona = c.rela_persona
        JOIN usuarios u ON c.id_contacto = u.rela_contacto AND u.id_usuario = ?
        WHERE e.rela_persona = ?;";

    $stmt_obtener_complejo = $conexion->prepare($sql_sucursal_empleado);
    $stmt_obtener_complejo->bind_param("ii", $id_usuario, $id_persona);

    if ($stmt_obtener_complejo->execute()) {
        $datos_complejo = $stmt_obtener_complejo->get_result()->fetch_assoc()['id_sucursal'] ?? false;
        return $datos_complejo;
    }
    return false;
}
// Consulta para obtener el ID del complejo basado en la sucursal del empleado
function obtenerIdComplejoDelEmpleado($id_persona, $id_usuario) {
    global $conexion;
    $query = "
        SELECT c.id_complejo 
        FROM complejo c
        JOIN sucursal s ON s.rela_complejo = c.id_complejo
        JOIN empleado e ON e.rela_sucursal = s.id_sucursal
        WHERE e.rela_persona = ? AND  e.rela_usuario = ?
    ";

    // Preparar y ejecutar la consulta
    $stmt_complejo = $conexion->prepare($query);
    $stmt_complejo->bind_param("ii", $id_persona,$id_usuario);
    $stmt_complejo->execute();
    $stmt_complejo->bind_result($id_complejo);
    $stmt_complejo->fetch();
    $stmt_complejo->close();

    // Validar que el complejo se haya encontrado
    if (!$id_complejo) {
        return false;
    }
    return $id_complejo;
}

function obtenerSocios($id_complejo) {
    global $conexion;
    // Realizar la consulta
    $query = "SELECT p.nombre, p.apellido, d.descripcion_documento AS documento, td.descripcion_tipo_documento AS tipo_documento, 
              s.id_socio, s.fecha_alta, s.fecha_afiliacion, s.fecha_expiracion, m.descripcion_membresia, m.descuento, m.precio_membresia
              FROM socio s
              JOIN persona p ON s.rela_persona = p.id_persona
              JOIN documento d ON d.rela_persona = p.id_persona
              JOIN tipo_documento td ON d.rela_tipo_documento = td.id_tipo_documento
              JOIN membresia m ON s.rela_membresia = m.id_membresia
              WHERE s.rela_complejo = ?";
    $stmt_socio = $conexion->prepare($query);
    $stmt_socio->bind_param("i", $id_complejo);
    $stmt_socio->execute();
    $socios = $stmt_socio->get_result();
    return $socios;
}

function validarPersona($id_documento, $descripcion_documento, $id_tipo_documento) {
    global $conexion;
    $sqlValidarPersona = "SELECT p.id_persona, p.nombre, p.apellido, d.descripcion_documento, p.fecha_nacimiento
                            FROM persona p
                            JOIN documento d ON d.rela_persona = p.id_persona
                            WHERE d.descripcion_documento = ? 
                            AND d.rela_tipo_documento = ?
                            AND d.id_documento != ?";
    $stmt = $conexion->prepare($sqlValidarPersona);
    $stmt->bind_param('sii', $descripcion_documento, $id_tipo_documento, $id_documento);
    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        return $resultado;
    }
    return false;
}


function actualizarSocio($conexion, $id_socio, $descripcion_documento, $rela_tipo_documento, $nombre, $apellido, $fecha_nacimiento, $rela_membresia, $rela_sexo) {
    $sql = "UPDATE persona p 
            JOIN documento d ON p.id_persona = d.rela_persona
            JOIN socio s ON p.id_persona = s.rela_persona
            SET d.descripcion_documento = ?, 
                d.rela_tipo_documento = ?, 
                p.nombre = ?, 
                p.apellido = ?, 
                p.fecha_nacimiento = ?, 
                s.rela_membresia = ?, 
                p.rela_sexo = ?
            WHERE s.id_socio = ?";

    if ($stmt_update_socio = $conexion->prepare($sql)) {
        $stmt_update_socio->bind_param("sisssiii", $descripcion_documento, $rela_tipo_documento, $nombre, $apellido, $fecha_nacimiento, $rela_membresia, $rela_sexo, $id_socio);

        if ($stmt_update_socio->execute()) {
            $stmt_update_socio->close();
            return true;
        } else {
            $stmt_update_socio->close();
            return false;
        }
    } else {
        return false;
    }
}

?>