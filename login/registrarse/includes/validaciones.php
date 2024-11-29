<?php  

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$nombre 				= $_POST['nombre'] 					?? null;
	$apellido 				= $_POST['apellido'] 				?? null;
	$documento 				= $_POST['documento'] 				?? null;
	$tipo_documento 		= $_POST['tipo_documento'] 			?? null;
	$sexo 					= $_POST['sexo'] 					?? null;
	$email 					= $_POST['email'] 					?? null;
	$username 				= $_POST['username'] 				?? null;
	$contrasena 			= $_POST['password'] 				?? null;
	$confirmar_contrasena 	= $_POST['confirmar-contrasena'] 	?? null;
	// Validar formulario y backend
    $errores_tipeo = validarFormulario($nombre, $apellido, $documento, $tipo_documento, $sexo, $email, $username, $contrasena, $confirmar_contrasena);

    $error_usuario = validarUsuario($conexion, $username, $email);

    $error_documento = validarDocumento($conexion, $documento, $tipo_documento);

    if (empty($errores_tipeo) && $error_usuario == 'no hay' && $error_documento == 'no hay') {
        $get = "enviar_correo&nombre=$nombre&apellido=$apellido&documento=$documento&tipo_documento=$tipo_documento&sexo=$sexo&email=$email&username=$username&password=$contrasena";
        header("Location: " . BASE_URL . "login/registrarse/includes/enviar_correo.php?$get");
        exit;
    } else {
        // Construir mensaje de error global
        $mensaje_error = "Formulario rechazado. Verifique los campos del formulario.";
    }

}
?>