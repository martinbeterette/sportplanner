<?php  
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

$id_complejo = $_GET['id_complejo'] ?? die("Parametros insuficientes: ID complejo");

// Parámetros para la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Filtro dinámico
$filtro = $_GET['filtro'] ?? '';
$parametro = "%" . $filtro . "%";

// Consulta SQL
$sql = "SELECT id_sucursal, descripcion_sucursal, s.fecha_alta, s.fecha_de_creacion, s.verificado, c.descripcion_complejo,id_complejo, asd.direccion, descripcion_barrio, descripcion_localidad, descripcion_provincia
        FROM complejo c 
        JOIN sucursal s ON c.id_complejo = s.rela_complejo
        JOIN asignacion_sucursal_domicilio asd ON asd.rela_sucursal = s.id_sucursal
        JOIN barrio b ON b.id_barrio = asd.rela_barrio
        JOIN localidad l ON l.id_localidad = b.rela_localidad
        JOIN provincia p ON p.id_provincia = l.rela_provincia
        WHERE s.estado IN(1) AND s.descripcion_sucursal LIKE ? AND c.id_complejo = ?  
        LIMIT ? OFFSET ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("siii", $parametro, $id_complejo, $registros_por_pagina, $offset);
$stmt->execute();
$registros = $stmt->get_result();


$html = '<table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sucursal</th>
                    <th>Fecha de Fundacion</th>
                    <th>Fecha de Insercion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';
while ($row = $registros->fetch_assoc()) {
    $html .= "<tr>
                <td>{$row['id_sucursal']}</td>
                <td>{$row['descripcion_sucursal']}</td>
                <td>{$row['fecha_de_creacion']}</td>
                <td>{$row['fecha_alta']}</td>
                <td>
                    <button class='btn-verificar' 
                            data-registros='".htmlspecialchars(json_encode($row))."'>Ver Sucursales</button>
                </td>
              </tr>";
}
$html .= '</tbody></table>';

// Total de registros para calcular la paginación
$total_sql = "SELECT COUNT(*) as total
              FROM complejo c 
                JOIN sucursal s ON c.id_complejo = s.rela_complejo
                JOIN asignacion_sucursal_domicilio asd ON asd.rela_sucursal = s.id_sucursal
                JOIN barrio b ON b.id_barrio = asd.rela_barrio
                JOIN localidad l ON l.id_localidad = b.rela_localidad
                JOIN provincia p ON p.id_provincia = l.rela_provincia
                WHERE s.estado IN(1) AND s.descripcion_sucursal LIKE ? AND c.id_complejo = ? ";
$stmt_total = $conexion->prepare($total_sql);
$stmt_total->bind_param("si", $parametro, $id_complejo);
$stmt_total->execute();
$total_result = $stmt_total->get_result();
$total_registros = $total_result->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);
// Enviar JSON al cliente
echo json_encode([
    'tabla' => $html,
    'total_registros' => $total_registros,
    'registros_por_pagina' => $registros_por_pagina,
    'total_pages' => $total_paginas,
    'current_page' => $pagina_actual
]);




?>