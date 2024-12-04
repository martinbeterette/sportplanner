<?php  
	
	require_once("../../../../config/root_path.php");
	require_once(RUTA. "config/database/conexion.php");


	$sql = "SELECT id_estado_zona,descripcion_estado_zona FROM estado_zona WHERE estado IN(1)";
	$stmt = $conexion->prepare($sql);
	$stmt->execute();
	$registros = $stmt->get_result();
	$estados = [];
	foreach ($registros as $reg) {
		$estados[] = $reg;
	}
	header("Content-Type: application/json");
	echo json_encode($estados);



	

?>