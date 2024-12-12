<?php
require_once("../../../config/database/conexion.php");
session_start();

$descripcion = $_POST['descripcion'];

$sql = "INSERT INTO 
					estado_zona(descripcion_estado_zona)
		VALUES
			('$descripcion')";

if ($conexion->query($sql)) {
	header("Location: tablaEstadoZona.php");
}
