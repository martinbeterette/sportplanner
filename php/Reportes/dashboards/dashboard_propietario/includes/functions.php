<?php 

function obtenerReservasDelMes($id_complejo) {
	global $conexion;
	$sql = "
		SELECT COUNT(*) as total 
			FROM reserva r
			JOIN zona z ON r.rela_zona = z.id_zona
			JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
			JOIN complejo c ON s.rela_complejo = c.id_complejo
			WHERE fecha_reserva BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE()
			AND r.rela_estado_reserva = 3 AND c.id_complejo = $id_complejo;
		";

	if($registros = $conexion->query($sql)){
		return $registros;
	} else {
		return false;
	}
	
}

function obtenerCanchasTotales($id_complejo) {
	global $conexion;
	$sql = "
		SELECT COUNT(*) as total FROM zona z
			JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
			JOIN complejo c ON s.rela_complejo = c.id_complejo
			WHERE c.id_complejo = $id_complejo AND z.estado = 1;
		";

	if($registros = $conexion->query($sql)){
		return $registros;
	} else {
		return false;
	}
	
}

function obtenerSucursalesActivas($id_complejo) {
	global $conexion;
	$sql = "
		SELECT COUNT(*) as total FROM sucursal
			JOIN complejo ON rela_complejo = id_complejo
			WHERE id_complejo = $id_complejo AND sucursal.estado = 1;
		";

	if($registros = $conexion->query($sql)){
		return $registros;
	} else {
		return false;
	}
	
}

function obtenerComplejo($id_persona, $id_usuario) {
    global $conexion;

    $sql_sucursal_empleado = "
        SELECT apc.rela_complejo
        FROM asignacion_persona_complejo apc
        JOIN complejo ON apc.rela_complejo = id_complejo
        WHERE apc.rela_persona = ? AND apc.rela_usuario = ?";

    $stmt_obtener_complejo = $conexion->prepare($sql_sucursal_empleado);
    $stmt_obtener_complejo->bind_param("ii", $id_persona, $id_usuario);

    if ($stmt_obtener_complejo->execute()) {
        $result = $stmt_obtener_complejo->get_result();
        if ($result->num_rows > 0) {
            $datos_complejo = $result->fetch_assoc();
            return $datos_complejo['rela_complejo'] ?? false;
        } else {
            // No se encontraron resultados
            return false;
        }
    } else {
        // Error en la ejecución de la consulta
        return false;
    }
}


?>