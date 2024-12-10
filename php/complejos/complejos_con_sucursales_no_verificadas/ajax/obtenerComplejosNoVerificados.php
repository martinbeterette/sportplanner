<?php  
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
// Parámetros para la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Filtro dinámico
$filtro = $_GET['filtro'] ?? '';
$parametro = "%" . $filtro . "%";

// Consulta SQL
$sql = "SELECT DISTINCT id_complejo, descripcion_complejo, c.fecha_alta, c.fecha_fundacion, c.verificado
        FROM complejo c 
        JOIN sucursal s ON c.id_complejo = s.rela_complejo
        WHERE c.estado IN(1) AND s.verificado LIKE 'no verificado'
        AND descripcion_complejo LIKE ?
        LIMIT ? OFFSET ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sii", $parametro, $registros_por_pagina, $offset);
$stmt->execute();
$registros = $stmt->get_result();


$html = '<table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Fecha de Fundacion</th>
                    <th>Fecha de Insercion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';
while ($row = $registros->fetch_assoc()) {
    $html .= "<tr>
                <td>{$row['id_complejo']}</td>
                <td>{$row['descripcion_complejo']}</td>
                <td>{$row['fecha_fundacion']}</td>
                <td>{$row['fecha_alta']}</td>
                <td>
                    <button class='btn-verificar' 
                            data-id='{$row['id_complejo']}'>Ver Sucursales</button>
                </td>
              </tr>";
}
$html .= '</tbody></table>';

// Total de registros para calcular la paginación
$total_sql = "SELECT COUNT(*) as total
              FROM complejo c JOIN sucursal s
                WHERE c.estado IN(1) AND s.verificado LIKE 'no verificado'
                AND descripcion_complejo LIKE ?
                GROUP BY(id_complejo)";
$stmt_total = $conexion->prepare($total_sql);
$stmt_total->bind_param("s", $parametro);
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