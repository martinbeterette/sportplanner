<?php  

require_once("../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");

$registros = obtenerProvincias();

echo json_encode($registros);











function obtenerProvincias() {
	global $conexion;
	$sql = "
		SELECT * FROM provincia
	";
	$provincias = [];
	$registros = $conexion->query($sql);
	foreach($registros as $reg) {
		$provincias[] = $reg;
	}
	return $provincias;
}
?>