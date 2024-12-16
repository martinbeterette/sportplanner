<?php  

function obtenerReservasDelMes($id_complejo) {
	$sql = "
		SELECT COUNT() 
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

?>