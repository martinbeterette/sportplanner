<?php
require_once("../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");
require_once(RUTA. "config/database/db_functions/personas.php");
session_start();
// Suponiendo que ya tienes conexión a la base de datos almacenada en $conexion
//paso 1
$descripcion_complejo       = $_POST['descripcion_complejo'];
$fecha_fundacion_complejo   = $_POST['fecha_fundacion_complejo'];

//paso 2
$descripcion_sucursal       = $_POST['descripcion_sucursal'];
$fecha_fundacion_sucursal   = $_POST['fecha_fundacion_sucursal'];
$rela_barrio                = $_POST['barrio']; // Este valor viene del formulario
$direccion                  = $_POST['direccion'];


//paso 3
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$rela_contacto = $_SESSION['id_email'];
$fecha_creacion_usuario = date('Y-m-d');

//validaciones de los inputs

    // Validar descripcion_complejo: al menos 4 caracteres, solo letras y números
    if (!preg_match('/^[A-Za-z0-9\s]{4,}$/', $descripcion_complejo)) {
        die("Descripción de complejo no válida.");
    }

    // Validar fecha_fundacion_complejo: formato yyyy-mm-dd y no posterior a hoy
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_fundacion_complejo) || strtotime($fecha_fundacion_complejo) > time()) {
        die("Fecha de fundación de complejo no válida.");
    }

    // Validar descripcion_sucursal: al menos 4 caracteres, solo letras y números
    if (!preg_match('/^[A-Za-z0-9\s]{4,}$/', $descripcion_sucursal)) {
        die("Descripción de sucursal no válida.");
    }

    // Validar fecha_fundacion_sucursal: formato yyyy-mm-dd y no posterior a hoy
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_fundacion_sucursal) || strtotime($fecha_fundacion_sucursal) > time()) {
        die("Fecha de fundación de sucursal no válida.");
    }

    // Validar rela_barrio: entero
    if (!filter_var($rela_barrio, FILTER_VALIDATE_INT)) {
        die("Barrio no válido.");
    }

    // Validar direccion: letras, números y algunos caracteres especiales como -, ., /
    if (!preg_match('/^[A-Za-z0-9\s\-\.\/]{4,}$/', $direccion)) {
        die("Dirección no válida.");
    }

    // Validar email: formato general de email
    if (!preg_match('/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,8}$/', $email)) {
        die("Email no válido.");
    }

    // Validar password: al menos 8 caracteres
    if (strlen($password) < 8) {
        die("Contraseña no válida.");
    }


//verificar y hashear contraseña
if ($password === $confirm_password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
} else {
    echo "Error: Las contraseñas no coinciden :( <br> <a href='".BASE_URL."index2.php'>Volver al inicio</a>";
}


$rela_persona = $_SESSION['id_persona'];

$fecha_alta = date('Y-m-d');


try {
    // Iniciar la transacción
    $conexion->begin_transaction();

    // Insertar en la tabla Complejo
    $sql_complejo = "INSERT INTO Complejo (descripcion_complejo, fecha_fundacion, fecha_alta, verificado) 
                     VALUES (?, ?, ?, 'no verificado')";
    $stmt_complejo = $conexion->prepare($sql_complejo);
    $stmt_complejo->bind_param('sss', $descripcion_complejo, $fecha_fundacion_complejo, $fecha_alta);
    $stmt_complejo->execute();
    // Obtener el ID generado del complejo
    $rela_complejo = $conexion->insert_id;

    ///////////////////////////PASO 3///////////////////////////////////////////////////
    // insertar usuario propietario para gestion
    $sql_usuario = "INSERT INTO usuarios (username,password, rela_contacto,estado,rela_perfil) 
                    VALUES (?,?,?,'no verificado',23) ";
    $stmt_usuario = $conexion->prepare($sql_usuario);
    $stmt_usuario->bind_param('ssi', $username, $hashed_password, $rela_contacto);
    $stmt_usuario->execute();
    $rela_usuario = $conexion->insert_id;


    // Insertar en la tabla Asignacion_persona_complejo
    $sql_asignacion_pc = "INSERT INTO Asignacion_persona_complejo (fecha_alta,rela_persona, rela_complejo) 
                          VALUES (?, ?, ?)";
    $stmt_asignacion_pc = $conexion->prepare($sql_asignacion_pc);
    $stmt_asignacion_pc->bind_param('sii', $fecha_alta, $rela_persona, $rela_complejo);
    $stmt_asignacion_pc->execute();

    // Insertar en la tabla Sucursal
    $sql_sucursal = "INSERT INTO Sucursal (descripcion_sucursal, fecha_de_creacion,fecha_alta,rela_complejo) 
                     VALUES (?, ?, ?, ?)";
    $stmt_sucursal = $conexion->prepare($sql_sucursal);
    $stmt_sucursal->bind_param('sssi', $descripcion_sucursal, $fecha_fundacion_sucursal,$fecha_alta, 
        $rela_complejo);
    $stmt_sucursal->execute();

    // Obtener el ID generado de la sucursal
    $rela_sucursal = $conexion->insert_id;

    // Insertar en la tabla Asignacion_sucursal_domicilio
    $sql_asignacion_sd = "INSERT INTO Asignacion_sucursal_domicilio (rela_barrio, rela_sucursal, direccion) 
                          VALUES (?, ?, ?)";
    $stmt_asignacion_sd = $conexion->prepare($sql_asignacion_sd);
    $stmt_asignacion_sd->bind_param('iis', $rela_barrio, $rela_sucursal, $direccion);
    $stmt_asignacion_sd->execute();

    // Si todo fue bien, confirmar la transacción
    $conexion->commit();

    echo "Transacción realizada con éxito.";
} catch (Exception $e) {
    // En caso de error, hacer rollback
    $conexion->rollback();
    echo "Error en la transacción: " . $e->getMessage();
} finally {
    // Cerrar los prepared statements
    $stmt_complejo->close();
    $stmt_asignacion_pc->close();
    $stmt_sucursal->close();
    $stmt_asignacion_sd->close();
    $stmt_usuario->close();
    $conexion->close();
    header("Location: ". BASE_URL ."login/verificacion_correo/register.php?username={$username}&email={$email}&verificar_propietario");
}
?>
