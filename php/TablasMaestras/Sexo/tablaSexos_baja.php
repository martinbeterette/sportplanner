<?php 
require_once('../../../config/database/conexion.php');
    session_start();

    

$id = $_GET['id_sexo'];

//eliminar el producto
$sql = "UPDATE sexo 
        	SET
        		estado = 0
        	WHERE id_sexo = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablasexos.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>