<?php

// Validar correo electrónico
function validarCorreo($correo) {
    return filter_var($correo, FILTER_VALIDATE_EMAIL) !== false;
}

// Validar username (solo letras, números, ".", "-" y "_", sin espacios)
function validarUsername($username) {
    return preg_match('/^[\w.-]+$/', $username) === 1;
}

// Validar contraseña (solo letras, números, ".", "-" y "_", "*", sin espacios)
function validarPassword($password) {
    return preg_match('/^[\w.*-]+$/', $password) === 1;
}

// Validar nombre (solo letras y espacios)
function validarNombre($nombre) {
    return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre) === 1;
}

// Validar apellido (solo letras y espacios)
function validarApellido($apellido) {
    return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $apellido) === 1;
}

// Validar documento (DNI o cédula)
function validarDocumento($documento, $tipoDocumento) {
    // Para DNI, solo números, opcionalmente eliminando puntos
    if ($tipoDocumento === 1) {
        $documentoLimpio = str_replace('.', '', $documento);
        return is_numeric($documentoLimpio);
    } else {
        // Para otros tipos de documentos permitimos letras, permite letras y números
        return preg_match('/^[\w]+$/', $documento) === 1;
    }
    // Otras validaciones según tipo si fuera necesario
    return false;
}

// Validar sexo (solo número entero)
function validarSexo($sexo) {
    return is_numeric($sexo);
}

// Validar domicilio (letras, números, espacios, ".", "-", ",", y "°")
function validarDomicilio($domicilio) {
    return preg_match('/^[\w\s\.\-,°]+$/', $domicilio) === 1;
}

function validarPersona($id_documento, $descripcion_documento, $id_tipo_documento) {
    global $conexion;
    $sqlValidarPersona = "SELECT p.id_persona, p.nombre, p.apellido, d.descripcion_documento, p.fecha_nacimiento
                            FROM persona p
                            JOIN documento d ON d.rela_persona = p.id_persona
                            WHERE d.descripcion_documento = ? 
                            AND d.rela_tipo_documento = ?
                            AND d.id_documento != ?";
    $stmt = $conexion->prepare($sqlValidarPersona);
    $stmt->bind_param('sii', $descripcion_documento, $id_tipo_documento, $id_documento);
    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        return $resultado;
    }
    return false;
}


function actualizarEmpleado($conexion, $documento, $idDocumento,$idTipoDocumento, $nombre, $apellido, $fechaNacimiento, $sexo,$idPersona, $id) {
    // Iniciamos la transacción
    $conexion->begin_transaction();

    try {
        // Actualizar documento
        $sqlDocumento = "UPDATE documento 
                            SET descripcion_documento = ?, rela_tipo_documento = ?  
                            WHERE id_documento = ?";
        $stmtDocumento = $conexion->prepare($sqlDocumento);
        $stmtDocumento->bind_param("sii", $documento, $idTipoDocumento,$idDocumento);
        $stmtDocumento->execute();

        // Actualizar persona
        $sqlPersona = "UPDATE persona 
                        SET nombre = ?, apellido = ?, fecha_nacimiento = ?, rela_sexo = ?
                        WHERE id_persona = ?";
        $stmtPersona = $conexion->prepare($sqlPersona);
        $stmtPersona->bind_param("sssii", $nombre, $apellido, $fechaNacimiento, $sexo,$idPersona);
        $stmtPersona->execute();

        // Actualizar empleado
        // $sqlEmpleado = "UPDATE empleado 
        //                     SET empleado_cargo = ?, rela_sucursal = ?
        //                     WHERE id_empleado = ?";
        // $stmtEmpleado = $conexion->prepare($sqlEmpleado);
        // $stmtEmpleado->bind_param("sii", $cargo, $sucursal, $id);
        // $stmtEmpleado->execute();

        // Si todo salió bien, hacemos el commit
        $conexion->commit();

        // Cerramos las conexiones
        $stmtDocumento->close();
        $stmtPersona->close();

        return true; // Todo salió bien

    } catch (Exception $e) {
        // Si ocurre algún error, hacemos un rollback
        $conexion->rollback();

        // Cerramos las conexiones
        $stmtDocumento->close();
        $stmtPersona->close();

        return false; // Error en alguna parte
    } finally {
        // Aseguramos que la conexión se cierre
        $conexion->close();
    }
}

function obtenerEmpleado($id_empleado) {
    global $conexion;
    $sql = "SELECT  
                        empleado.id_empleado,
                        persona.id_persona,
                        persona.nombre,
                        persona.apellido,
                        documento.id_documento,
                        documento.descripcion_documento,
                        documento.rela_tipo_documento,
                        persona.fecha_nacimiento,
                        empleado.rela_persona,
                        empleado.empleado_cargo,
                        empleado.fecha_alta,
                        empleado.rela_sucursal,
                        persona.rela_sexo,
                        sexo.descripcion_sexo
                    FROM
                        empleado
                    JOIN
                        persona
                    ON
                        empleado.rela_persona = persona.id_persona
                    JOIN
                        sucursal
                    ON
                        empleado.rela_sucursal = sucursal.id_sucursal
                    JOIN 
                        documento
                    ON
                        documento.rela_persona = persona.id_persona
                    JOIN 
                        sexo
                    ON
                        sexo.id_sexo = persona.rela_sexo
                    WHERE
                        empleado.estado IN(1)
                    AND 
                        empleado.id_empleado = ?";

    $stmt_empleado = $conexion->prepare($sql);
    $stmt_empleado->bind_param("i", $id_empleado);
    if ($stmt_empleado->execute()) {
        $registros = $stmt_empleado->get_result();
        return $registros;
    }
    return false;
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

        $conexion->commit();
    } catch (Exception $e) {
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    }
    

}
?>


