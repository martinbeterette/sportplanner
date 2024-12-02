<?php  
	
	require_once("../../../../config/root_path.php");
	require_once(RUTA. "config/database/conexion.php");


	$sql = "SELECT id_deporte,descripcion_deporte FROM deporte WHERE estado IN(1)";
	$stmt = $conexion->prepare($sql);
	$stmt->execute();
	$registros = $stmt->get_result();
	$deportes = [];
	foreach ($registros as $reg) {
		$deportes[] = $reg;
	}
	header("Content-Type: application/json");
	echo json_encode($deportes);



	

?>