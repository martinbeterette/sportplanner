<?php 
require_once('../../config/database/conexion.php');

$id = $_GET['id'];
$id_sucursal = $_GET['id_sucursal'];

//eliminar el producto
$sql = "UPDATE tarifa 
        	SET
        		estado = 0
        	WHERE id_tarifa = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tabla_tarifa.php?id_sucursal=$id_sucursal"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>