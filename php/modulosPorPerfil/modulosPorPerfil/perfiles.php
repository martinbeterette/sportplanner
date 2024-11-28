<?php 

/*if (isset($_GET['id_persona'])) {

	$id_persona = $_GET['id_persona'];
} else {
	$id_persona = 1;
}*/

$conexion = new mysqli("localhost","root", "","proyecto_pp2");

$sql = "SELECT id_perfil, descripcion_perfil FROM perfil";

$resultado = $conexion->query($sql);

$perfiles = [];

foreach ($resultado as $reg) {
	$perfiles[] = $reg;
}

header('Content-Type: application/json');
echo json_encode($perfiles);



?>