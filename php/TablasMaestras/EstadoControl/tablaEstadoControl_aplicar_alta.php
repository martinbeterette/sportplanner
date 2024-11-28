<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    

   
$descripcion 		= $_POST['descripcion'];

$sql = "INSERT INTO 
					estado_control(descripcion_estado_control)
		VALUES
			('$descripcion')";

if ($conexion->query($sql)) {
	header("Location: tablaestadocontrol.php");
}

?>