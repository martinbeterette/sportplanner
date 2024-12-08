<?php  
	
	require_once("../../../../config/root_path.php");
	require_once(RUTA. "config/database/conexion.php");


	$sql = "SELECT id_provincia,descripcion_provincia FROM provincia WHERE estado IN(1)";
	$stmt = $conexion->prepare($sql);
	$stmt->execute();
	$registros = $stmt->get_result();
	$provincias = [];
	foreach ($registros as $reg) {
		$provincias[] = $reg;
	}
	header("Content-Type: application/json");
	echo json_encode($provincias);



	

?>