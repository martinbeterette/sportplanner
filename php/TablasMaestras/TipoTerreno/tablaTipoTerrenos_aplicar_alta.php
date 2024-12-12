<?php
require_once("../../../config/database/conexion.php");
session_start();

$descripcion         = $_POST['descripcion'];

$sql = "INSERT INTO 
					tipo_terreno(descripcion_tipo_terreno)
		VALUES
			('$descripcion')";

if ($conexion->query($sql)) {
    header("Location: tablaTipoTerrenos.php");
}
