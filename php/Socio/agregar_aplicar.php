<?php  
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
$id_complejo = $_POST['id_complejo'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$descripcion_documento = $_POST['descripcion_documento'];
$rela_sexo = (int) $_POST['descripcion_sexo'];
$descripcion_contacto = $_POST['telefono'];
$rela_tipo_documento = $_POST['tipo_documento'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$rela_membresia = $_POST['rela_membresia'];

$sqlValidarPersona = "SELECT p.nombre, p.apellido, d.descripcion_documento, p.fecha_nacimiento
                        FROM persona p
                        JOIN documento d ON d.id_documento = p.rela_documento
                        WHERE d.descripcion_documento = ? 
                        AND d.rela_tipo_documento = ?";

$stmt = $conexion->prepare($sqlValidarPersona);
$stmt->bind_param('si', $descripcion_documento, $rela_tipo_documento);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $persona = $resultado->fetch_assoc();
    $nombre = $persona['nombre']; 
    $apellido = $persona['apellido']; 
    $documento = $persona['descripcion_documento']; 
    $fechaNacimiento = $persona['fecha_nacimiento'];

    $get = "persona_repetida&nombre={$nombre}&apellido={$apellido}&descripcion_documento={$documento}&fecha_nacimiento={$fechaNacimiento}";

    header("Location: " .BASE_URL. "php/socio/tabla_socios.php?" . $get);

    exit();
} else {

    $conexion->begin_transaction();

    try {
        // Consulta preparada para insertar en 'documento'
        $sqlDocumento = "INSERT INTO documento (descripcion_documento, rela_tipo_documento) VALUES (?, ?)";
        $stmtDocumento = $conexion->prepare($sqlDocumento);
        $stmtDocumento->bind_param("si", $descripcion_documento, $rela_tipo_documento);
        
        if (!$stmtDocumento->execute()) {
            throw new Exception("Error al insertar en documento: " . $stmtDocumento->error);
        }
        
        // Obtener el ID del documento insertado
        $relaDocumento = $conexion->insert_id;

        // Consulta preparada para insertar en 'persona'
        $sqlPersona = "INSERT INTO persona (nombre, apellido, fecha_nacimiento, fecha_alta, rela_documento,rela_sexo) VALUES (?, ?, ?, CURRENT_DATE(), ?,?)";
        $stmtPersona = $conexion->prepare($sqlPersona);
        $stmtPersona->bind_param("sssii", $nombre, $apellido, $fecha_nacimiento, $relaDocumento, $rela_sexo);

        if (!$stmtPersona->execute()) {
            throw new Exception("Error al insertar en persona: " . $stmtPersona->error);
        }

        // Obtener el ID de la persona insertada
        $relaPersona = $conexion->insert_id;

        // Consulta preparada para insertar en 'empleado'
        $sqlSocio = "INSERT INTO socio (rela_membresia, fecha_alta, rela_persona, rela_complejo) VALUES (?, CURRENT_DATE(), ?, ?)";
        $stmtSocio = $conexion->prepare($sqlSocio);
        $stmtSocio->bind_param("iii", $rela_membresia, $relaPersona, $id_complejo);

        if (!$stmtSocio->execute()) {
            throw new Exception("Error al insertar en empleado: " . $stmtSocio->error);
        }

        $sqlContacto = "INSERT INTO contacto (descripcion_contacto, rela_tipo_contacto, rela_persona) VALUES (?, 2, ?)";
        $stmtContacto = $conexion->prepare($sqlContacto);
        $stmtContacto->bind_param("si", $descripcion_contacto, $relaPersona);

        if (!$stmtContacto->execute()) {
            throw new Exception("Error al insertar en contacto: " . $stmtContacto->error);
        }

        // Si todo va bien, confirmamos la transacción
        $conexion->commit();

        // Redirigir después de la inserción exitosa
        header("Location:" .BASE_URL. "php/socio/tabla_socios.php?id_complejo={$id_complejo}");

    } catch (Exception $e) {
        // Si ocurre un error, revertimos la transacción
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Cerrar las declaraciones preparadas y la conexión
    $stmtDocumento->close();
    $stmtPersona->close();
    $stmtSocio->close();
    $stmtContacto->close();
    $conexion->close();

}





?>