<?php

require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reserva = $_POST['id_reserva'];
    $motivo_cancelacion = $_POST['motivo_cancelacion'];

    $id_sucursal = "SELECT rela_sucursal FROM zona JOIN reserva ON rela_zona = id_zona WHERE id_reserva = $id_reserva";
    $id_sucursal = $conexion->query($id_sucursal)->fetch_assoc()['rela_sucursal'];

    // Iniciar la transacción
    $conexion->begin_transaction();

    try {
        // Actualizar la descripción de la reserva
        $queryUpdateReserva = "UPDATE reserva SET descripcion_reserva = ?, rela_estado_reserva = 5 WHERE id_reserva = ?";
        $stmtReserva = $conexion->prepare($queryUpdateReserva);
        $stmtReserva->bind_param('si', $motivo_cancelacion, $id_reserva);
        $stmtReserva->execute();

        if ($stmtReserva->affected_rows === 0) {
            throw new Exception("No se pudo actualizar la reserva. Verifica el ID.");
        }

        // Actualizar la categoría en la tabla notificación
        $queryUpdateNotificacion = "UPDATE notificacion SET categoria = 'cancelacion', titulo = 'Reserva Cancelada', rela_sucursal = ? WHERE rela_reserva = ?";
        $stmtNotificacion = $conexion->prepare($queryUpdateNotificacion);
        $stmtNotificacion->bind_param('ii', $id_sucursal, $id_reserva);
        $stmtNotificacion->execute();

        if ($stmtNotificacion->affected_rows === 0) {
            throw new Exception("No se pudo actualizar la notificación. Verifica el ID.");
        }

        // Confirmar la transacción
        $conexion->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Revertir los cambios en caso de error
        $conexion->rollback();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    } finally {
        $stmtReserva->close();
        $stmtNotificacion->close();
        $conexion->close();
    }
}
