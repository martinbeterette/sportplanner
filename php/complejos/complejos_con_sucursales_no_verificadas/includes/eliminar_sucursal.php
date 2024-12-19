<?php  
	session_start();
	require_once("../../../../config/root_path.php");
	require_once(RUTA . "config/database/conexion.php");

	$id_sucursal = $_GET['id_sucursal'] ?? null;
	$id_complejo = $_GET['id_complejo'] ?? null;
	$id_usuario  = $_SESSION['id_usuario'];

	if(!$id_complejo) {
		header("Location: ../complejos_no_verificadors.php");
	}

	if(!$id_sucursal) {
		header("Location: listado_sucursales.php?id_complejo=$id_complejo");
	}

	echo "ESTAMOS ACA  el id sucursal es : $id_sucursal" . "<br>" . "y el id_complejo: $id_complejo";

	$resultado_anterior = $conexion->query("SELECT 
												id_sucursal,
												descripcion_sucursal,
												descripcion_complejo,
												sucursal.fecha_alta,
												sucursal.verificado
											FROM sucursal JOIN complejo
											on sucursal.rela_complejo = complejo.id_complejo
											WHERE id_sucursal = $id_sucursal
												 ")->fetch_assoc();
	$json_anterior = json_encode($resultado_anterior);

	$sql = "
		UPDATE sucursal SET estado = 0 WHERE id_sucursal = $id_sucursal;
	";

	if($conexion->query($sql)) {
		$sql = "INSERT into auditoria_verificado_sucursal(accion,estado_anterior, estado_posterior, tiempo_accion, rela_usuario) VALUES('Eliminacion', '$json_anterior', null, CURTIME(), $id_usuario)";
		if($conexion->query($sql)) {

			header("Location: listado_sucursales.php?id_complejo=$id_complejo");
		}
	}





?>