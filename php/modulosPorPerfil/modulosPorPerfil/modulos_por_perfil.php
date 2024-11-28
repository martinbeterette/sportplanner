<?php 
$conexion = new mysqli("localhost","root", "","proyecto_pp2");

function obtenerModulosPorPerfil($id_perfil) {
	global $conexion;
	$sql = "SELECT rela_modulo FROM asignacion_perfil_modulo JOIN perfil ON asignacion_perfil_modulo.rela_perfil = perfil.id_perfil WHERE perfil.id_perfil = {$id_perfil}";
	$modulosPorPerfil = [];
	$resultado = $conexion->query($sql);
	foreach ($resultado as $reg){
		$modulosPorPerfil[] = $reg;
	}
	return $modulosPorPerfil;
}

if (isset($_GET['id_perfil'])) {

	$id_perfil = $_GET['id_perfil'];
} else {
	$id_perfil = 1;
}
$modulos = obtenerModulosPorPerfil($id_perfil);
header('Content-Type: application/json');
echo json_encode($modulos);
?>
