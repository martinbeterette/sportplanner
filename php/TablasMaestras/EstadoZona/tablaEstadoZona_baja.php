<?php
require_once('../../../config/database/conexion.php');
session_start();

$id = $_GET['id_estado_zona'];

//eliminar el producto
$sql = "UPDATE estado_zona 
        	SET
        		estado = 0
        	WHERE id_estado_zona = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
	header("Location: tablaEstadoZona.php");
} else {
	echo "error al actualizar el registro: " . $conexion->error;
}
