<?php
require_once("../../config/database/conexion.php");
$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("falta GET de sucursal");

$nombre             = $_POST['nombre'];
$apellido           = $_POST['apellido'];
$documento          = $_POST['documento'];
$tipo_documento     = $_POST['tipo_documento'];
$cargo              = $_POST['cargo'];
$fechaNacimiento    = $_POST['fecha_nacimiento'];
$sucursal           = $id_sucursal;

$sqlValidarPersona = "SELECT p.nombre, p.apellido, d.descripcion_documento, p.fecha_nacimiento
                        FROM persona p
                        JOIN documento d ON d.id_documento = p.rela_documento
                        WHERE d.descripcion_documento = ? 
                        AND d.rela_tipo_documento = ?";

$stmt = $conexion->prepare($sqlValidarPersona);
$stmt->bind_param('si', $documento, $tipo_documento);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0/*ESTO PARA QUE NO TOME ESTA VALIDACION*/) {
    $persona = $resultado->fetch_assoc();
    $nombre = $persona['nombre']; 
    $apellido = $persona['apellido']; 
    $documento = $persona['descripcion_documento']; 
    $fechaNacimiento = $persona['fecha_nacimiento'];

    $get = "persona_repetida&nombre={$nombre}&apellido={$apellido}&documento={$documento}&fecha_nacimiento={$fechaNacimiento}&id_sucursal=$id_sucursal";

    header("Location: tablaEmpleados_alta.php?" . $get);
    exit();
} else {

    $conexion->begin_transaction();

    try {
        // Consulta preparada para insertar en 'documento'
        $sqlDocumento = "INSERT INTO documento (descripcion_documento, rela_tipo_documento) VALUES (?, ?)";
        $stmtDocumento = $conexion->prepare($sqlDocumento);
        $stmtDocumento->bind_param("si", $documento, $tipo_documento);
        
        if (!$stmtDocumento->execute()) {
            throw new Exception("Error al insertar en documento: " . $stmtDocumento->error);
        }
        
        // Obtener el ID del documento insertado
        $relaDocumento = $conexion->insert_id;

        // Consulta preparada para insertar en 'persona'
        $sqlPersona = "INSERT INTO persona (nombre, apellido, fecha_nacimiento, fecha_alta, rela_documento) VALUES (?, ?, ?, CURRENT_DATE(), ?)";
        $stmtPersona = $conexion->prepare($sqlPersona);
        $stmtPersona->bind_param("sssi", $nombre, $apellido, $fechaNacimiento, $relaDocumento);

        if (!$stmtPersona->execute()) {
            throw new Exception("Error al insertar en persona: " . $stmtPersona->error);
        }

        // Obtener el ID de la persona insertada
        $relaPersona = $conexion->insert_id;

        // Consulta preparada para insertar en 'empleado'
        $sqlEmpleado = "INSERT INTO empleado (empleado_cargo, fecha_alta, rela_persona, rela_sucursal) VALUES (?, CURRENT_DATE(), ?, ?)";
        $stmtEmpleado = $conexion->prepare($sqlEmpleado);
        $stmtEmpleado->bind_param("sii", $cargo, $relaPersona, $sucursal);

        if (!$stmtEmpleado->execute()) {
            throw new Exception("Error al insertar en empleado: " . $stmtEmpleado->error);
        }

        // Si todo va bien, confirmamos la transacción
        $conexion->commit();

        // Redirigir después de la inserción exitosa
        header("Location: tablaEmpleados.php?id_sucursal=$id_sucursal");

    } catch (Exception $e) {
        // Si ocurre un error, revertimos la transacción
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Cerrar las declaraciones preparadas y la conexión
    $stmtDocumento->close();
    $stmtPersona->close();
    $stmtEmpleado->close();
    $conexion->close();


}

?>
