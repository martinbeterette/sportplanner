<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
    
$id = $_GET['id_estado_reserva'];

//eliminar el producto
$sql = "UPDATE estado_reserva 
        	SET
        		estado = 0
        	WHERE id_estado_reserva = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablaestadoreserva.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>