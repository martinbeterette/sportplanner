<?php
require_once("../../../../config/root_path.php");
require_once(RUTA ."config/database/conexion.php");

// Variables de paginación
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$porPagina = 10; // Registros por página
$inicio = ($pagina - 1) * $porPagina;

// Consulta con límite y offset
$sql = "SELECT DISTINCT zona.*, sucursal.descripcion_sucursal 
        FROM zona
        JOIN sucursal ON zona.rela_sucursal = id_sucursal
        JOIN tarifa ON tarifa.rela_sucursal = id_sucursal
        JOIN itinerario ON itinerario.rela_sucursal = id_sucursal
        WHERE zona.estado = 1 
        LIMIT $inicio, $porPagina";
$resultado = mysqli_query($conexion, $sql);

// Total de registros
$sqlTotal = "SELECT COUNT(DISTINCT zona.id_zona) AS total 
             FROM zona
             JOIN sucursal ON zona.rela_sucursal = id_sucursal
             JOIN tarifa ON tarifa.rela_sucursal = id_sucursal
             JOIN itinerario ON itinerario.rela_sucursal = id_sucursal
             WHERE zona.estado = 1";
$totalResultado = mysqli_fetch_assoc(mysqli_query($conexion, $sqlTotal));
$totalRegistros = $totalResultado['total'];

// Calcular total de páginas
$totalPaginas = ceil($totalRegistros / $porPagina);

// Preparar datos
$data = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $data[] = $fila;
}

// Respuesta JSON
header('Content-Type: application/json');
echo json_encode([
    'data' => $data,
    'totalPages' => $totalPaginas
]);
?>
