<?php
// Conectar a la base de datos
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

$registros_por_pagina = 5; 

if (isset($_GET['pagina']) && is_numeric($_GET['pagina'])) {
    $pagina_actual = (int)$_GET['pagina'];
} else {
    $pagina_actual = 1;
}

$offset = ($pagina_actual - 1) * $registros_por_pagina;

// obtenemos el filtro
$search = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$search_param = "%" . $conexion->real_escape_string($search) . "%";

$id_complejo = isset($_GET['id_complejo']) ? $_GET['id_complejo'] : die("falta get de complejo");

// consulta para los registros
$sql = "SELECT 
                membresia.id_membresia, 
                membresia.descripcion_membresia, 
                membresia.descuento, 
                membresia.precio_membresia, 
                complejo.descripcion_complejo
            FROM 
                membresia
            JOIN 
                complejo 
            ON 
                membresia.rela_complejo = complejo.id_complejo
            WHERE (descripcion_membresia LIKE ? OR descuento LIKE ?)
            AND membresia.rela_complejo = ?
            ORDER BY membresia.id_membresia
            LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssiii", 
                    $search_param, 
                    $search_param, 
                    $id_complejo, 
                    $registros_por_pagina, 
                    $offset);
$stmt->execute();
$result = $stmt->get_result();

// crear la tabla
$tabla = '<table border="1">
                <tr>
                <th>ID Membresía</th>
                <th>Descripción</th>
                <th>Descuento (%)</th>
                <th>Precio</th>
                <th colspan="2">Acciones</th>
            </tr>';
while ($row = $result->fetch_assoc()) {
    $tabla .= '<tr>';
    $tabla .= '<td>' . htmlspecialchars($row['id_membresia']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_membresia']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descuento']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['precio_membresia']) . '</td>';
    
    // Botón para modificar
    $tabla .= '<td class="acciones">
                <a href="formulario_modificar_membresia.php?id_membresia=' . $row['id_membresia'] . '&id_complejo='. $id_complejo .'" class="editar">
                Modificar</a>
                </td>';

    // Botón para eliminar
    $tabla .= '<td class="acciones">
                <accion href="" class="eliminar" id_complejo="' . $id_complejo . '" valor="' . $row['id_membresia'] . '" class="eliminar">
                eliminar
                </td>';

    $tabla .= '</tr>';
}

$tabla .= '</table>';

// contar el número total de registros
$sql_count = "SELECT COUNT(id_membresia) as total 
              FROM membresia
              WHERE (descripcion_membresia LIKE ? OR descuento LIKE ?)
              AND rela_complejo = ?";

$stmt_count = $conexion->prepare($sql_count);
$stmt_count->bind_param("ssi", 
                        $search_param, 
                        $search_param, 
                        $id_complejo);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_items = $result_count->fetch_assoc()['total'];
$total_pages = $total_items > 0 ? ceil($total_items / $registros_por_pagina) : 0;

// devolver la tabla como JSON
echo json_encode(array(
    "tabla" => $tabla,
    "total_pages" => $total_pages,
    "current_page" => $pagina_actual
));

$conexion->close();
?>
