<?php 
require_once("../../../../config/root_path.php");
require_once(RUTA . 'config/database/conexion.php');

$id = $_GET['id'];

//eliminar el producto
$sql = "UPDATE tarifa 
        	SET
        		estado = 0
        	WHERE id_tarifa = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: ../tabla_tarifa.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>