<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if (isset($_GET['id_dia'], $_GET['id_sucursal'])) {
    $idDia = $_GET['id_dia'];
    $idSucursal = $_GET['id_sucursal'];

    $sql = "
        SELECT 
            i.id_itinerario,
            i.horario_desde,
            i.horario_hasta
        FROM dia d
        LEFT JOIN itinerario i ON d.id_dia = i.rela_dia AND i.rela_sucursal = ?
        WHERE d.id_dia = ?
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $idSucursal, $idDia);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $datos = $resultado->fetch_assoc();

    echo json_encode($datos ? $datos : ['horario_desde' => null, 'horario_hasta' => null]);
    
}
?>
