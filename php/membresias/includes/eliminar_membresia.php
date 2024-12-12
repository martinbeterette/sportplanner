<?php  
	require_once("../../../config/root_path.php");
	require_once(RUTA."config/database/conexion.php");

	$id_membresia = $_GET['id_membresia'] ?? null;
	if(!$id_membresia){
		header("Location: ../index.php");
	}

	$sql = "UPDATE membresia SET estado = 0 WHERE id_membresia = $id_membresia";
	if($conexion->query($sql)){
		header("Location: ../index.php");
	}

?>