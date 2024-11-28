<?php 

require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

$sql = "SELECT id_perfil, descripcion_perfil FROM perfil";

$resultado = $conexion->query($sql);

$perfiles = [];

foreach ($resultado as $reg) {
	$perfiles[] = $reg;
}

header('Content-Type: application/json');
echo json_encode($perfiles);



?>