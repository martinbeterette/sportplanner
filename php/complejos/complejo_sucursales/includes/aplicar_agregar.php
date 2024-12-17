<?php  
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

$descripcion_sucursal 	= $_POST['descripcion_sucursal'] 		?? false;
$fecha_de_creacion 		= $_POST['fecha_de_creacion'] 			?? false;
$provincia 				= $_POST['provincia'] 					?? false;
$localidad 				= $_POST['localidad'] 					?? false;
$barrio 				= $_POST['barrio'] 						?? false;
$direccion 				= $_POST['direccion'] 					?? false;
$id_complejo 			= $_POST['id_complejo'] 				?? false;

echo $descripcion_sucursal . "<br>". 
$fecha_de_creacion . "<br>" . 
$provincia . "<br>" .
$localidad . "<br>".
$barrio . "<br>".
$direccion. "<br>".
$id_complejo. "<br>";

// exit;

$conexion->begin_transaction();

try {
	$sql_sucursal = "INSERT INTO sucursal(descripcion_sucursal, fecha_de_creacion, fecha_alta, rela_complejo)
			VALUES (?,?, CURRENT_DATE, ?)
			";
	$stmt = $conexion->prepare($sql_sucursal);
	$stmt->bind_param("ssi", $descripcion_sucursal, $fecha_de_creacion, $id_complejo);
	$stmt->execute();

	$id_sucursal = $conexion->insert_id;


	$sql_domicilio = "
		INSERT INTO asignacion_sucursal_domicilio(direccion, rela_barrio, rela_sucursal) VALUES(?,?,?)
	";

	$stmt = $conexion->prepare($sql_domicilio);
	$stmt->bind_param("sii", $direccion, $barrio,$id_sucursal);
	$stmt->execute();

	$conexion->commit();
	header("Location: ../complejo.php");
} catch (Exception $e){
	$conexion->rollback();
    echo "error: ". $e->getMessage();
}
?>