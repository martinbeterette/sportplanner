<?php
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if (isset($_REQUEST['id_reserva'])) {
    $idReserva = $_REQUEST['id_reserva'];
    $id_usuario = $_REQUEST['id_usuario'];
    $id_horario = $_REQUEST['id_horario'];
    $fecha_reserva = $_REQUEST['fecha_reserva'];

    $sql = "SELECT COUNT(*) as total 
            FROM reserva 
            WHERE rela_horario = $id_horario AND fecha_reserva = '$fecha_reserva' AND rela_estado_reserva = 1";

    if ($registros = $conexion->query($sql)) {
        if ($registros->fetch_assoc()['total'] == 0) {
            $sql = "UPDATE reserva SET rela_estado_reserva = 1 WHERE id_reserva = $idReserva";
            if ($conexion->query($sql)) {
                $sql = "INSERT INTO notificacion(titulo, mensaje, rela_usuario, estado, rela_reserva, categoria) 
                        VALUES ('confirmacion', 'se confirmo la reserva', $id_usuario, 'no leido', $idReserva, 'confirmacion')";
                if ($conexion->query($sql)) {
                    echo 'todo correcto';
                } else {
                    echo $conexion->error;
                }
            } else {
                echo $conexion->error;
            }
        } else {
            echo 'horario ocupado';
        }
    } else {
        echo $conexion->error;
    }
}

$conexion->close();
