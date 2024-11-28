<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
    
$id = $_GET['id_localidad'];

//eliminar el producto
$sql = "UPDATE localidad 
        	SET
        		estado = 0
        	WHERE id_localidad = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablalocalidades.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>