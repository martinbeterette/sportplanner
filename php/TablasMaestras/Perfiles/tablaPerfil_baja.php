<?php
require_once('../../../config/database/conexion.php');
session_start();

$id = $_GET['id_perfil'];

//eliminar el producto
$sql = "UPDATE perfil 
        	SET
        		estado = 0
        	WHERE id_perfil = $id";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablaPerfil.php");
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
