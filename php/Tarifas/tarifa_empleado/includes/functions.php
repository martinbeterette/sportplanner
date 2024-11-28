<?php 
function obtenerComplejoPorPersona($id_persona, $id_usuario) {
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
?>