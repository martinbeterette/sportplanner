<?php 

	require_once("../../../../config/root_path.php");
	require_once(RUTA . "config/database/conexion.php");

	$id_empleado = isset($_GET['id_empleado']) ? $_GET['id_empleado'] : die("Ha ocurrido un error :( <br> <a href='". BASE_URL .  "index2.php" . "'>volver</a>");
	$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("Ha ocurrido un error :( <br> <a href='". BASE_URL .  "index2.php" . "'>volver</a>");

	//eliminar el producto
	$sql = "UPDATE empleado 
	        	SET
	        		estado = 0
	        	WHERE id_empleado = $id_empleado;";

	//ejecutar la consulta o error
	if ($conexion->query($sql)) {
	    header("Location: ../../index1.php?id_sucursal=$id_sucursal"); 
	} else {
	    echo "error al actualizar el registro: " . $conexion->error;
	}
?>