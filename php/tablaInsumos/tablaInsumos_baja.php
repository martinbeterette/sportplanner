<?php 
require_once('../../config/database/conexion.php');

$id = $_GET['id_insumo'];

//eliminar el producto
$sql = "UPDATE insumo 
        	SET
        		estado = 0
        	WHERE id_insumo = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablaInsumos.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>