<?php
// Require de conexión a la base de datos
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
// Función para actualizar el estado de asistencia y la hora de llegada
function actualizarAsistencia($id_sucursal, $id_reserva, $pagina_actual) {
    global $conexion; // Accedemos a la conexión global (ajusta esta línea si usas un objeto de conexión)

    // Hora actual en formato "HH:MM"
    $hora_actual = date("H:i");

    // SQL para actualizar los campos `hora_llegada` y `estado_asistencia`
    $sql = "UPDATE RESERVA JOIN control ON id_reserva = rela_reserva SET 
                control.entrada = ?, 
                rela_estado_control = 2
            WHERE id_reserva = ?";

    // Preparar y ejecutar la consulta
    if ($stmt = $conexion->prepare($sql)) {
        // Vinculamos parámetros
        $stmt->bind_param("si", $hora_actual, $id_reserva);

        // Ejecutamos la consulta y verificamos el resultado
        if ($stmt->execute()) {
            // Redirigir a index.php si la actualización fue exitosa
            header("Location: ../listado_reservas.php?id_sucursal=$id_sucursal&pagina_actual=$pagina_actual");
            exit();
        } else {
            echo "Error al actualizar: " . $stmt->error;
        }

        // Cerramos el statement
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }
}

// Llamada a la función con los parámetros recibidos por REQUEST
if (isset($_REQUEST['id_sucursal']) && isset($_REQUEST['id_reserva'])) {
    $pagina_actual = $_REQUEST['pagina_actual'] ?? 1;
    $id_sucursal = $_REQUEST['id_sucursal'];
    $id_reserva = $_REQUEST['id_reserva'];
    $metodo_pago = $_REQUEST['metodo_pago'] ?? 1;

    // Aquí podrías añadir una función de control de acceso usando el $id_sucursal

    // Llamamos a la función de actualización
    actualizarAsistencia($id_sucursal, $id_reserva, $pagina_actual);
} else {
    echo "Parámetros incompletos.";
}
?>
