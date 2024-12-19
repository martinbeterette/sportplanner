<?php
require_once('../../../config/root_path.php');
require_once(RUTA . 'config/database/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_zona = isset($_POST['idZona']) ? intval($_POST['idZona']) : null;
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
    $tipo = isset($_POST['tipoTerreno']) ? intval($_POST['tipoTerreno']) : null;
    $formato_deporte = isset($_POST['formatoDeporte']) ? intval($_POST['formatoDeporte']) : null;
    $estado = isset($_POST['estado']) ? intval($_POST['estado']) : null;

    // Validar datos obligatorios
    if (!$id_zona || !$descripcion || !$tipo || !$formato_deporte || !$estado) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
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
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta.']);
        exit;
    }

    // Vincular parámetros
    $stmt->bind_param("siiii", $descripcion, $tipo, $formato_deporte, $estado, $id_zona);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cancha modificada correctamente.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el registro.']);
    }

    $stmt->close();
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}

$conexion->close();
