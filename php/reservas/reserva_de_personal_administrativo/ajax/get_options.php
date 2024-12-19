<?php
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

$response = [
    "success" => false,
    "tipo_documento" => [],
    "sexo" => []
];

try {
    // Obtener opciones de tipo documento
    $result = $conexion->query("SELECT id_tipo_documento, descripcion_tipo_documento FROM tipo_documento");
    $response['tipo_documento'] = $result->fetch_all(MYSQLI_ASSOC);

    // Obtener opciones de sexo
    $result = $conexion->query("SELECT id_sexo, descripcion_sexo FROM sexo");
    $response['sexo'] = $result->fetch_all(MYSQLI_ASSOC);

    $response['success'] = true;
} catch (Exception $e) {
    $response['success'] = false;
}

header('Content-Type: application/json');
echo json_encode($response);
