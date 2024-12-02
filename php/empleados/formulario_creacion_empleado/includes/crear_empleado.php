<?php  
require_once("../../../../config/root_path.php");
require_once(RUTA."config/database/conexion.php");
require_once("functions.php");

$id_sucursal        = $_POST['id_sucursal']         ?? false;
$correo 			= $_POST['correo'] 				?? false;
$username 			= $_POST['username'] 			?? false;
$password 			= $_POST['password'] 			?? false;
$confirmPassword 	= $_POST['confirm-password'] 	?? false;

if(!$id_sucursal){
	header("Location: ../index.php?error=error al capturar la sucursal");
}

if (isset($_POST['paso2'])) {
	$paso2 = true;
	

	$nombre 			= $_POST['nombre'] 			?? false;
	$apellido 			= $_POST['apellido'] 		?? false;
	$documento 			= $_POST['documento'] 		?? false;
	$tipoDocumento 		= $_POST['tipo_documento'] 	?? false;
	$sexo 				= $_POST['sexo'] 			?? false;
	$domicilio 			= $_POST['domicilio'] 		?? false;
} else {
	$paso2 = false;	
}

if(isset($_POST['paso1'])) {
	if(validarCorreo($correo)) {
		$sql = "
			SELECT 
				id_contacto,
				rela_persona
			FROM contacto
			WHERE descripcion_contacto LIKE ?;
		";
		$stmtCorreo = $conexion->prepare($sql);
		$stmtCorreo->bind_param("s",$correo);
		$stmtCorreo->execute();
		$registrosCorreo = $stmtCorreo->get_result()->fetch_assoc();
		$idCorreo = $registrosCorreo['id_contacto'] ?? false;
		$idPersona = $registrosCorreo['rela_persona'] ?? false;

	}
}


require_once("insercion_empleado.php");






?>