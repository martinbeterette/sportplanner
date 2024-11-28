<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
$descripcion 		= $_POST['descripcion'];
$beneficio          = $_POST['beneficio'];

$sql = "INSERT INTO 
					membresia(beneficio_membresia, descripcion_membresia)
		VALUES
			('{$beneficio}','{$descripcion}')";

if ($conexion->query($sql)) {
	header("Location: tablaMembresia.php");
}

?>