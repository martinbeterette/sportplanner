<?php 
	require_once("../../../config/database/conexion.php");
    session_start();

   

$descripcion 		= $_POST['descripcion'];
$deporte 			= $_POST['deporte'];

$sql = "INSERT INTO 
					formato_deporte(descripcion_formato_deporte, rela_deporte)
		VALUES
			('$descripcion', $deporte)";

if ($conexion->query($sql)) {
	header("Location: tablaFormatoDeportes.php");
}

?>