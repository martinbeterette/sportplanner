<?php  
session_start();
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("Falta GET de Sucursal :(");

$descripcion_tarifa = $_POST['descripcion_tarifa'];
$precio = $_POST['precio'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
// echo $descripcion_tarifa. $precio. $hora_desde;die;

// Consulta para obtener todas las tarifas de la sucursal
$sql = "SELECT hora_inicio, hora_fin FROM tarifa WHERE rela_sucursal = ? AND estado IN(1)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_sucursal);
$stmt->execute();
$result = $stmt->get_result();

$solapamiento = false;

// Verificar la superposición de horarios
while ($row = $result->fetch_assoc()) {
    $bd_hora_inicio = $row['hora_inicio'];
    $bd_hora_fin = $row['hora_fin'];

    // Verificar si los rangos cruzan la medianoche
    $cruza_medianoche_bd = ($bd_hora_inicio > $bd_hora_fin);
    $cruza_medianoche_form = ($hora_inicio > $hora_fin);

    // Lógica para comparar los horarios
    if ($cruza_medianoche_bd || $cruza_medianoche_form) {
        // Caso donde uno de los rangos cruza la medianoche
        // Verificar las condiciones para el rango cruzado
        if (
            ($hora_inicio <= $bd_hora_fin || $hora_fin >= $bd_hora_inicio) ||  // Si los horarios de formulario cruzan el rango existente
            ($bd_hora_inicio <= $hora_fin || $bd_hora_fin >= $hora_inicio)     // O si el rango existente cruza los horarios del formulario
        ) {
            $solapamiento = true;
            break;
        }
    } else {
        // Caso normal (sin cruzar medianoche)
        if (
            ($hora_inicio < $bd_hora_fin && $hora_fin > $bd_hora_inicio) // Verificar solapamiento simple
        ) {
            $solapamiento = true;
            break;
        }
    }
}

// Resultado de la validación
if ($solapamiento) {
	$stmt->close();
	$conexion->close();
    header("Location: tabla_tarifa.php?tarifa_solapada&id_sucursal=$id_sucursal");
    exit();
} else {
    // Aquí puedes proceder a insertar la nueva tarifa
    $insert_sql = "INSERT INTO tarifa (descripcion_tarifa,precio,hora_inicio, hora_fin, rela_sucursal) VALUES (?,?,?,?, ?)";
    $insert_stmt = $conexion->prepare($insert_sql);
    $insert_stmt->bind_param("sissi", $descripcion_tarifa,$precio,$hora_inicio, $hora_fin, $id_sucursal);
    if($insert_stmt->execute()) {
    	$insert_stmt->close();
    	$conexion->close();
    	header("Location: tabla_tarifa.php?id_sucursal=$id_sucursal");
    	exit();
    }
    

}


?>