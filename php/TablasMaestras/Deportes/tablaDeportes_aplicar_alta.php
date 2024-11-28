<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
    
$descripcion 		= $_POST['descripcion'];

$sql = "INSERT INTO 
					deporte(descripcion_deporte)
		VALUES
			('$descripcion')";

if ($conexion->query($sql)) {
	header("Location: tablaDeportes.php");
}

?>