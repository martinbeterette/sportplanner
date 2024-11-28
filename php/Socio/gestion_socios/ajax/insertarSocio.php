<?php
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

$id_persona = filter_var($_REQUEST['persona'], FILTER_VALIDATE_INT);
$meses =    filter_var($_REQUEST['meses'],FILTER_VALIDATE_INT);
$membresia = filter_var($_REQUEST['membresia'],FILTER_VALIDATE_INT);
$id_complejo = filter_var($_REQUEST['id_complejo'],FILTER_VALIDATE_INT);

$existe = $conexion->query("SELECT count(*) as existe FROM socio WHERE rela_complejo = {$id_complejo} AND rela_persona = {$id_persona}")->fetch_assoc();
if($existe && $existe['existe'] > 0) {
    $query = "
        UPDATE socio 
        SET rela_persona = ?, fecha_afiliacion = CURDATE(), fecha_expiracion = DATE_ADD(NOW(), INTERVAL ? MONTH), rela_membresia = ?, rela_complejo = ?
        WHERE rela_persona = {$id_persona} AND rela_complejo = {$id_complejo}
    ";
} else {

    $query = "
        INSERT INTO socio (rela_persona, fecha_afiliacion,fecha_expiracion, rela_membresia,rela_complejo, fecha_alta)
        VALUES (?, CURDATE(), DATE_ADD(NOW(), INTERVAL ? MONTH), ?, ?, CURDATE())
    ";
}
// Consulta para insertar el socio, con DATE_ADD para calcular la fecha de expiración

$stmt = $conexion->prepare($query);
$stmt->bind_param("iiii", $id_persona, $meses, $membresia, $id_complejo);
header('Content-Type: application/json'); 
if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode([
                        'status' => 'error',
                        'error' => $stmt->error
                    ]);
}


?>
