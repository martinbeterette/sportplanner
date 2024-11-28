<?php 
require_once('../../../config/root_path.php');
require_once(RUTA.'config/database/db_functions/zonas.php');
$rela_horario 	= $_POST['id_horario'];
$fecha 			= $_POST['fecha'];
$rela_persona 	= $_POST['persona'];
$rela_zona 		= $_POST['cancha'];
$monto_pagado   = $_POST['monto_pagado'];
$monto_total    = $_POST['monto_total'];

// echo $rela_horario."<br>".$fecha."<br>".$rela_zona."<br>".$rela_persona;die;



if(insertarReserva($rela_horario, $fecha, $rela_zona, $rela_persona,$monto_pagado,$monto_total)) {
	$querySucursal = "SELECT id_sucursal FROM sucursal JOIN zona ON rela_sucursal = id_sucursal WHERE id_zona=$rela_zona";
	$reg = $conexion->query($querySucursal);
	$id_sucursal = $reg->fetch_assoc()['id_sucursal'];
	$queryNotificacion = "INSERT INTO notificacion(mensaje,rela_sucursal,estado) VALUES('la persona $rela_persona ha reservado una cancha zzzzz', $id_sucursal,'no leido')";
	$conexion->query($queryNotificacion);

	header("Location: formularioReserva1.php");
} else {
	echo "error en la reserva";
	echo "<a href='formularioReserva1.php'>Volver</a>";
}
?>