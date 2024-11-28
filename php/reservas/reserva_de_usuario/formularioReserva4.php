<?php 
session_start();
require_once('../../../config/root_path.php');
require_once(RUTA.'config/database/db_functions/zonas.php');
require_once(RUTA.'config/database/conexion.php');

$rela_horario 	= $_POST['id_horario'];
$fecha 			= $_POST['fecha'];
$rela_persona 	= $_POST['persona'];
$rela_zona 		= $_POST['cancha'];
$monto_base     = $_POST['monto_base'];
$monto_final    = $_POST['monto_final'];
$id_tarifa 		=	(int)$_POST['id_tarifa'];

// echo $rela_horario."<br>".$fecha."<br>".$rela_zona."<br>".$rela_persona;die;



if(insertarReserva($rela_horario, $fecha, $rela_zona, $rela_persona)) {
	$hoy = date('Y-m-d');
	$id_reserva = $conexion->insert_id;
	$queryControl = "INSERT INTO control(fecha_alta,rela_estado_control,rela_reserva,monto_base,monto_final,rela_tarifa) VALUES(CURDATE(),1,$id_reserva,$monto_base,$monto_final, $id_tarifa)";
	$conexion->query($queryControl);

	$querySucursal = "SELECT id_sucursal FROM sucursal JOIN zona ON rela_sucursal = id_sucursal WHERE id_zona=$rela_zona";
	$reg = $conexion->query($querySucursal);
	$id_sucursal = $reg->fetch_assoc()['id_sucursal'];
	$cancha = $conexion->query("SELECT * FROM zona WHERE id_zona = $rela_zona")->fetch_assoc();
	$queryNotificacion = "
		INSERT INTO notificacion(titulo,mensaje,rela_sucursal,estado,rela_reserva) 
		VALUES('Reserva',
				'El usuario {$_SESSION['usuario']} quiere reservar la cancha de Descripcion: {$cancha['descripcion_zona']}',
				$id_sucursal,'no leido',
				$id_reserva
			)";
	$conexion->query($queryNotificacion);

	header("Location: formularioReserva1.php");
} else {
	echo "error en la reserva";
	echo "<a href='formularioReserva1.php'>Volver</a>";
}
?>