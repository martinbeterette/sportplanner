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

$id_complejo = isset($_GET['id_complejo']) ? $_GET['id_complejo'] : die("falta get de complejo");

// consulta para los registros
$sql = "SELECT 
            id_socio as id, 
            id_persona, 
            nombre, 
            apellido, 
            descripcion_documento, 
            descripcion_membresia, 
            beneficio_membresia 

        FROM socio JOIN persona
        ON socio.rela_persona = persona.id_persona
        JOIN documento
        ON persona.id_persona = documento.rela_persona
        JOIN membresia
        ON socio.rela_membresia = membresia.id_membresia
        WHERE 
            (
                id_socio LIKE ?
                OR id_persona LIKE ?
                OR nombre LIKE ?
                OR apellido LIKE ?
                OR descripcion_documento LIKE ?
                OR descripcion_membresia LIKE ?
                OR beneficio_membresia LIKE ?
            )
        AND
            (rela_complejo = ? AND socio.estado IN(1))

        ORDER BY (id)
        LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssssiii",
                    $search_param,
                    $search_param,
                    $search_param,
                    $search_param,
                    $search_param,
                    $search_param,
                    $search_param, 
                    $id_complejo,
                    $registros_por_pagina, 
                    $offset);
$stmt->execute();
$result = $stmt->get_result();

//creamos la tabla
$tabla = '<table border="1">
                <tr>
                <th>ID socio</th>
                <th>ID persona</th>
                <th>nombre</th>
                <th>apellido</th>
                <th>documento</th>
                <th>descripcion_membresia</th>
                <th>valor</th>
                <th colspan="2" class="acciones">acciones</th>
            </tr>';
while ($row = $result->fetch_assoc()) {
    $tabla .= '<tr>';
    $tabla .= '<td>' . htmlspecialchars($row['id']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['id_persona']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['nombre']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['apellido']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_documento']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_membresia']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['beneficio_membresia']) . '</td>';
    $tabla .= '<td class="acciones">' . '<a href="modificar.php?id=' . $row['id'] . '&id_complejo='. $id_complejo .'">
                    <img src="' . BASE_URL . 'assets/icons/icons8-editar.svg' . '"></a>' . 
                '</td>';

    $tabla .= '<td class="acciones">' . '<accion href="" class="eliminar" complejo="' . $id_complejo . '" valor="' . $row['id'] . '"><img src="' . BASE_URL . 'assets/icons/icons8-basura-llena.svg' . '"></accion>' . '</td>';
    $tabla .= '</tr>';
}
$tabla .= '<tr>
                <td colspan="9" class="añadir">
                    <a href="agregar.php?id='. $id_complejo .'">
                    <img src="'. BASE_URL. 'assets/icons/icons8-añadir.png' .'">
                </td>
            </tr>';
$tabla .= '</table>';

// contar el número total de registros
$sql_count = "SELECT COUNT(id_socio) as total 
                FROM socio JOIN persona
                ON socio.rela_persona = persona.id_persona
                JOIN documento
                ON persona.id_persona = documento.rela_persona
                JOIN membresia
                ON socio.rela_membresia = membresia.id_membresia
                WHERE 
                (
                    id_socio LIKE ?
                    OR id_persona LIKE ?
                    OR nombre LIKE ?
                    OR apellido LIKE ?
                    OR descripcion_documento LIKE ?
                    OR descripcion_membresia LIKE ?
                    OR beneficio_membresia LIKE ?
                )
                AND
                (rela_complejo = ? AND socio.estado IN(1))
                ";

$stmt_count = $conexion->prepare($sql_count);
$stmt_count->bind_param("sssssssi", 
                            $search_param,
                            $search_param,
                            $search_param,
                            $search_param,
                            $search_param,
                            $search_param,
                            $search_param,
                            $id_complejo
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
