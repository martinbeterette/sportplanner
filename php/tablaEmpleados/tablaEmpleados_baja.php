<?php 
require_once('../../config/database/conexion.php');

$id = $_GET['id_empleado'];
$id_sucursal = $_GET['id_sucursal'];

//eliminar el producto
$sql = "UPDATE empleado 
        	SET
        		estado = 0
        	WHERE id_empleado = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablaEmpleados.php?id_sucursal=$id_sucursal"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>