<?php
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once("../includes/functions.php");

function marcarInasistencia($id_sucursal, $id_reserva, $pagina_actual) {
    // Usamos hora actual para la salida (en formato 'HH:MM')
    $horaSalida = date('H:i');
    
    // Conexión a la base de datos
    global $conexion;

    // Consulta de actualización
    $sql = "UPDATE control SET rela_estado_control = 4, entrada = null, salida = null WHERE rela_reserva = ?";

    // Preparar y ejecutar la consulta
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $id_reserva);

    if ($stmt->execute()) {
        // Si se ejecuta correctamente, redirigir a la página principal
        header("Location: ../listado_reservas.php?id_sucursal=$id_sucursal&pagina_actual=$pagina_actual");
        exit;
    } else {
        // Si hay un error, puedes manejarlo aquí
        echo "Error al marcar la salida: " . $stmt->error;
    }
}

// Obtener parámetros de id_sucursal y id_reserva desde $_REQUEST
$id_sucursal = filter_input(INPUT_GET, 'id_sucursal',FILTER_SANITIZE_NUMBER_INT) ?? null;
$id_reserva = $_REQUEST['id_reserva'] ?? null;

$pagina_actual = $_REQUEST['pagina_actual'] ?? 1;


// Llamar a la función si tenemos los parámetros necesarios
if ($id_sucursal && $id_reserva) {
    marcarInasistencia($id_sucursal, $id_reserva, $pagina_actual);
} else {
    echo "Parámetros faltantes.";
}
?>
