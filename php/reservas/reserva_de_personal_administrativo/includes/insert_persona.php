<?php
session_start();
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");


// Saneamiento de datos
$nombre = $conexion->real_escape_string($_REQUEST['nombre']);
$apellido = $conexion->real_escape_string($_REQUEST['apellido']);
$documento = $conexion->real_escape_string($_REQUEST['documento']);
$correo = filter_var($_REQUEST['correo'], FILTER_VALIDATE_EMAIL);
$tipo_documento = intval($_REQUEST['id_tipo_documento']);
$id_sexo = intval($_REQUEST['id_sexo']);
$fecha_nacimiento = $_REQUEST['fecha_nacimiento'];


//valido la fecha
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_nacimiento)) {
    echo "error: formato de fecha invalido";
    exit();
}
// Validación: Verifica si la persona ya existe
$sql_validacion = "
    SELECT COUNT(*) AS existe 
    FROM persona 
    JOIN documento ON documento.rela_persona = persona.id_persona 
    WHERE documento.descripcion_documento = '$documento' 
    AND documento.rela_tipo_documento = $tipo_documento
";

$resultado_validacion = $conexion->query($sql_validacion);
if (!$resultado_validacion) {
    echo ('error de validacion');
    exit();
}

$fila = $resultado_validacion->fetch_assoc();
if ($fila['existe'] > 0) {
    echo ('existe');
    exit();
}




try {

    $conexion->begin_transaction();

    if (!$correo) {
        throw new Exception("El correo no está definido.");
    }
    //verificamos si el correo ya existe
    $reg_correo = $conexion->query("SELECT * from contacto WHERE descripcion_contacto = '$correo'");
    if ($reg_correo->num_rows > 0) {
        echo "correo existente";
        exit();
    }



    // Inserción en `persona`
    $sql_persona = "
        INSERT INTO persona (nombre, apellido, fecha_nacimiento, rela_sexo) 
        VALUES (?, ?,?,?)
    ";
    $stmt_persona = $conexion->prepare($sql_persona);
    $stmt_persona->bind_param("sssi", $nombre, $apellido, $fecha_nacimiento, $id_sexo);
    $stmt_persona->execute();
    //id_persona insertada
    $id_persona = $conexion->insert_id;

    $stmt_documento = $conexion->prepare("INSERT INTO documento 
        (descripcion_documento, rela_tipo_documento, rela_persona) VALUES(?,?,?)");
    $stmt_documento->bind_param("sii", $documento, $tipo_documento, $id_persona);
    $stmt_documento->execute();

    $stmt_contacto = $conexion->prepare(
        "
        INSERT INTO contacto 
        (rela_tipo_contacto, descripcion_contacto, rela_persona)
        VALUES(
            1,
            ?,
            ?
        )
        "
    );
    $stmt_contacto->bind_param("si", $correo, $id_persona);
    $stmt_contacto->execute();
    $id_contacto = $conexion->insert_id;

    $username = $documento;
    $documento_hasheado = password_hash($documento, PASSWORD_DEFAULT);
    $stmt_usuario = $conexion->prepare(
        "INSERT INTO usuarios
        (expiry,estado,rela_perfil,fecha_alta,username,password, rela_contacto)
        VALUES(
            NOW(), 'no verificado', 1 , CURDATE() , ?, ?, ?
        )
        "
    );
    $stmt_usuario->bind_param("ssi", $username, $documento_hasheado, $id_contacto);
    $stmt_usuario->execute();

    $conexion->commit();
} catch (Exception $e) {
    $conexion->rollback();
    echo ("error durante la consulta");
    echo "error: " . $e->getMessage();
    exit();
}



// Respuesta exitosa
echo 'success';
