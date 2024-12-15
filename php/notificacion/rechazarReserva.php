<?php
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if (isset($_REQUEST['id_reserva'])) {
    $idReserva = $_REQUEST['id_reserva'];
    $id_usuario = $_REQUEST['id_usuario'];
    $sql = "UPDATE reserva SET rela_estado_reserva = 5 WHERE id_reserva = $idReserva";

    if ($conexion->query($sql)) {
        $sql = "INSERT INTO notificacion(titulo, mensaje, rela_usuario, estado, rela_reserva, categoria) 
        VALUES ('cancelacion', 'se le rechazo la peticion de la reserva', $id_usuario, 'no leido', $idReserva, 'cancelacion')";
        if ($conexion->query($sql)) {
            echo 'todo correcto';
        } else {
            echo $conexion->error;
        }
    } else {
        echo $conexion->error;
    }
}

$conexion->close();
