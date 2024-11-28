<?php
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if (isset($_REQUEST['id_reserva'])) {
    $idReserva = $_REQUEST['id_reserva'];
    $sql = "UPDATE reserva SET rela_estado_reserva = 7 WHERE id_reserva = $idReserva";

    if ($conexion->query($sql)) {
        echo 'todo correcto';
    }
}

$conexion->close();
