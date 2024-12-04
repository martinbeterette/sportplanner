<?php  
	
	require_once("../../../../config/root_path.php");
	require_once(RUTA. "config/database/conexion.php");
	$id_provincia = isset($_GET['id_provincia']) ? $_GET['id_provincia'] : die("error: falta GET de id_provincia");

	$sql = "SELECT id_localidad,descripcion_localidad FROM localidad WHERE estado IN(1) AND rela_provincia = ?";
	$stmt = $conexion->prepare($sql);
	$stmt->bind_param("i", $id_provincia);
	$stmt->execute();
	$registros = $stmt->get_result();
	$localidades = [];
	foreach ($registros as $reg) {
		$localidades[] = $reg;
	}
	header("Content-Type: application/json");
	echo json_encode($localidades);



	

?>