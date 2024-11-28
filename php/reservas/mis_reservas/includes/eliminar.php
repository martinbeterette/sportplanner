<?php  
require_once('../../../../config/root_path.php');
require_once(RUTA . 'config/database/conexion.php');

function cancelarReserva($id_reserva, $motivo,$conexion) {
	$sql = "UPDATE reserva SET rela_estado_reserva = 5, descripcion_reserva = ? WHERE id_reserva = ?";
	$stmt = $conexion->prepare($sql);
	$stmt->bind_param("si",$motivo,$id_reserva);
	if ($stmt->execute()) {
		return true;
	}

	return false;
}

$id_reserva = isset($_GET['id_reserva']) ? $_GET['id_reserva'] : die("No se ha encontrado la reserva");
$motivo 	= isset($_GET['motivo']) ? $_GET['motivo'] : die("No se ha adjuntado un motivo");
if (cancelarReserva($id_reserva,$motivo,$conexion)) {
	header("Location: ../mis_reservas.php");
} else {
	echo "error durante la consulta :(";
	echo "<br>";
	echo "<a href='../mis_reservas.php'>Volver</a>";
}


?>