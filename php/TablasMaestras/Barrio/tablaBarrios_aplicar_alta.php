<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
    
$descripcion 		= $_POST['descripcion'];
$localidad 			= $_POST['localidad'];

$sql = "INSERT INTO 
					barrio(descripcion_barrio, rela_localidad)
		VALUES
			('$descripcion', $localidad)";

if ($conexion->query($sql)) {
	header("Location: tablabarrios.php");
}

?>