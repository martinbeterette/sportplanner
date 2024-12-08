<?php  

require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

$registros_por_pagina = 5; 
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

$search = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("Falta ID de sucursal");

$search_param = "%" . $conexion->real_escape_string($search) . "%";

// Consulta principal con filtros
$sql = "SELECT 
            r.id_reserva,
            p.nombre,
            p.apellido,
            z.descripcion_zona,
            ec.descripcion_estado_control,
            c.monto_base,
            c.monto_final,
            c.entrada,
            c.salida
        FROM 
            reserva r
        JOIN 
            persona p ON r.rela_persona = p.id_persona
        JOIN 
            zona z ON r.rela_zona = z.id_zona
        JOIN 
            sucursal s ON z.rela_sucursal = s.id_sucursal
        JOIN 
            control c ON c.rela_reserva = r.id_reserva
        LEFT JOIN 
            estado_control ec ON ec.id_estado_control = c.rela_estado_control
        WHERE 
            s.id_sucursal = ? 
            AND r.fecha_reserva = ?
            AND (p.nombre LIKE ? OR p.apellido LIKE ?)
        ORDER BY r.id_reserva
        LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("isssii", $id_sucursal, $fecha, $search_param, $search_param, $registros_por_pagina, $offset);
$stmt->execute();
$result = $stmt->get_result();

$tabla = '<table class="tabla-reservas">
            <thead>
                <tr>
                    <th>ID Reserva</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cancha</th>
                    <th>Estado</th>
                    <th>Monto Base</th>
                    <th>Monto Final</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';
while ($row = $result->fetch_assoc()) {
    $tabla .= "<tr>
                    <td>{$row['id_reserva']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['apellido']}</td>
                    <td>{$row['descripcion_zona']}</td>
                    <td>{$row['descripcion_estado_control']}</td>
                    <td>{$row['monto_base']}</td>
                    <td>{$row['monto_final']}</td>
                    <td>{$row['entrada']}</td>
                    <td>{$row['salida']}</td>";
    // Botón que llama al modal
    $tabla .= '<td>
        <button 
            data-pagina="'. $pagina_actual .'"
            data-id-sucursal="'. $id_sucursal .'"
            data-id-reserva="'. $row['id_reserva'] .'"
            class="acciones"
            style="background-color: #4CAF50; color: white; border: none; padding: 10px; cursor: pointer;">
            Acciones
        </button>
    </td>';
}
$tabla .= '</tbody></table>';

$sql_count = "SELECT COUNT(r.id_reserva) as total FROM reserva r JOIN zona z ON r.rela_zona = z.id_zona JOIN sucursal s ON z.rela_sucursal = s.id_sucursal WHERE s.id_sucursal = ? AND r.fecha_reserva = ?";
$stmt_count = $conexion->prepare($sql_count);
$stmt_count->bind_param("is", $id_sucursal, $fecha);
$stmt_count->execute();
$total_items = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_items / $registros_por_pagina);

// Envío de respuesta JSON
echo json_encode(["tabla" => $tabla, "total_pages" => $total_pages, "current_page" => $pagina_actual]);

$conexion->close();
?>


