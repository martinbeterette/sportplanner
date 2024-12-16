<?php
session_start();
require_once("../../../../../config/root_path.php");
require_once(RUTA."config/database/conexion.php");
require_once '../includes/functions.php'; // Asegúrate de incluir tu archivo de conexión a la BD


$id_persona = $_SESSION['id_persona'];
$id_usuario = $_SESSION['id_usuario'];
$id_complejo = obtenerComplejo($id_persona, $id_usuario); // Según tu lógica

$sql = "
    SELECT 
        s.descripcion_sucursal AS sucursal, 
        COUNT(*) AS total
    FROM 
        reserva r
    JOIN 
        zona z ON r.rela_zona = z.id_zona
    JOIN 
        sucursal s ON s.id_sucursal = z.rela_sucursal
    JOIN 
        complejo c ON s.rela_complejo = c.id_complejo
    WHERE 
        c.id_complejo = ? 
        AND r.rela_estado_reserva = 3 
        AND r.fecha_reserva BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE()
    GROUP BY 
        s.id_sucursal
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_complejo);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data); // Devuelve los datos en JSON
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo obtener los datos']);
}
?>
