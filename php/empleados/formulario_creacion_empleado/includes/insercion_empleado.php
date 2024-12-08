<?php  

$errores = [];

// Validar correo
if (!validarCorreo($correo)) {
    $errores[] = "Correo inválido.";
}

// Validar username
if (!validarUsername($username)) {
    $errores[] = "Username inválido.";
}

// Validar password
if (!validarPassword($password)) {
    $errores[] = "contraseña inválida.";
}

if($password !== $confirmPassword) {
	$errores[] = "las contraseñas no coinciden";
}
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Validaciones adicionales si es paso 2
if ($paso2) {
    if (!validarNombre($nombre)) {
        $errores[] = "Nombre inválido.";
    }
    if (!validarApellido($apellido)) {
        $errores[] = "Apellido inválido.";
    }
    if (!validarDocumento($documento, $tipoDocumento)) {
        $errores[] = "Documento inválido.";
    }
    if (!validarSexo($sexo)) {
        $errores[] = "Sexo inválido.";
    }
    if (!validarDomicilio($domicilio)) {
        $errores[] = "Domicilio inválido.";
    }
}

// Si hay errores, redirigir con los datos enviados y los errores
if (!empty($errores)) {
    $query_string = http_build_query([
        'errores' => $errores,
        'correo' => $correo,
        'username' => $username,
        'nombre' => $paso2 ? $nombre : null,
        'apellido' => $paso2 ? $apellido : null,
        'documento' => $paso2 ? $documento : null,
        'tipoDocumento' => $paso2 ? $tipoDocumento : null,
        'sexo' => $paso2 ? $sexo : null,
        'domicilio' => $paso2 ? $domicilio : null,
        'id_sucursal' => $id_sucursal,
    ]);
    header("Location: ../index.php?$query_string");
    exit;
}

// Intentar la inserción si no hubo errores
if (insertarUsuarioEmpleado(
    $correo,
    $username,
    $password_hash,
    $paso2 ? $nombre : null,
    $paso2 ? $apellido : null,
    $paso2 ? $documento : null,
    $paso2 ? $tipoDocumento : null,
    $paso2 ? $sexo : null,
    $id_sucursal
)) {
    header("Location: " . BASE_URL . "index.php");
} else {
    header("Location: ../index.php?errores=error durante la insercion");
}
exit;

?>