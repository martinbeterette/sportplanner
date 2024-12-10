<?php  
	session_start();
	require_once("../../../../config/root_path.php");
	require_once(RUTA . "config/database/conexion.php");

	$id_sucursal = $_GET['id_sucursal'] ?? null;
	$id_complejo = $_GET['id_complejo'] ?? null;

	if(!$id_complejo) {
		header("Location: ../complejos_no_verificadors.php");
	}

	if(!$id_sucursal) {
		header("Location: listado_sucursales.php?id_complejo=$id_complejo");
	}

	echo "ESTAMOS ACA  el id sucursal es : $id_sucursal" . "<br>" . "y el id_complejo: $id_complejo";


	$sql = "
		UPDATE sucursal SET estado = 0 WHERE id_sucursal = $id_sucursal;
	";

	if($conexion->query($sql)) {
		header("Location: listado_sucursales.php?id_complejo=$id_complejo");
	}





?>