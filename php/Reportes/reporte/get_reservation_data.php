<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

header('Content-Type: application/json');

$idSucursal = $_GET['id_Sucursal'];
$period = isset($_GET['period']) ? $_GET['period'] : 'week';
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;

$timeFilter = "";
if ($period === 'custom' && $startDate && $endDate) {
    $timeFilter = "AND (r.fecha_reserva BETWEEN '$startDate' AND '$endDate')";
} else {
    switch ($period) {
        case 'day':
            $interval = 'INTERVAL 1 DAY';
            break;
        case 'week':
            $interval = 'INTERVAL 1 WEEK';
            break;
        case 'month':
            $interval = 'INTERVAL 1 MONTH';
            break;
        case 'year':
            $interval = 'INTERVAL 1 YEAR';
            break;
        default:
            $interval = 'INTERVAL 1 WEEK';
            break;
    }
    $timeFilter = "AND r.fecha_reserva >= DATE_SUB(CURDATE(), $interval)";
}

// Obtener reservas por día de la semana
$queryDays = "
    SELECT DAYNAME(r.fecha_reserva) AS day, COUNT(*) AS total
    FROM reserva r
    JOIN zona z ON r.rela_zona = z.id_zona
    WHERE 1=1 $timeFilter AND z.rela_sucursal = $idSucursal
    GROUP BY DAYNAME(r.fecha_reserva)
    ORDER BY FIELD(DAYNAME(r.fecha_reserva), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
";

$resultDays = $conexion->query($queryDays);
$data['days'] = ['labels' => [], 'values' => []];
while ($row = $resultDays->fetch_assoc()) {
    $data['days']['labels'][] = $row['day'];
    $data['days']['values'][] = $row['total'];
}

// Obtener las canchas más reservadas, con el estado y la ganancia
$queryCourts = "
    SELECT z.descripcion_zona AS cancha,
           COUNT(r.rela_zona) AS Reserva_total,
           SUM(CASE WHEN co.rela_estado_control = 3 THEN 1 ELSE 0 END) AS Finalizados,
           SUM(CASE WHEN co.rela_estado_control = 4 THEN 1 ELSE 0 END) AS Ausente,
           SUM(CASE WHEN co.rela_estado_control = 5 THEN 1 ELSE 0 END) AS Cancelado,
           SUM(co.monto_final) AS recaudacion_por_cancha
    FROM reserva r
    JOIN zona z ON r.rela_zona = z.id_zona
    JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
    JOIN estado_reserva er ON r.rela_estado_reserva = er.id_estado_reserva
    JOIN control co ON co.rela_reserva = r.id_reserva
    WHERE 1=1 $timeFilter AND z.rela_sucursal = $idSucursal
    GROUP BY z.descripcion_zona
";

$resultCourts = $conexion->query($queryCourts);
$data['courts'] = ['labels' => [], 'Reserva_total' => [], 'Finalizados' => [], 'Ausente' => [], 'Cancelado' => [], 'recaudacion_por_cancha' => []];
while ($row = $resultCourts->fetch_assoc()) {
    $data['courts']['labels'][] = $row['cancha'];
    $data['courts']['Reserva_total'][] = $row['Reserva_total'];
    $data['courts']['Finalizados'][] = $row['Finalizados'];
    $data['courts']['Ausente'][] = $row['Ausente'];
    $data['courts']['Cancelado'][] = $row['Cancelado'];
    $data['courts']['recaudacion_por_cancha'][] = $row['recaudacion_por_cancha'];
}

// Obtener información sobre las tarifas
$queryTarifas = "
    SELECT 
        COUNT(c.id_control) AS total_reserva, 
        t.descripcion_tarifa, 
        SUM(c.monto_final) AS recaudacion_total
    FROM control c 
    JOIN tarifa t ON c.rela_tarifa = t.id_tarifa
    JOIN reserva r ON c.rela_reserva = r.id_reserva
    WHERE 1=1 $timeFilter AND r.rela_zona IN (
        SELECT id_zona FROM zona WHERE rela_sucursal = $idSucursal
    )
    GROUP BY t.descripcion_tarifa
";

$resultTarifas = $conexion->query($queryTarifas);
$data['tarifas'] = ['labels' => [], 'total_reserva' => []];
while ($row = $resultTarifas->fetch_assoc()) {
    $data['tarifas']['labels'][] = $row['descripcion_tarifa'];
    $data['tarifas']['total_reserva'][] = $row['total_reserva'];
}


echo json_encode($data);
