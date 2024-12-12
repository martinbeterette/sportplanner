<?php
require_once('../../../config/root_path.php');
require_once(RUTA . 'config/database/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_zona = isset($_POST['id_zona']) ? intval($_POST['id_zona']) : null;
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
    $tipo = isset($_POST['tipo_terreno']) ? intval($_POST['tipo_terreno']) : null;
    $formato_deporte = isset($_POST['formato_deporte']) ? intval($_POST['formato_deporte']) : null;
    $estado = isset($_POST['estado']) ? intval($_POST['estado']) : null;

    // Validar datos obligatorios
    if (!$id_zona || !$descripcion || !$tipo || !$formato_deporte || !$estado) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Preparar la consulta
    $sql = "UPDATE zona
            SET 
                descripcion_zona = ?,
                rela_tipo_terreno = ?,
                rela_formato_deporte = ?,
                rela_estado_zona = ?,
                rela_servicio = 1
            WHERE id_zona = ?";

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta.']);
        exit;
    }

    // Vincular parámetros
    $stmt->bind_param("siiii", $descripcion, $tipo, $formato_deporte, $estado, $id_zona);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el registro.']);
    }

    $stmt->close();
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}

$conexion->close();
