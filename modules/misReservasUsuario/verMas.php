<?php
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if (isset($_POST['id_reserva'])) {
    $idReserva = $_POST['id_reserva'];

    // Consulta para obtener los detalles de la reserva
    $query = "SELECT *
              FROM reserva r
              JOIN persona p ON r.rela_persona = p.id_persona
              JOIN horario h ON r.rela_horario = h.id_horario
              JOIN zona z ON r.rela_zona = z.id_zona
              JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
              JOIN formato_deporte fd ON z.rela_formato_deporte = fd.id_formato_deporte
              JOIN estado_reserva er ON r.rela_estado_reserva = er.id_estado_reserva
              JOIN control co ON co.rela_reserva = r.id_reserva
              WHERE r.id_reserva = $idReserva";

    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        $reserva = mysqli_fetch_assoc($resultado);
        echo json_encode($reserva);
    } else {
        echo json_encode(['error' => 'No se pudo obtener los datos de la reserva']);
    }

    mysqli_free_result($resultado);
    mysqli_close($conexion);
}
