<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
    
$id = $_GET['id_barrio'];

//eliminar el producto
$sql = "UPDATE barrio 
        	SET
        		estado = 0
        	WHERE id_barrio = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablabarrios.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>