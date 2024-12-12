<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

// Obtener los tipos de terreno
$sql_terrenos = "SELECT id_tipo_terreno, descripcion_tipo_terreno FROM tipo_terreno";
$terrenos = $conexion->query($sql_terrenos);

// Obtener los formatos de deporte
$sql_formatos = "SELECT id_formato_deporte, descripcion_formato_deporte FROM formato_deporte";
$formatos = $conexion->query($sql_formatos);

// Obtener los estados
$sql_estados = "SELECT id_estado_zona, descripcion_estado_zona FROM estado_zona";
$estados = $conexion->query($sql_estados);

$data = [
    'terrenos' => $terrenos->fetch_all(MYSQLI_ASSOC),
    'formatos' => $formatos->fetch_all(MYSQLI_ASSOC),
    'estados' => $estados->fetch_all(MYSQLI_ASSOC),
];

header('Content-Type: application/json');
echo json_encode($data);
