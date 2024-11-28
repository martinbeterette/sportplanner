<?php
require_once("../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");

if (isset($_GET['completar_pago'])) {
    // die("falta_pagar");
    $id_reserva = $_GET['completar_pago'];

    // Actualizar monto pagado al monto total y cambiar el estado de la reserva a 2
    $query = "UPDATE reserva SET monto_pagado = monto_total, rela_estado_reserva = 2 WHERE id_reserva = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_reserva);
    $stmt->execute();

    // Redirigir al listado
    header("Location: listado_reservas.php");
    exit();
}

if (isset($_GET['pago_completo'])) {
    // die("pago_completo");
    $id_reserva = $_GET['pago_completo'];

    // Cambiar solo el estado de la reserva a 2
    $query = "UPDATE reserva SET rela_estado_reserva = 2 WHERE id_reserva = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_reserva);
    $stmt->execute();

    // Redirigir al listado
    header("Location: listado_reservas.php");
    exit();
}
?>
