<?php  

if(isset($_POST['btn-actualizar-precios'])) {
	$numero = $_POST['porcentaje'] ?? false;

	if ($numero) {
		$aumento = 1 + ($numero / 100);
		$actualizacion_masiva_query = "
			UPDATE membresia SET precio_membresia = precio_membresia * $aumento
		";
		$conexion->query($actualizacion_masiva_query);
	}
}

?>