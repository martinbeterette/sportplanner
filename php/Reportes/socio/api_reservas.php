<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

header('Content-Type: application/json');

$fechaDesde = $_GET['fechaDesde'] ?? null;
$fechaHasta = $_GET['fechaHasta'] ?? null;

// Filtros de fecha
$whereClause = "";
if ($fechaDesde && $fechaHasta) {
    $whereClause = "AND fecha BETWEEN '$fechaDesde' AND '$fechaHasta'";
}

// Consultas
// 1. Socios con más reservas
$querySocios = "
    SELECT COUNT(*) AS total_reservas, 
            nombre, 
            apellido, 
            d.descripcion_documento, 
            m.descripcion_membresia, 
            s.fecha_expiracion, 
            s.fecha_afiliacion
    FROM socio s JOIN persona p ON s.rela_persona = p.id_persona
    JOIN reserva r ON p.id_persona = r.rela_persona
    JOIN documento d ON d.rela_persona = p.id_persona
    JOIN membresia m ON m.id_membresia = s.rela_membresia 
    WHERE s.rela_complejo = 1 $whereClause 
    LIMIT 10
";

$resultSocios = $conexion->query($querySocios);
$socios = [];
while ($row = $resultSocios->fetch_assoc()) {
    $socios[] = $row;
}

// 2. Membresías más solicitadas
$queryMembresias = "
    SELECT  m.descripcion_membresia,
            m.descuento,
            m.precio_membresia,
            COUNT(*) AS total_socios
    FROM socio s
    JOIN membresia m ON s.rela_membresia = m.id_membresia
    JOIN complejo c ON s.rela_complejo = c.id_complejo
    WHERE c.id_complejo = 1
    GROUP BY s.rela_membresia
    LIMIT 5
";

$resultMembresias = $conexion->query($queryMembresias);
$membresias = [];
while ($row = $resultMembresias->fetch_assoc()) {
    $membresias[] = $row;
}

// Enviar datos como JSON
echo json_encode([
    'socios' => $socios,
    'membresias' => $membresias,
]);

$conexion->close();
