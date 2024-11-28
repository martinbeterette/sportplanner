<?php 
require_once('../../../config/database/conexion.php');

$id = $_GET['id_tipo_contacto'];

//eliminar el producto
$sql = "UPDATE tipo_contacto 
        	SET
        		estado = 0
        	WHERE id_tipo_contacto = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablatipocontactos.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>