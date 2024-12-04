<?php  
	
	require_once("../../../../config/root_path.php");
	require_once(RUTA. "config/database/conexion.php");
	$id_deporte = isset($_GET['id_deporte']) ? $_GET['id_deporte'] : die("error: falta GET de id_deporte");

	$sql = "SELECT id_formato_deporte,descripcion_formato_deporte FROM formato_deporte WHERE estado IN(1) AND rela_deporte = ?";
	$stmt = $conexion->prepare($sql);
	$stmt->bind_param("i", $id_deporte);
	$stmt->execute();
	$registros = $stmt->get_result();
	$formatos_deporte = [];
	foreach ($registros as $reg) {
		$formatos_deporte[] = $reg;
	}
	header("Content-Type: application/json");
	echo json_encode($formatos_deporte);



	

?>