<?php 
require_once('../../../config/database/conexion.php');

$id = $_GET['id_tipo_documento'];

//eliminar el producto
$sql = "UPDATE tipo_documento 
        	SET
        		estado = 0
        	WHERE id_tipo_documento = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tabla_tipo_documentos.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>