<?php
require_once("../../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");

// Obtener los datos del formulario
$id_sucursal = $_POST['id_sucursal'];
$id_complejo = $_POST['id_complejo'];
$descripcion_sucursal = $_POST['descripcion_sucursal'];
$direccion = $_POST['direccion'];
$barrio = $_POST['barrio'];
$localidad = $_POST['localidad'];
$provincia = $_POST['provincia'];
$fecha_creacion = $_POST['fecha_de_creacion'];

$conexion->begin_transaction();
try {
    
    // Actualizar la sucursal
    $query = "UPDATE sucursal s JOIN asignacion_sucursal_domicilio asd
                ON s.id_sucursal = asd.rela_sucursal
              SET s.descripcion_sucursal = ?, 
                  s.fecha_de_creacion = ?,
                  asd.direccion = ?,
                  asd.rela_barrio = ?
              WHERE id_sucursal = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sssii", $descripcion_sucursal, $fecha_creacion, $direccion, $barrio,$id_sucursal);
    $stmt->execute();
    $conexion->commit();
    $conexion->close();
    $stmt->close();
    // Redirigir de vuelta a la lista de sucursales
    header("Location: ../complejo.php?id_complejo=$id_complejo");
    exit();
} catch (Exception $e) {
    $conexion->rollBack();
    echo "error: ". $e->getMessage();
}
?>
