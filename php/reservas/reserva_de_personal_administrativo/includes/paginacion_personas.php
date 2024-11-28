<?php
require_once("../../../../config/database/conexion.php");

header('Content-Type: application/json');

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registrosPorPagina = 10; // Cantidad de registros por página
$offset = ($pagina - 1) * $registrosPorPagina;

// Consulta total de registros para calcular el número de páginas
$sqlTotal = "SELECT COUNT(*) as total FROM persona";
$resultTotal = $conexion->query($sqlTotal);

if ($resultTotal && $filaTotal = $resultTotal->fetch_assoc()) {
    $totalRegistros = (int) $filaTotal['total'];
    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);
} else {
    echo json_encode(['error' => 'Error al contar registros']);
    exit;
}

// Consulta con límite y offset para paginación
$sql = "SELECT id_persona, nombre, apellido FROM persona LIMIT $registrosPorPagina OFFSET $offset";
$result = $conexion->query($sql);

$personas = [];
if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        $personas[] = $fila;
    }
}

echo json_encode([
    'data' => $personas,
    'pagina_actual' => $pagina,
    'total_paginas' => $totalPaginas
]);
?>
