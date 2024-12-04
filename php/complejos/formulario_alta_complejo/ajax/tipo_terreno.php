<?php  
	
	require_once("../../../../config/root_path.php");
	require_once(RUTA. "config/database/conexion.php");


	$sql = "SELECT id_tipo_terreno,descripcion_tipo_terreno FROM tipo_terreno WHERE estado IN(1)";
	$stmt = $conexion->prepare($sql);
	$stmt->execute();
	$registros = $stmt->get_result();
	$tiposTerreno = [];
	foreach ($registros as $reg) {
		$tiposTerreno[] = $reg;
	}
	header("Content-Type: application/json");
	echo json_encode($tiposTerreno);



	

?>