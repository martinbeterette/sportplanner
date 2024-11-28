<?php
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

function marcarSalida($id_sucursal, $id_reserva, $pagina_actual) {
    // Usamos hora actual para la salida (en formato 'HH:MM')
    $horaSalida = date('H:i');
    
    // Conexión a la base de datos
    global $conexion;

    // Consulta de actualización
    $sql = "UPDATE RESERVA JOIN control ON id_reserva = rela_reserva SET 
                control.salida = ?, 
                rela_estado_control = 3
            WHERE id_reserva = ?";

    // Preparar y ejecutar la consulta
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('si', $horaSalida, $id_reserva);

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
$id_sucursal = $_REQUEST['id_sucursal'] ?? null;
$id_reserva = $_REQUEST['id_reserva'] ?? null;
$pagina_actual = $_REQUEST['pagina_actual'] ?? 1;

// Llamar a la función si tenemos los parámetros necesarios
if ($id_sucursal && $id_reserva) {
    $entrada = $conexion->query("SELECT entrada FROM control WHERE rela_reserva = $id_reserva")->fetch_assoc()['entrada'];
    if(!empty($entrada)) {
        marcarSalida($id_sucursal, $id_reserva,$pagina_actual);
    } else {
         header("Location: ../listado_reservas.php?id_sucursal=$id_sucursal&no_hay_entrada");
    }
} else {
    echo "Parámetros faltantes.";
}
?>
