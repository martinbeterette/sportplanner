<?php 
require_once("../../../config/database/conexion.php");
    session_start();

   
    
$id = $_GET['id_estado_control'];

//eliminar el producto
$sql = "UPDATE estado_control 
        	SET
        		estado = 0
        	WHERE id_estado_control = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablaEstadoControl.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>