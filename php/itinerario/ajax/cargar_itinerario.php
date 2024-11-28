<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

function obtenerDiasPorItinerario($id_sucursal) {
    global $conexion;
    $sql = "SELECT rela_dia FROM itinerario JOIN sucursal ON itinerario.rela_sucursal = sucursal.id_sucursal WHERE sucursal.id_sucursal = $id_sucursal";
    $dias_por_sucursal = [];
    $resultado = $conexion->query($sql);
    foreach ($resultado as $reg){
        $dias_por_sucursal[] = $reg;
    }
    return $dias_por_sucursal;
}

if (isset($_GET['id_sucursal'])) {

    $id_sucursal = $_GET['id_sucursal'];
} else {
    $id_sucursal = 1;
}
$dias_por_sucursal = obtenerDiasPorItinerario($id_sucursal);
header('Content-Type: application/json');
echo json_encode($dias_por_sucursal);
?>
