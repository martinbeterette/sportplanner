<?php
session_start();
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if (isset($_GET['id_reserva'])) {
    $id_reserva = $_GET['id_reserva'];
} else {
    echo json_encode([
        'estado' => false,
        'mensaje' => 'falta reserva'
    ]);
    exit;
}

$sql = "SELECT r.id_reserva, 
		r.fecha_reserva, 
        z.descripcion_zona, 
		s.descripcion_sucursal, 
        asd.direccion, 
        c.descripcion_complejo, 
        h.horario_inicio, 
        h.horario_fin, 
        b.descripcion_barrio,
        l.descripcion_localidad,
        p.descripcion_provincia
        FROM reserva r
        JOIN zona z ON r.rela_zona = z.id_zona
        JOIN sucursal s	ON z.rela_sucursal = s.id_sucursal
        JOIN complejo c ON s.rela_complejo = c.id_complejo
        JOIN asignacion_sucursal_domicilio asd ON asd.rela_sucursal = s.id_sucursal
        JOIN horario h ON r.rela_horario = h.id_horario
        JOIN barrio	b ON b.id_barrio = asd.rela_barrio
        JOIN localidad l ON b.rela_localidad = l.id_localidad
        JOIN provincia p ON p.id_provincia = l.rela_provincia
        WHERE id_reserva = $id_reserva";


if ($registros = $conexion->query($sql)) {
    $datos = $registros->fetch_assoc();
    echo json_encode([
        'estado' => true,
        'mensaje' => $datos
    ]);
} else {
    echo json_encode([
        'estado' => false,
        'mensaje' => $conexion->error
    ]);
}
