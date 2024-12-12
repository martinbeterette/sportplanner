<?php
require_once("../../../config/database/conexion.php");
session_start();

$descripcion 		= $_POST['descripcion'];

$sql = "INSERT INTO 
					perfil(descripcion_perfil)
		VALUES
			('$descripcion')";

if ($conexion->query($sql)) {
	header("Location: tablaPerfil.php");
}
