<?php 
require_once("../../../config/database/conexion.php");

$descripcion 		= $_POST['descripcion'];

$sql = "INSERT INTO 
					tipo_contacto(descripcion_tipo_contacto)
		VALUES
			('$descripcion')";

if ($conexion->query($sql)) {
	header("Location: tablatipocontactos.php");
}

?>