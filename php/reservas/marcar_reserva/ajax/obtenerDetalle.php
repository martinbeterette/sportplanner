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

	if($conexion->query($sql)) {

	} else {
		$mensaje = $conexion->error;
		echo json_encode([
			'success' => false,
			'datos' => false,
			'mensaje' => $mensaje,

		]);
	}

?>

 <p><strong>Nombre:</strong> ${respuesta.datos.nombre}</p>
                            <p><strong>apellido:</strong> ${respuesta.datos.apellido}</p>
                            <p><strong>documento:</strong> ${respuesta.datos.descripcion_documento}</p>
                            <p><strong>sexo:</strong> ${respuesta.datos.descripcion_sexo}</p>
                            <p><strong>ID reserva:</strong> ${respuesta.datos.id_reserva}</p>
                            <p><strong>Fecha reserva:</strong> ${respuesta.datos.fecha_reserva}</p>
                            <p><strong>Fecha alta:</strong> ${respuesta.datos.fecha_alta}</p>
                            <p><strong>Cancha:</strong> ${respuesta.datos.descripcion_zona}</p>
                            <p><strong>Sucursal:</strong> ${respuesta.datos.descripcion_sucursal}</p>
                            <p><strong>Direccion:</strong> ${respuesta.datos.direccion}</p>
                            <p><strong>Estado:</strong> ${respuesta.datos.estado_reserva}</p>