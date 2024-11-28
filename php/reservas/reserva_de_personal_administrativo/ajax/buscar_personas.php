<?php
// Conectar a la base de datos
require_once("../../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");


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


// consulta para los registros
$sql = "SELECT  DISTINCT
                        persona.id_persona,
                        persona.nombre,
                        persona.apellido,
                        persona.fecha_nacimiento,
                        documento.descripcion_documento,
                        sexo.descripcion_sexo
                    FROM
                        persona
                    JOIN
                        documento
                    ON
                        documento.rela_persona = persona.id_persona
                    JOIN
                        sexo
                    ON
                        sexo.id_sexo = persona.rela_sexo
                    JOIN 
                        contacto
                    ON
                        contacto.rela_persona = persona.id_persona
                    JOIN 
                        usuarios 
                    ON
                        usuarios.rela_contacto = contacto.id_contacto
                    WHERE (
                            nombre                      LIKE ?
                            OR apellido                 LIKE ?
                            OR descripcion_documento    LIKE ?
                    )
                    AND
                        (usuarios.estado IN('verificado', 'no verificado'))  
                    ORDER BY (persona.nombre)
                    LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssii", 
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
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Documento</th>
                <th>Fecha de nacimiento</th>
                <th></th>
            </tr>';
while ($row = $result->fetch_assoc()) {
    $tabla .= '<tr>';
    $tabla .= '<td>' . htmlspecialchars($row['nombre']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['apellido']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['descripcion_documento']) . '</td>';
    $tabla .= '<td>' . htmlspecialchars($row['fecha_nacimiento']) . '</td>';
    $tabla .= '<td> <button class="seleccionar-persona" data-value="'. htmlspecialchars($row['id_persona']) .'">Seleccionar</button> </td>';
    $tabla .= '</tr>';
}   

$tabla .= '</table>';

// contar el número total de registros
$sql_count = "SELECT COUNT(DISTINCT persona.id_persona) as total 
              FROM
                        persona
                    JOIN
                        documento
                    ON
                        documento.rela_persona = persona.id_persona
                    JOIN
                        sexo
                    ON
                        sexo.id_sexo = persona.rela_sexo
                    JOIN 
                        contacto
                    ON
                        contacto.rela_persona = persona.id_persona
                    JOIN 
                        usuarios 
                    ON
                        usuarios.rela_contacto = contacto.id_contacto
                    WHERE (
                            nombre                      LIKE ?
                            OR apellido                 LIKE ?
                            OR descripcion_documento    LIKE ?
                    )
                    AND
                        (usuarios.estado IN('verificado', 'no verificado'))  
                    ORDER BY (persona.id_persona)";
$stmt_count = $conexion->prepare($sql_count);
$stmt_count->bind_param("sss", 
                            $search_param, 
                            $search_param,
                            $search_param
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
    "current_page" => $pagina_actual
));

$conexion->close();
?>
