<?php
// Conectar a la base de datos
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");


$registros_por_pagina = 10;

if (isset($_GET['pagina']) && is_numeric($_GET['pagina'])) {
    $pagina_actual = (int)$_GET['pagina'];
} else {
    $pagina_actual = 1;
}

$offset = ($pagina_actual - 1) * $registros_por_pagina;

// obtenemos el filtro
$search = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$search_param = "%" . $conexion->real_escape_string($search) . "%";

$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("falta get de sucursal");
$fecha_desde = $_GET['fecha_desde'] ?? date('Y-m-d', strtotime('-1 month'));
$fecha_hasta = $_GET['fecha_hasta'] ?? date('Y-m-d');
if (!$fecha_desde || $fecha_desde == '') {
    $fecha_desde = date('Y-m-d', strtotime('-1 month'));
}

if (!$fecha_hasta || $fecha_hasta == '') {
    $fecha_hasta = date('Y-m-d');
}

// consulta para los registros
$sql = "SELECT * 
        FROM reserva r
        JOIN zona z ON r.rela_zona = z.id_zona
        JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
        JOIN control con ON con.rela_reserva = r.id_reserva
        WHERE id_sucursal = ? AND fecha_reserva BETWEEN ? AND ?
        ORDER BY fecha_reserva DESC
        LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "issii",
    $id_sucursal,
    $fecha_desde,
    $fecha_hasta,
    $registros_por_pagina,
    $offset
);
$stmt->execute();
$result = $stmt->get_result();

//creamos la tabla
$tabla = '<table border="1">
                <tr>
                <th>ID Reserva</th>
                <th>Fecha Reserva</th>
                <th>Zona</th>
                <th>Sucursal</th>
                <th colspan="2">acciones</th>
            </tr>';
while ($row = $result->fetch_assoc()) {
    $tabla .= '<tr>';
    $tabla .= '<td>' . htmlspecialchars($row['id_reserva']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['fecha_reserva']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_zona']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_sucursal']) . '</td>';

    $tabla .= '<td class="acciones">' . '<a href="formulario_creacion_empleado/includes/modificar.php?id_empleado=' . $row['id_reserva'] . '&id_sucursal=' . $id_sucursal . '">
                    <img src="' . BASE_URL . 'assets/icons/icons8-editar.svg' . '"></a>' .
        '</td>';

    $tabla .= '<td class="acciones">' . '<accion href="" class="eliminar" id_sucursal="' . $id_sucursal . '" valor="' . $row['id_reserva'] . '"><img src="' . BASE_URL . 'assets/icons/icons8-basura-llena.svg' . '"></accion>' . '</td>';

    $tabla .= '</tr>';
}

$tabla .= '</table>';

// contar el número total de registros
$sql_count = "SELECT COUNT(*) AS total 
                FROM reserva r
                JOIN zona z ON r.rela_zona = z.id_zona
                JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
                JOIN control con ON con.rela_reserva = r.id_reserva
                WHERE id_sucursal = ? AND fecha_reserva BETWEEN ? AND ?";
$stmt_count = $conexion->prepare($sql_count);
$stmt_count->bind_param(
    "iss",
    $id_sucursal,
    $fecha_desde,
    $fecha_hasta
);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_items = $result_count->fetch_assoc()['total'];
$total_pages = $total_items > 0 ? ceil($total_items / $registros_por_pagina) : 0;

// header("content-type: application/json");

// devolver la tabla como JSON
echo json_encode(array(
    "tabla" => $tabla,
    "total_pages" => $total_pages,
    "current_page" => $pagina_actual,
    "total_registros" => $total_items
));

$conexion->close();
