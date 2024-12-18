<?php
session_start();
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

header('Content-Type: application/json');

if (!isset($_POST['id_reserva'])) {
    echo json_encode(['error' => 'ID de reserva no proporcionado.']);
    exit;
}

$idReserva = intval($_POST['id_reserva']); // Validar como entero
$username = $_SESSION['usuario'];

// Obtener la sucursal asociada a la reserva
$sucursalQuery = "SELECT s.id_sucursal FROM sucursal s 
                  JOIN zona z ON z.rela_sucursal = s.id_sucursal 
                  JOIN reserva r ON r.rela_zona = z.id_zona 
                  WHERE r.id_reserva = ?";
$stmt = $conexion->prepare($sucursalQuery);
$stmt->bind_param("i", $idReserva);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $idSucursal = $result->fetch_assoc()['id_sucursal'];

    // Actualizar el estado de la reserva a "Cancelado"
    $updateQuery = "UPDATE reserva SET rela_estado_reserva = 5 WHERE id_reserva = ?";
    $stmt = $conexion->prepare($updateQuery);
    $stmt->bind_param("i", $idReserva);

    if ($stmt->execute()) {
        // Crear una notificación para la sucursal
        $notificacionQuery = "INSERT INTO notificacion (titulo, mensaje, rela_sucursal) 
                              VALUES ('Cancelación', ?, ?)";
        $mensaje = "El usuario $username ha cancelado su reserva.";
        $stmt = $conexion->prepare($notificacionQuery);
        $stmt->bind_param("si", $mensaje, $idSucursal);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'No se pudo cancelar la reserva.']);
    }
} else {
    echo json_encode(['error' => 'Reserva no encontrada.']);
}

$stmt->close();
$conexion->close();
