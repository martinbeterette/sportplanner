<?php  

	require_once("../../../../config/root_path.php");
	require_once(RUTA . "config/database/conexion.php");

	$id_reserva = $_REQUEST['id_reserva'];

	$sql = "
		SELECT 
			nombre,
			apellido,
			descripcion_documento,
			descripcion_sexo,
			id_reserva,
			fecha_reserva,
			r.fecha_alta,
			descripcion_sucursal,
			asd.direccion,
			b.descripcion_barrio,
			r.estado as estado_reserva

		FROM reserva r JOIN zona z
		ON r.rela_zona = z.id_zona
		JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
		JOIN persona p ON p.id_persona = r.rela_persona
		JOIN documento d ON p.id_persona = d.rela_persona
		JOIN asignacion_sucursal_domicilio asd ON asd.rela_sucursal = s.id_sucursal
		JOIN barrio b ON asd.rela_barrio = b.id_barrio
		JOIN sexo se ON se.id_sexo = p.rela_sexo
		WHERE id_reserva = $id_reserva
	";

	if($datos = $conexion->query($sql)->fetch_assoc()) {
		echo json_encode([
			'success' => true,
			'datos' => $datos,
			'mensaje' => false,
		]);
	} else {
		$mensaje = $conexion->error;
		echo json_encode([
			'success' => false,
			'datos' => false,
			'mensaje' => $mensaje,
		]);
	}

?>

 