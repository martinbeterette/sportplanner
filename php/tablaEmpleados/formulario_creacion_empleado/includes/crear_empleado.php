<?php  
require_once("../../../../config/root_path.php");
require_once(RUTA."config/database/conexion.php");
require_once("functions.php");

$id_sucursal        = $_POST['id_sucursal']         ?? false;
$correo 			= $_POST['correo'] 				?? false;
$username 			= $_POST['username'] 			?? false;
$password 			= $_POST['password'] 			?? false;
$confirmPassword 	= $_POST['confirm-password'] 	?? false;
if($password !== $confirmPassword) {
	echo "las contraseñas no coinciden";die;
}
$password_hash = password_hash($password, PASSWORD_DEFAULT);

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


if 
	(
		validarCorreo($correo) &&
		validarUsername($username) &&
		validarPassword($password) &&
		(!$paso2 || ( // Si no es paso 2, ignorar estas validaciones
	        validarNombre($nombre) &&
	        validarApellido($apellido) &&
	        validarDocumento($documento,$tipoDocumento) &&
	        validarSexo($sexo) &&
	        validarDomicilio($domicilio)
	    ))
	) {
		echo "validaciones hechas correctamente";
		insertarUsuarioEmpleado(
			$correo,
			$username,
			$password_hash,
			$paso2 ? $nombre : null,
			$paso2 ? $apellido : null,
			$paso2 ? $documento : null,
			$paso2 ? $tipoDocumento : null, 
			$paso2 ? $sexo : null,
			$id_sucursal
		);
}




function insertarUsuarioEmpleado (
	$correo = null,
	$username = null,
	$password = null,
	$nombre = null,
	$apellido = null,
	$documento = null,
	$tipoDocumento = null,
	$sexo = null,
	$id_sucursal 
) {
	global $conexion, $paso2, $idCorreo, $idPersona;
	$conexion->begin_transaction();
	try {

		if ($paso2) {
			$sql = "
				INSERT INTO persona(nombre,apellido,rela_sexo) VALUES (?,?,?)
			";
			$stmt_persona = $conexion->prepare($sql);
			$stmt_persona->bind_param("ssi",$nombre,$apellido,$sexo);
			$stmt_persona->execute();
			$idPersona = $conexion->insert_id;

			$sql = "
				INSERT INTO documento(descripcion_documento,rela_tipo_documento,rela_persona) VALUES (?,?,?)
			";
			$stmt_documento = $conexion->prepare($sql);
			$stmt_documento->bind_param("sii",$documento,$tipoDocumento,$idPersona);
			$stmt_documento->execute();

			$sql = "
				INSERT INTO contacto(descripcion_contacto,rela_tipo_contacto,rela_persona) VALUES (?,1,?)
			";
			$stmt_correo = $conexion->prepare($sql);
			$stmt_correo->bind_param("si",$correo,$idPersona);
			$stmt_correo->execute();
			$idCorreo = $conexion->insert_id;

		}
		$sql = "INSERT INTO usuarios(username, password, estado,rela_contacto,rela_perfil, fecha_alta,expiry)
				VALUES (?,?,'no verificado',?,3, CURDATE(), now());
				";
		$stmt_usuario = $conexion->prepare($sql);
		$stmt_usuario->bind_param("ssi",$username,$password,$idCorreo);
		$stmt_usuario->execute();
		$id_usuario = $conexion->insert_id;

		$sql = "INSERT INTO empleado(fecha_alta, rela_persona,rela_sucursal, rela_usuario)
				VALUES (CURDATE(), ?, ?, ?)";
		$stmt_empleado = $conexion->prepare($sql);
		$stmt_empleado->bind_param("iii", $idPersona, $id_sucursal, $id_usuario);
		$stmt_empleado->execute();

		header("Location: ../../tablaEmpleados.php?id_sucursal=$id_sucursal");

		$conexion->commit();
	} catch (Exception $e) {
		$conexion->rollback();
		header("Location ../index.php?id_sucursal=$id_sucursal&error");
        echo "Error: " . $e->getMessage();
	}
	

}

?>