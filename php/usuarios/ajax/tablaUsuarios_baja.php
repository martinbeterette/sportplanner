<?php
require_once("../../../config/root_path.php");
require_once('../../../config/database/db_functions.php');
session_start();

$id = isset($_GET['id']) ? $_GET['id'] : null;
$actualValue = isset($_GET['actual_estado']) ? $_GET['actual_estado'] : null;

if ($id !== null && $actualValue !== null) {
    $actualValue = ($actualValue == 1) ? 0 : 1;

    $sql = "UPDATE persona 
            SET estado = $actualValue 
            WHERE id_persona = $id";

    if ($conexion->query($sql)) {
        echo 'success';
    } else {
        echo "error al actualizar el registro: " . $conexion->error;
    }
} else {
    echo "Faltan parámetros necesarios.";
}
?>
