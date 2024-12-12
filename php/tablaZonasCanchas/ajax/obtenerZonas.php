<?php
// Conectar a la base de datos
require_once("../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");



$registros_por_pagina = isset($_GET['registros_por_pagina']) ? $_GET['registros_por_pagina'] : 5; 

if (isset($_GET['pagina']) && is_numeric($_GET['pagina'])) {
    $pagina_actual = (int)$_GET['pagina'];
} else {
    $pagina_actual = 1;
}

$offset = ($pagina_actual - 1) * $registros_por_pagina;

// obtenemos el filtro
$search = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$search_param = "%" . $conexion->real_escape_string($search) . "%";

$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("falta get de id_sucursal");

// consulta para los registros
$sql = "SELECT  
                        id_zona,
                        descripcion_zona,
                        descripcion_tipo_terreno,
                        descripcion_formato_deporte,
                        descripcion_estado_zona

                    FROM
                        zona
                    JOIN
                        servicio
                    ON
                        zona.rela_servicio = servicio.id_servicio
                    JOIN 
                        sucursal
                    ON 
                        zona.rela_sucursal = sucursal.id_sucursal
                    JOIN 
                        formato_deporte
                    ON 
                        zona.rela_formato_deporte = formato_deporte.id_formato_deporte
                    JOIN
                        tipo_terreno
                    ON
                        zona.rela_tipo_terreno = tipo_terreno.id_tipo_terreno
                    JOIN 
                        estado_zona
                    ON
                        estado_zona.id_estado_zona = zona.rela_estado_zona
                    WHERE
                        (descripcion_servicio LIKE 'cancha' AND id_sucursal = ?)
                    AND
                        (
                            id_zona LIKE ?
                            OR descripcion_zona LIKE ?
                            OR descripcion_estado_zona LIKE ?
                            OR descripcion_formato_deporte LIKE ?
                            OR descripcion_tipo_terreno LIKE ?
                        )
                    AND

                        (zona.estado IN(1))
                    ORDER BY (zona.id_zona)
                    LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("isssssii",
                    $id_sucursal,
                    $search_param,
                    $search_param,
                    $search_param,
                    $search_param,
                    $search_param,
                    $registros_por_pagina, 
                    $offset);
$stmt->execute();
$result = $stmt->get_result();

//creamos la tabla
$tabla = '<table border="1">
                <tr>
                <th>descripcion_zona</th>
                <th>superficie</th>
                <th>deporte</th>
                <th>estado</th>
                <th colspan="2" class="acciones">acciones</th>
            </tr>';
while ($row = $result->fetch_assoc()) {
    $tabla .= '<tr>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_zona']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_tipo_terreno']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_formato_deporte']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_estado_zona']) . '</td>';

    $tabla .= '<td class="acciones">' . '<a href="tablaZonas_modificacion.php?id_zona=' . $row['id_zona'] . '&id_sucursal='. $id_sucursal .'">
                    <img src="' . BASE_URL . 'assets/icons/icons8-editar.svg' . '"></a>' . 
                '</td>';

    $tabla .= '<td class="acciones">' . '<accion href="" class="eliminar" sucursal="' . $id_sucursal . '" valor="' . $row['id_zona'] . '"><img src="' . BASE_URL . 'assets/icons/icons8-basura-llena.svg' . '"></accion>' . '</td>';
    $tabla .= '</tr>';
}
$tabla .= '<tr>
                <td colspan="9" class="añadir">
                    <a href="tablaZonas_alta.php?id_sucursal='. $id_sucursal .'">
                    <img src="'. BASE_URL. 'assets/icons/icons8-añadir.png' .'">
                </td>
            </tr>';
$tabla .= '</table>';

// contar el número total de registros
$sql_count = "SELECT COUNT(id_zona) as total 
                FROM zona
                    JOIN
                        servicio
                    ON
                        zona.rela_servicio = servicio.id_servicio
                    JOIN 
                        sucursal
                    ON 
                        zona.rela_sucursal = sucursal.id_sucursal
                    JOIN 
                        formato_deporte
                    ON 
                        zona.rela_formato_deporte = formato_deporte.id_formato_deporte
                    JOIN
                        tipo_terreno
                    ON
                        zona.rela_tipo_terreno = tipo_terreno.id_tipo_terreno
                    JOIN 
                        estado_zona
                    ON
                        estado_zona.id_estado_zona = zona.rela_estado_zona
                    WHERE
                        (descripcion_servicio LIKE 'cancha' AND id_sucursal = ?)
                    AND
                        (
                            id_zona LIKE ?
                            OR descripcion_zona LIKE ?
                            OR descripcion_estado_zona LIKE ?
                            OR descripcion_formato_deporte LIKE ?
                            OR descripcion_tipo_terreno LIKE ?
                        )
                    AND
                        (zona.estado IN(1))
                    ORDER BY (zona.id_zona)
                ";

$stmt_count = $conexion->prepare($sql_count);
$stmt_count->bind_param("isssss", 
                            $id_sucursal,
                            $search_param,
                            $search_param,
                            $search_param,
                            $search_param,
                            $search_param
                        );
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_items = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_items / $registros_por_pagina);

// header("content-type: application/json");

// devolver la tabla como JSON
echo json_encode(array(
    "tabla" => $tabla,
    "total_pages" => $total_pages,
    "current_page" => $pagina_actual
));

$conexion->close();
?>
