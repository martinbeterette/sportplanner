<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
    
$id = $_GET['id_membresia'];

//eliminar el producto
$sql = "UPDATE membresia 
        	SET
        		estado = 0
        	WHERE id_membresia = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablaMembresia.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>