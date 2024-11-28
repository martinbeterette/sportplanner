<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
$id = $_GET['id_provincia'];

//eliminar el producto
$sql = "UPDATE provincia 
        	SET
        		estado = 0
        	WHERE id_provincia = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablaprovincias.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>