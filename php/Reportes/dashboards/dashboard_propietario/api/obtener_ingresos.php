<?php 
session_start();
require_once("../../../../../config/root_path.php");
require_once(RUTA."config/database/conexion.php");
require_once '../includes/functions.php'; // Asegúrate de incluir tu archivo de conexión a la BD

$id_persona = $_SESSION['id_persona'];
$id_usuario = $_SESSION['id_usuario'];
$id_complejo = obtenerComplejo($id_persona,$id_usuario); // O el valor que corresponda
$sql = "
    SELECT s.descripcion_sucursal AS sucursal, SUM(con.monto_final) AS total
    FROM control con
    JOIN reserva r ON con.rela_reserva = r.id_reserva
    JOIN zona z ON r.rela_zona = z.id_zona
    JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
    JOIN complejo c ON s.rela_complejo = c.id_complejo
    WHERE id_complejo = ? AND con.rela_estado_control = 3
    AND r.fecha_reserva BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE()
    GROUP BY s.id_sucursal
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_complejo);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Añade cada fila como un array asociativo
    }
    echo json_encode($data); // Devuelve los datos en formato JSON
} else {
    echo json_encode(['error' => 'No se pudo obtener los datos']);
}

?>