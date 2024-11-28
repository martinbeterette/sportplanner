<?php 
	require_once("../../../config/database/conexion.php");
    session_start();

  

$descripcion 		= $_POST['descripcion'];

$sql = "INSERT INTO 
					servicio(descripcion_servicio)
		VALUES
			('$descripcion')";

if ($conexion->query($sql)) {
	header("Location: tablaservicios.php");
}

?>