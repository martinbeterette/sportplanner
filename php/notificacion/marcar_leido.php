<?php
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if (isset($_POST['id'])) {
    $id = $conexion->real_escape_string($_POST['id']);
    $sql = "UPDATE notificacion SET estado = 'leido' WHERE id_notificacion = '$id'";
    $conexion->query($sql);
}

$conexion->close();
