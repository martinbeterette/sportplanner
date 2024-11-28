<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
$id = $_GET['id_deporte'];

//eliminar el producto
$sql = "UPDATE deporte 
        	SET
        		estado = 0
        	WHERE id_deporte = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablaDeportes.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>