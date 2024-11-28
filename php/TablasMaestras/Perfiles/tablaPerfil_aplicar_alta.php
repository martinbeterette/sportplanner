<?php 
require_once("../../../config/database/conexion.php");
    session_start();

   

$descripcion 		= $_POST['descripcion'];

$sql = "INSERT INTO 
					sexo(descripcion_sexo)
		VALUES
			('$descripcion')";

if ($conexion->query($sql)) {
	header("Location: tablasexos.php");
}

?>