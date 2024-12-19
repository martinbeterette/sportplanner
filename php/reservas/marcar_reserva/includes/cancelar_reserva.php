<?php
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once("../includes/functions.php");

function cancelarReserva($id_sucursal, $id_reserva, $observacion, $pagina_actual) {
    // Conexión a la base de datos
    global $conexion;

    // Comenzamos una transacción
    $conexion->begin_transaction();

    try {
        // Obtener el id_usuario relacionado con la reserva
        $sqlUsuario = "SELECT rela_usuario FROM notificacion JOIN reserva ON rela_reserva = id_reserva WHERE id_reserva = ? LIMIT  1";
        $stmtUsuario = $conexion->prepare($sqlUsuario);
        $stmtUsuario->bind_param('i', $id_reserva);
        $stmtUsuario->execute();
        $resultUsuario = $stmtUsuario->get_result();
        $id_usuario = $resultUsuario->fetch_assoc()['rela_usuario'];

        if (!$id_usuario) {
            // throw new Exception("No se encontró un usuario relacionado con la reserva.");
        }

        // Actualizar la tabla reserva
        $sqlReserva = "UPDATE reserva 
                       SET rela_estado_reserva = 5, descripcion_reserva = ? 
                       WHERE id_reserva = ?";
        $stmtReserva = $conexion->prepare($sqlReserva);
        $stmtReserva->bind_param('si', $observacion, $id_reserva);
        $stmtReserva->execute();

        // Actualizar la tabla control
        $sqlControl = "UPDATE control 
                       SET rela_estado_control = 6 
                       WHERE rela_reserva = ?";
        $stmtControl = $conexion->prepare($sqlControl);
        $stmtControl->bind_param('i', $id_reserva);
        $stmtControl->execute();

        // Insertar en la tabla notificación
        $mensajeNotificacion = "Le notificamos que su reserva ha sido cancelada por el siguiente motivo: $observacion";
        $tituloNotificacion = "Cancelación de reserva";
        $categoria = "cancelacion";

        if(!$id_usuario) {
            
            $sqlNotificacion = "INSERT INTO notificacion (titulo, mensaje, rela_usuario,rela_reserva, categoria) 
                                VALUES (?, ?, ?, ?,?)";
            $stmtNotificacion = $conexion->prepare($sqlNotificacion);
            $stmtNotificacion->bind_param('ssiis', $tituloNotificacion, $mensajeNotificacion, $id_usuario,$id_reserva, $categoria);
            $stmtNotificacion->execute();
        }

        // Confirmar transacción
        $conexion->commit();

        // Redirigir a la página principal
        header("Location: ../listado_reservas.php?id_sucursal=$id_sucursal&pagina_actual=$pagina_actual");
        exit;
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        echo "Error al cancelar la reserva: " . $e->getMessage();
    }
}

// Obtener parámetros de id_sucursal, id_reserva, observacion y pagina_actual desde $_REQUEST
$id_sucursal = filter_input(INPUT_GET, 'id_sucursal', FILTER_SANITIZE_NUMBER_INT) ?? null;
$id_reserva = filter_input(INPUT_GET, 'id_reserva', FILTER_SANITIZE_NUMBER_INT) ?? null;
$observacion = filter_input(INPUT_GET, 'observacion', FILTER_SANITIZE_STRING) ?? null;
$pagina_actual = filter_input(INPUT_GET, 'pagina_actual', FILTER_SANITIZE_NUMBER_INT) ?? 1;

// Validar que los parámetros requeridos estén presentes
if ($id_sucursal && $id_reserva && $observacion) {
    cancelarReserva($id_sucursal, $id_reserva, $observacion, $pagina_actual);
} else {
    echo "Parámetros faltantes o inválidos.";
}
?>
