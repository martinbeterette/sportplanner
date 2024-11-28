<?php 
require_once("../../../config/database/conexion.php");

$descripcion 		= $_POST['descripcion'];

$sql = "INSERT INTO 
					tipo_documento(descripcion_tipo_documento)
		VALUES
			('$descripcion')";

if ($conexion->query($sql)) {
	header("Location: tabla_tipo_documentos.php");
}

?>