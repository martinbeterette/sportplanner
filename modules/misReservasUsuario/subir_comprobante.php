<?php
session_start();
require_once("../../config/root_path.php");
require_once(RUTA."config/database/conexion.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reserva = $_POST['id_reserva'];
    $comprobante = $_FILES['comprobante'];

    // Validar archivo PDF
    if ($comprobante['type'] !== 'application/pdf') {
        echo json_encode(['error' => 'Solo se permiten archivos PDF.']);
        exit;
    }

    // Crear carpeta si no existe
    $ruta_carpeta = 'comprobantes/';
    if (!is_dir($ruta_carpeta)) {
        mkdir($ruta_carpeta, 0777, true);
    }

    // Guardar archivo
    $ruta_archivo = $ruta_carpeta . 'comprobante_' . $id_reserva . '.pdf';
    if (move_uploaded_file($comprobante['tmp_name'], $ruta_archivo)) {
        // Guardar ruta en la base de datos
        if ($conexion->connect_error) {
            echo json_encode(['error' => 'Error de conexión a la base de datos.']);
            exit;
        }

        $stmt = $conexion->prepare("UPDATE reserva SET comprobante = ? WHERE id_reserva = ?");
        $stmt->bind_param('si', $ruta_archivo, $id_reserva);
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Comprobante subido correctamente.']);
        } else {
            echo json_encode(['error' => 'Error al guardar en la base de datos.']);
        }

        $stmt->close();
        $conexion->close();
    } else {
        echo json_encode(['error' => 'Error al subir el archivo.']);
    }
}
?>
