<?php  
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
    $sql_asignacion_pc = "INSERT INTO Asignacion_persona_complejo (fecha_alta,rela_persona, rela_complejo,rela_usuario) 
                          VALUES (?, ?, ?,?)";
    $stmt_asignacion_pc = $conexion->prepare($sql_asignacion_pc);
    $stmt_asignacion_pc->bind_param('siii', $fecha_alta, $rela_persona, $rela_complejo,$rela_usuario);
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

    header("Location: ". BASE_URL ."login/verificacion_correo/register.php?username={$username}&email={$email}&verificar_propietario");
}

?>	