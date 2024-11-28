<?php 
require_once("../../config/database/conexion.php");

$descripcion 		= $_POST['descripcion'];
$cantidad 			= $_POST['cantidad'];
$categoria 			= $_POST['categoria'];
$estado 			= $_POST['estado'];


$sql = "INSERT INTO 
					insumo(descripcion_insumo,cantidad,fecha_alta,categoria,rela_estado_insumo)
		VALUES
			('$descripcion',$cantidad,CURRENT_DATE(),'$categoria',$estado)";

if ($conexion->query($sql)) {
	header("Location: tablaInsumos.php");
}

?>