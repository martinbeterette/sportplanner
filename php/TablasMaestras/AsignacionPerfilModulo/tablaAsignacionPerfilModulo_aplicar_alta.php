<?php 
require_once("../../../config/database/conexion.php");
session_start();
    
$modulo 		= $_POST['modulo'];
$perfil 			= $_POST['perfil'];

$sql = "INSERT INTO 
					asignacion_perfil_modulo(rela_modulo, rela_perfil)
		VALUES
			($modulo, $perfil)";

if ($conexion->query($sql)) {
	header("Location: tablaAsignacionPerfilModulo.php");
}

?>