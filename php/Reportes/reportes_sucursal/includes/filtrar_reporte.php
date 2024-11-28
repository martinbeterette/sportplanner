<?php
// Variables de los filtros
$tipo_fecha = isset($_POST['tipo_fecha']) ? $_POST['tipo_fecha'] : 'fecha_reserva';
$fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : date('Y-m-01'); // Primer día del mes
$fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : date('Y-m-t');  // Último día del mes
$estado_reserva = isset($_POST['estado_reserva']) ? $_POST['estado_reserva'] : '';

// Construcción dinámica de la consulta SQL
$query = "
    SELECT 
        r.id_reserva, 
        r.fecha_reserva, 
        r.fecha_alta, 
        CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo,
        z.descripcion_zona,
        t.descripcion_tipo_terreno AS tipo_terreno,
        e.descripcion_estado_zona AS estado,
        IF(e.descripcion_estado_zona = 'Finalizado', ta.precio, '-') AS precio
    FROM reserva r
    INNER JOIN zona z ON r.rela_zona = z.id_zona
    INNER JOIN tipo_terreno t ON z.rela_tipo_terreno = t.id_tipo_terreno
    INNER JOIN estado_zona e ON z.rela_estado_zona = e.id_estado_zona
    JOIN reserva ON rela_zona = id_zona
    JOIN estado_reserva ON reserva.rela_estado_reserva = id_estado_reserva
    LEFT JOIN tarifa ta ON z.rela_sucursal = ta.rela_sucursal
    LEFT JOIN persona p ON r.rela_persona = p.id_persona
    WHERE 1=1
";

// Filtro por rango de fechas
if (!empty($fecha_desde) && !empty($fecha_hasta)) {
    $query .= " AND $tipo_fecha BETWEEN '$fecha_desde' AND '$fecha_hasta'";
}

// Filtro por estado de la reserva
if (!empty($estado_reserva)) {
    $query .= " AND z.rela_estado_zona = '$estado_reserva'";
}

// Ordenamiento por tipo de fecha
$query .= " ORDER BY $tipo_fecha DESC";

// Ejecución de la consulta
$result = $conexion->query($query);

// Mostrar los resultados en una tabla
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Fecha Reserva</th><th>Fecha Alta</th><th>Cliente</th><th>Zona</th><th>Tipo Terreno</th><th>Estado</th><th>Precio</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id_reserva']}</td>";
        echo "<td>{$row['fecha_reserva']}</td>";
        echo "<td>{$row['fecha_alta']}</td>";
        echo "<td>{$row['nombre_completo']}</td>";
        echo "<td>{$row['descripcion_zona']}</td>";
        echo "<td>{$row['tipo_terreno']}</td>";
        echo "<td>{$row['estado']}</td>";
        echo "<td>{$row['precio']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados para los filtros seleccionados.";
}
?>
