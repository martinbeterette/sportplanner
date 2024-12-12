<?php
require_once('../../../config/database/conexion.php');
session_start();

$id = $_GET['id_tipo_terreno'];

//eliminar el producto
$sql = "UPDATE tipo_terreno 
        	SET
        		estado = 0
        	WHERE id_tipo_terreno = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablaTipoTerrenos.php");
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
