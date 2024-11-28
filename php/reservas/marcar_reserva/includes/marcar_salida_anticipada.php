<?php
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

function marcarSalidaParcial($id_sucursal, $id_reserva, $observacion) {
    // Usamos la hora actual para la salida (en formato 'HH:MM')
    $horaSalida = date('H:i');
    $estadoAsistencia = 'Parcial';
    
    // Conexión a la base de datos
    global $conexion;

    // Consulta de actualización

    $sql = "UPDATE reserva SET horario_salida = ?, estado_asistencia = ?, observacion = ? WHERE id_reserva = ?";

    // Preparar y ejecutar la consulta
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('sssi', $horaSalida, $estadoAsistencia, $observacion, $id_reserva);

    if ($stmt->execute()) {
        // Si se ejecuta correctamente, redirigir a la página principal
        header("Location: ../listado_reservas.php?id_sucursal=$id_sucursal");
        exit;
    } else {
        // Si hay un error, puedes manejarlo aquí
        echo "Error al marcar la salida parcial: " . $stmt->error;
    }
}

// Obtener parámetros de id_sucursal y id_reserva desde $_REQUEST
$id_sucursal = $_REQUEST['id_sucursal'] ?? null;
$id_reserva = $_REQUEST['id_reserva'] ?? null;
$observacion = $_REQUEST['observacion'] ?? null;

// Llamar a la función si tenemos los parámetros necesarios
if ($id_sucursal && $id_reserva) {
    marcarSalidaParcial($id_sucursal, $id_reserva, $observacion);
} else {
    echo "Parámetros faltantes.";
}
?>
