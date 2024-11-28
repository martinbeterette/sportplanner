<?php 
require_once("../../config/database/conexion.php");

$zona 		= $_POST['descripcion'];
$terreno 	= $_POST['terreno'];
$tipoFutbol = $_POST['tipo_futbol'];
$estado 	= $_POST['estado'];
$sucursal 	= $_POST['sucursal'];
$servicio 	= 1;

$sql = "INSERT INTO 
					zona(descripcion_zona,rela_tipo_terreno,rela_formato_deporte,rela_estado_zona,rela_sucursal,rela_servicio)
		VALUES
			('$zona','$terreno','$tipoFutbol',$estado,$sucursal,$servicio)";
// echo $sql; die;

if ($conexion->query($sql)) {
	header("Location: tablaZonas.php?id_sucursal=$sucursal");
}

?>