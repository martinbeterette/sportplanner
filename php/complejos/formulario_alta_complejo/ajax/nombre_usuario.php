<?php
header('Content-Type: application/json');
require_once("../../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");

$username = $_POST['username'];
$consulta = $conexion->prepare("SELECT COUNT(id_usuario) FROM usuarios WHERE username = ?");
$consulta->bind_param("s", $username);
$consulta->execute();
$consulta->bind_result($count);
$consulta->fetch();

$consulta->close();
$conexion->close();

echo json_encode(['success' => $count == 0]);
?>
