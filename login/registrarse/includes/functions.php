<?php 

function validarUsuario($conexion, $username, $email) {
    if ($username && $email) {
        $sql_verificar_usuario = "
            SELECT COUNT(*) as contador FROM usuarios u JOIN contacto c
            ON c.id_contacto = u.rela_contacto
            WHERE u.username LIKE ? OR c.descripcion_contacto LIKE ?
        ";
        $stmt = $conexion->prepare($sql_verificar_usuario);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $contador = $stmt->get_result()->fetch_assoc()['contador'];

        return $contador > 0 ? "ocupado" : "no hay";
    }
    return "vacio";
}

function validarDocumento($conexion, $documento, $tipo_documento) {
    if ($documento && $tipo_documento) {
        $sql_verificar_documento = "
            SELECT COUNT(*) as contador FROM documento 
            WHERE descripcion_documento LIKE ? AND rela_tipo_documento = ?
        ";
        $stmt = $conexion->prepare($sql_verificar_documento);
        $stmt->bind_param("si", $documento, $tipo_documento);
        $stmt->execute();
        $contador = $stmt->get_result()->fetch_assoc()['contador'];

        return $contador > 0 ? "ocupado" : "no hay";
    }
    return "vacio";
}

function validarFormulario($nombre, $apellido, $documento, $tipo_documento, $sexo, $email, $username, $contrasena, $confirmar_contrasena) {
    $errores = [];

    // Validar nombres y apellidos (sin números)
    if (!preg_match("/^[a-zA-ZáéíóúñÑ\s]+$/", $nombre)) {
        $errores[] = "El nombre contiene caracteres no permitidos.";
    }
    if (!preg_match("/^[a-zA-ZáéíóúñÑ\s]+$/", $apellido)) {
        $errores[] = "El apellido contiene caracteres no permitidos.";
    }

    // Validar documento (mínimo 6 dígitos)
    if (!preg_match("/^[a-zA-Z0-9]{6,}$/", $documento)) {
        $errores[] = "El documento debe tener al menos 6 dígitos.";
    }

    // Validar sexo (opciones válidas)
    if (!is_numeric($sexo)) {
        $errores[] = "Seleccione un sexo válido.";
    }

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email ingresado no es válido.";
    }

    // Validar contraseñas
    if ($contrasena !== $confirmar_contrasena) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    // Retornar lista de errores
    return $errores;
}

?>