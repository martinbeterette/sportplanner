<?php
// Conectar a la base de datos
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

$registros_por_pagina = 5;

// Validar página actual
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el filtro
$search = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$search_param = "%" . $conexion->real_escape_string($search) . "%";

// Consulta principal
$sql = "
SELECT
    p.id_persona,
    u.username,
    u.estado,
    p.nombre,
    p.apellido,
    CASE 
        WHEN p.estado = 1 THEN 'Activo'
        WHEN p.estado = 0 THEN 'Inactivo'
    END AS estado_persona_nombre,
    p.estado as estado_persona,
    c.descripcion_contacto
FROM contacto c
JOIN persona p ON p.id_persona = c.rela_persona
JOIN usuarios u ON c.id_contacto = u.rela_contacto
WHERE (
    p.nombre LIKE ? 
    OR p.apellido LIKE ? 
    OR u.username LIKE ? 
    OR c.descripcion_contacto LIKE ?
)
ORDER BY p.id_persona
LIMIT ? OFFSET ?
";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die(json_encode(["error" => "Error en la preparación de la consulta: " . $conexion->error]));
}

$stmt->bind_param(
    "ssssii",   
    $search_param,  // %nombre%
    $search_param,  // %apellido%
    $search_param,  // %username%
    $search_param,  // %descripcion_contacto%
    $registros_por_pagina,  // cantidad de registros por página
    $offset  // desplazamiento de la consulta (paginación)
);

$stmt->execute();
$result = $stmt->get_result();

// Generar la tabla
$tabla = '<table border="1">
    <tr>
        <th>ID Usuario</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Usuario</th>
        <th>Contacto</th>
        <th>Estado Persona</th>
        <th>Estado</th>
        <th colspan="2">Acciones</th>
    </tr>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tabla .= '<tr>';
        $tabla .= '<td>' . htmlspecialchars($row['id_persona']) . '</td>';
        $tabla .= '<td>' . htmlspecialchars($row['nombre']) . '</td>';
        $tabla .= '<td>' . htmlspecialchars($row['apellido']) . '</td>';
        $tabla .= '<td>' . htmlspecialchars($row['username']) . '</td>';
        $tabla .= '<td>' . htmlspecialchars($row['descripcion_contacto']) . '</td>';
        $tabla .= '<td>' . htmlspecialchars($row['estado_persona_nombre']) . '</td>';
        $tabla .= '<td>' . htmlspecialchars($row['estado']) . '</td>';
        $tabla .= '
        <td>
        <span class="eliminar" id="' . htmlspecialchars($row['id_persona']) . '"data-estado="' . htmlspecialchars($row['estado_persona']) . '">
        <img src="HTTP://localhost/PROYECTO_PP2_2024/assets/icons/icons8-basura-llena.svg">
    </span>
        </td>
    ';
        $tabla .= '</tr>';
    }
} else {
    $tabla .= '<tr><td colspan="9">No se encontraron resultados.</td></tr>';
}

$tabla .= '</table>';

// Contar el número total de registros
$sql_count = "
    SELECT COUNT(p.id_persona) AS total
    FROM contacto c
    JOIN persona p ON p.id_persona = c.rela_persona
    JOIN usuarios u ON c.id_contacto = u.rela_contacto
    WHERE (
        p.nombre LIKE ?
        OR p.apellido LIKE ?
        OR u.username LIKE ?
        OR c.descripcion_contacto LIKE ?
    )
    AND p.estado = 1
";

$stmt_count = $conexion->prepare($sql_count);

if (!$stmt_count) {
    die(json_encode(["error" => "Error en la preparación del conteo: " . $conexion->error]));
}

$stmt_count->bind_param(
    "ssss",
    $search_param,
    $search_param,
    $search_param,
    $search_param
);

$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_items = $result_count->fetch_assoc()['total'] ?? 0;
$total_pages = $total_items > 0 ? ceil($total_items / $registros_por_pagina) : 0;

// Respuesta JSON
header("Content-Type: application/json");
echo json_encode([
    "tabla" => $tabla,
    "total_pages" => $total_pages,
    "current_page" => $pagina_actual
]);

// Cerrar conexión
$stmt->close();
$stmt_count->close();
$conexion->close();
