<?php  
	
	require_once("../../../../config/root_path.php");
	require_once(RUTA. "config/database/conexion.php");
	$id_localidad = isset($_GET['id_localidad']) ? $_GET['id_localidad'] : die("error: falta GET de id_localidad");

	$sql = "SELECT id_barrio,descripcion_barrio FROM barrio WHERE estado IN(1) AND rela_localidad = ?";
	$stmt = $conexion->prepare($sql);
	$stmt->bind_param("i", $id_localidad);
	$stmt->execute();
	$registros = $stmt->get_result();
	$barrios = [];
	foreach ($registros as $reg) {
		$barrios[] = $reg;
	}
	header("Content-Type: application/json");
	echo json_encode($barrios);



	

?>