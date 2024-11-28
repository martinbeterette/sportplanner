<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
    
$descripcion 		= $_POST['descripcion'];
$provincia 			= $_POST['provincia'];

$sql = "INSERT INTO 
					localidad(descripcion_localidad, rela_provincia)
		VALUES
			('$descripcion', $provincia)";

if ($conexion->query($sql)) {
	header("Location: tablalocalidades.php");
}

?>