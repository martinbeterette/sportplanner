<?php 
require_once("../../../config/database/conexion.php");
    session_start();

   

$id = $_GET['id_servicio'];

//eliminar el producto
$sql = "UPDATE servicio 
        	SET
        		estado = 0
        	WHERE id_servicio = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablaservicios.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>