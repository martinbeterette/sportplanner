<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
    
$descripcion 		= $_POST['descripcion'];

$sql = "INSERT INTO 
					provincia(descripcion_provincia)
		VALUES
			('$descripcion')";

if ($conexion->query($sql)) {
	header("Location: tablaprovincias.php");
}

?>