<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if (isset($_POST['rela_persona'])) {
    $rela_persona = $_POST['rela_persona'];

    // Consulta para obtener los detalles de la reserva
    $query = "SELECT c.descripcion_complejo,
                        m.descripcion_membresia, 
                        m.descuento, 
                        m.precio_membresia, 
                        s.fecha_alta, 
                        s.fecha_afiliacion, 
                        s.fecha_expiracion, 
                        p.nombre, 
                        p.apellido, 
                        d.descripcion_documento, 
                        con.descripcion_contacto,
                        s.id_socio,
                        s.estado AS estado_socio,
                        s.rela_persona 
                FROM membresia m 
                JOIN socio s ON m.id_membresia = s.rela_membresia
                JOIN persona p ON s.rela_persona = p.id_persona
                JOIN documento d ON p.id_persona = d.rela_persona
                JOIN contacto con ON con.rela_persona = p.id_persona
                JOIN complejo c ON s.rela_complejo = c.id_complejo
                WHERE s.rela_persona = $rela_persona";

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
