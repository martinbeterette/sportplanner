<?php
// Conectar a la base de datos
require_once("../../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");


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

$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("falta get de sucursal");

// consulta para los registros
$sql = "SELECT  
                        empleado.id_empleado,
                        persona.nombre,
                        persona.apellido,
                        documento.descripcion_documento,
                        persona.fecha_nacimiento,
                        empleado.empleado_cargo,
                        empleado.fecha_alta,
                        sucursal.descripcion_sucursal
                    FROM
                        empleado
                    JOIN
                        persona
                    ON
                        empleado.rela_persona = persona.id_persona
                    JOIN
                        sucursal
                    ON
                        empleado.rela_sucursal = sucursal.id_sucursal
                    JOIN 
                        documento
                    ON
                        documento.rela_persona = persona.id_persona
                    WHERE (nombre                LIKE ?
                            OR apellido                 LIKE ?
                            OR descripcion_documento    LIKE ?
                            OR empleado_cargo           LIKE ?
                            OR fecha_nacimiento         LIKE ?
                    )
                    AND
                        (empleado.estado IN(1)  AND id_sucursal = ?)  
                    ORDER BY (empleado.id_empleado)
                    LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssiii", 
                    $search_param, 
                    $search_param, 
                    $search_param,
                    $search_param,
                    $search_param,
                    $id_sucursal,
                    $registros_por_pagina, 
                    $offset);
$stmt->execute();
$result = $stmt->get_result();

//creamos la tabla
$tabla = '<table border="1">
                <tr>
                <th>ID Empleado</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Documento</th>
                <th>Fecha de nacimiento</th>
                <th>cargo</th>
                <th>fecha_alta</th>
                <th>sucursal</th>
            </tr>';
while ($row = $result->fetch_assoc()) {
    $tabla .= '<tr>';
    $tabla .= '<td>' . htmlspecialchars($row['id_empleado']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['nombre']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['apellido']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_documento']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['fecha_nacimiento']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['empleado_cargo']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['fecha_alta']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_sucursal']) . '</td>';

    $tabla .= '</tr>';
}   

$tabla .= '</table>';

// contar el número total de registros
$sql_count = "SELECT COUNT(id_empleado) as total 
              FROM
                        empleado
                    JOIN
                        persona
                    ON
                        empleado.rela_persona = persona.id_persona
                    JOIN
                        sucursal
                    ON
                        empleado.rela_sucursal = sucursal.id_sucursal
                    JOIN 
                        documento
                    ON
                        documento.rela_persona = persona.id_persona
                    WHERE 
                    (
                        nombre                      LIKE ?
                        OR apellido                 LIKE ?
                        OR descripcion_documento    LIKE ?
                        OR empleado_cargo           LIKE ?
                        OR fecha_nacimiento         LIKE ?
                    )
                    AND
                        (empleado.estado IN(1)  AND id_sucursal = ?)  
                    ORDER BY (empleado.id_empleado)";
$stmt_count = $conexion->prepare($sql_count);
$stmt_count->bind_param("sssssi", 
                            $search_param, 
                            $search_param,
                            $search_param,
                            $search_param,
                            $search_param,
                            $id_sucursal
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
    "current_page" => $pagina_actual,
    "total_items" => $total_items
));

$conexion->close();
?>
