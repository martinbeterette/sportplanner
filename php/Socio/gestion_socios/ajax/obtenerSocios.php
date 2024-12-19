<?php
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

$registros_por_pagina = 10; // Registros por página

// Página actual
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Filtro
$search = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$search_param = "%" . $conexion->real_escape_string($search) . "%";

// ID del complejo
$id_complejo = isset($_GET['id_complejo']) ? $_GET['id_complejo'] : die("Falta el parámetro 'id_complejo'");

// Consulta principal
$sql = "
    SELECT 
        p.nombre, 
        p.apellido, 
        d.descripcion_documento AS documento, 
        td.descripcion_tipo_documento AS tipo_documento, 
        s.id_socio, 
        s.fecha_expiracion, 
        m.descripcion_membresia
    FROM socio s
    JOIN persona p ON s.rela_persona = p.id_persona
    JOIN documento d ON d.rela_persona = p.id_persona
    JOIN tipo_documento td ON d.rela_tipo_documento = td.id_tipo_documento
    JOIN membresia m ON s.rela_membresia = m.id_membresia
    WHERE s.rela_complejo = ? 
        AND (
            p.nombre LIKE ? OR 
            p.apellido LIKE ? OR 
            d.descripcion_documento LIKE ? OR 
            m.descripcion_membresia LIKE ?
        )
        AND s.estado IN (1)
    ORDER BY s.id_socio
    LIMIT ? OFFSET ?
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("issssii", 
    $id_complejo, 
    $search_param, 
    $search_param, 
    $search_param, 
    $search_param, 
    $registros_por_pagina, 
    $offset
);
$stmt->execute();
$result = $stmt->get_result();

// Construir la tabla
$tabla = '<table border="1">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Documento</th>
            <th>Membresía</th>
            <th>Expiración</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>';
while ($row = $result->fetch_assoc()) {
    $tabla .= '<tr>';
    $tabla .= '<td>' . htmlspecialchars($row['nombre']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['apellido']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['documento']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_membresia']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['fecha_expiracion']) . '</td>';
    $tabla .= '<td>
                <button class="btn btn-modificar" onclick="location.href=`includes/modificar.php?id_socio=' . $row['id_socio'] . '&id_complejo=' . $id_complejo .'`">Modificar</button>
                <button class="btn btn-eliminar" onclick="eliminarSocio('.htmlspecialchars($row['id_socio']).', '.htmlspecialchars($id_complejo).')">Eliminar</button>
               </td>';
    $tabla .= '</tr>';
}
$tabla .= '</tbody></table>';

// Consulta para el conteo total de registros
$sql_count = "
    SELECT COUNT(s.id_socio) AS total 
    FROM socio s
    JOIN persona p ON s.rela_persona = p.id_persona
    JOIN documento d ON d.rela_persona = p.id_persona
    JOIN tipo_documento td ON d.rela_tipo_documento = td.id_tipo_documento
    JOIN membresia m ON s.rela_membresia = m.id_membresia
    WHERE s.rela_complejo = ? 
        AND (
            p.nombre LIKE ? OR 
            p.apellido LIKE ? OR 
            d.descripcion_documento LIKE ? OR 
            m.descripcion_membresia LIKE ?
        )
        AND s.estado IN (1)
";
$stmt_count = $conexion->prepare($sql_count);
$stmt_count->bind_param("issss", 
    $id_complejo, 
    $search_param, 
    $search_param, 
    $search_param, 
    $search_param
);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_items = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_items / $registros_por_pagina);

// Devolver como JSON
echo json_encode([
    "tabla" => $tabla,
    "total_pages" => $total_pages,
    "current_page" => $pagina_actual
]);

$conexion->close();
?>
