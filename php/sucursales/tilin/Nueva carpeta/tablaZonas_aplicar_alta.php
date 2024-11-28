<?php 
require_once("../../config/database/conexion.php");

$zona 		= $_POST['descripcion'];
$dimension 	= $_POST['dimension'];
$terreno 	= $_POST['terreno'];
$tipoFutbol = $_POST['tipo_futbol'];
$valor 		= $_POST['valor'];
$estado 	= $_POST['estado'];
$complejo 	= $_POST['complejo'];
$servicio 	= $_POST['servicio'];

$sql = "INSERT INTO 
					zona(descripcion_zona,dimension,terreno,tipo_futbol,valor,rela_estado_zona,rela_complejo,rela_servicio)
		VALUES
			('$zona','$dimension','$terreno','$tipoFutbol',$valor,$estado,$complejo,$servicio)";

if ($conexion->query($sql)) {
	header("Location: tablaZonas.php");
}

?>