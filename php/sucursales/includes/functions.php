<?php  

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