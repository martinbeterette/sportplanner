<?php
session_start();
require_once("../../../../config/database/conexion.php");

// Obtener página actual
if($_SESSION['id_perfil'] == 3) {
    $id_persona = $_SESSION['id_persona'];
    $id_usuario = $_SESSION['id_usuario'];
    $id_sucursal = obtenerComplejoPorPersona($id_persona, $id_usuario);
}

if($_SESSION['id_perfil'] == 23) {
    $id_sucursal = $_GET['id_sucursal'] ?? false;
    function obtenerComplejoPorPersona($id_persona, $id_usuario) {
        global $conexion;

        $sql_sucursal_empleado = "
            SELECT apc.rela_complejo, descripcion_complejo, complejo.fecha_fundacion
                FROM asignacion_persona_complejo apc
                JOIN complejo on apc.rela_complejo = id_complejo
                WHERE apc.rela_persona = ? AND apc.rela_usuario = ?";

        $stmt_obtener_complejo = $conexion->prepare($sql_sucursal_empleado);
        $stmt_obtener_complejo->bind_param("ii", $id_persona, $id_usuario);

        if ($stmt_obtener_complejo->execute()) {
            $datos_complejo = $stmt_obtener_complejo->get_result()->fetch_assoc() ?? false;
            return $datos_complejo;
        }
        return false;
    }

    $id_complejo = obtenerComplejoPorPersona($id_persona, $id_usuario);
    $complejo = $id_complejo['rela_complejo'];
}


$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 10; // Cantidad de registros por página
$offset = ($pagina - 1) * $limite;

// Consultar total de registros
$totalRegistros = $conexion->query("SELECT COUNT(*) as total FROM zonas WHERE rela_sucursal = $id_sucursal")->fetch_assoc()['total'];
$totalPaginas = ceil($totalRegistros / $limite);

// Consultar registros paginados
$result = $conexion->query("SELECT id_persona, nombre, apellido FROM zonas WHERE rela_sucursal = $id_sucursal LIMIT $offset, $limite");
$data = [];
while ($fila = $result->fetch_assoc()) {
    $data[] = $fila;
}

echo json_encode(['data' => $data, 'totalPages' => $totalPaginas]);

 function obtenerComplejoPorPersona($id_persona, $id_usuario) {
    global $conexion;

    $sql_sucursal_empleado = "
        SELECT s.id_sucursal 
        FROM empleado e
        JOIN sucursal s ON e.rela_sucursal = s.id_sucursal
        JOIN persona p ON e.rela_persona = p.id_persona
        JOIN contacto c ON p.id_persona = c.rela_persona
        JOIN usuarios u ON c.id_contacto = u.rela_contacto AND u.id_usuario = ?
        WHERE e.rela_persona = ?;";

    $stmt_obtener_complejo = $conexion->prepare($sql_sucursal_empleado);
    $stmt_obtener_complejo->bind_param("ii", $id_usuario, $id_persona);

    if ($stmt_obtener_complejo->execute()) {
        $datos_complejo = $stmt_obtener_complejo->get_result()->fetch_assoc()['id_sucursal'] ?? false;
        return $datos_complejo;
    }
    return false;
}
?>
