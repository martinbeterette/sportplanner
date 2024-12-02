<?php  
$errores = [];

// Validar descripcion_complejo: al menos 4 caracteres, solo letras y números
if (!preg_match('/^[A-Za-z0-9\s]{4,}$/', $descripcion_complejo)) {
    $errores[] = "Descripción de complejo no válida.";
}

// Validar fecha_fundacion_complejo: formato yyyy-mm-dd y no posterior a hoy
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_fundacion_complejo) || strtotime($fecha_fundacion_complejo) > time()) {
    $errores[] = "Fecha de fundación de complejo no válida.";
}

// Validar descripcion_sucursal: al menos 4 caracteres, solo letras y números
if (!preg_match('/^[A-Za-z0-9\s]{4,}$/', $descripcion_sucursal)) {
    $errores[] = "Descripción de sucursal no válida.";
}

// Validar fecha_fundacion_sucursal: formato yyyy-mm-dd y no posterior a hoy
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_fundacion_sucursal) || strtotime($fecha_fundacion_sucursal) > time()) {
    $errores[] = "Fecha de fundación de sucursal no válida.";
}

// Validar rela_barrio: entero
if (!filter_var($rela_barrio, FILTER_VALIDATE_INT)) {
    $errores[] = "Barrio no válido.";
}

// Validar direccion: letras, números y algunos caracteres especiales como -, ., /
if (!preg_match('/^[A-Za-z0-9\s\-\.\/]{4,}$/', $direccion)) {
    $errores[] = "Dirección no válida.";
}

// Validar email: formato general de email
if (!preg_match('/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,8}$/', $email)) {
    $errores[] = "Email no válido.";
}

// Validar password: al menos 8 caracteres
if (strlen($password) < 8) {
    $errores[] = "Contraseña no válida.";
}

// Verificar y hashear contraseña
if ($password === $confirm_password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
} else {
    $errores[] = "Las contraseñas no coinciden.";
}

// Redirigir si hay errores
if (!empty($errores)) {
    $query_string = http_build_query([
        'errores' => $errores,
        'descripcion_complejo' => $descripcion_complejo,
        'fecha_fundacion_complejo' => $fecha_fundacion_complejo,
        'descripcion_sucursal' => $descripcion_sucursal,
        'fecha_fundacion_sucursal' => $fecha_fundacion_sucursal,
        'barrio' => $rela_barrio,
        'direccion' => $direccion,
        'email' => $email,
        'username' => $username,
    ]);
    header("Location: ../formulario_alta.php?$query_string");
    exit;
}
?>
