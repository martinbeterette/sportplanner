<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
    
$descripcion 		= $_POST['descripcion'];

$sql = "INSERT INTO 
					estado_reserva(descripcion_estado_reserva)
		VALUES
			('$descripcion')";

if ($conexion->query($sql)) {
	header("Location: tablaestadoreserva.php");
}

?>