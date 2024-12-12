<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

header('Content-Type: application/json');

// Obtén los parámetros de la solicitud GET
$fechaDesde = isset($_GET['fechaDesde']) ? $_GET['fechaDesde'] : null;
$fechaHasta = isset($_GET['fechaHasta']) ? $_GET['fechaHasta'] : null;

error_log("Fecha desde: " . $fechaDesde); // Muestra en los logs de PHP
error_log("Fecha hasta: " . $fechaHasta);

// Verifica si las fechas son válidas
if ($fechaDesde && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaDesde)) {
    echo json_encode(["error" => "Formato de fecha desde no válido"]);
    exit;
}
if ($fechaHasta && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaHasta)) {
    echo json_encode(["error" => "Formato de fecha hasta no válido"]);
    exit;
}

// Consulta base
$query = "SELECT 
            s.descripcion_sucursal AS sucursal,
            COUNT(co.id_control) AS Reserva_total,
            SUM(CASE WHEN co.rela_estado_control = 3 THEN 1 ELSE 0 END) AS Finalizados,
            SUM(CASE WHEN co.rela_estado_control = 4 THEN 1 ELSE 0 END) AS Ausente,
            SUM(CASE WHEN co.rela_estado_control = 5 THEN 1 ELSE 0 END) AS Cancelado,
            SUM(co.monto_final) AS recaudacion_por_sucursal,
            COUNT(DISTINCT e.id_empleado) AS total_empleado
          FROM reserva r
          JOIN zona z ON r.rela_zona = z.id_zona
          JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
          JOIN complejo c ON s.rela_complejo = c.id_complejo
          JOIN empleado e ON e.rela_sucursal = s.id_sucursal
          JOIN estado_reserva er ON r.rela_estado_reserva = er.id_estado_reserva
          JOIN control co ON co.rela_reserva = r.id_reserva
          WHERE c.id_complejo = 1";

// Agrega condiciones dinámicamente si existen fechas
if ($fechaDesde) {
    $query .= " AND r.fecha >= ?";
}
if ($fechaHasta) {
    $query .= " AND r.fecha <= ?";
}

// Agrega la cláusula GROUP BY
$query .= " GROUP BY s.id_sucursal";

$stmt = $conexion->prepare($query);

// Vincula los parámetros si existen fechas
$paramIndex = 1;
if ($fechaDesde) {
    $stmt->bind_param("s", $fechaDesde);
    $paramIndex++;
}
if ($fechaHasta) {
    $stmt->bind_param("s", $fechaHasta);
}

// Ejecuta la consulta
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Devuelve los datos en formato JSON
echo json_encode($data);

$stmt->close();
$conexion->close();
