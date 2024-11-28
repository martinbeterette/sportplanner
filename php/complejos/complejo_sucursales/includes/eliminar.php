<?php 
	echo "eliminar TODO CORRECTO"; 
	require_once("../../../../config/root_path.php");
	require_once(RUTA. "config/database/conexion.php");

	if (isset($_GET['id_sucursal'])) {
	    $id_sucursal = (int) $_GET['id_sucursal'] ?? die("falta get de sucursal");;
	    $id_complejo = (int) $_GET['id_complejo'] ?? die("falta get de complejo");;

	    // Actualizar el estado de la sucursal a 0 para "eliminar" lógicamente
	    $query = "UPDATE sucursal SET estado = 0 WHERE id_sucursal = $id_sucursal";

	    if ($conexion->query($query) === TRUE) {
	        // Redireccionamos al usuario de regreso al listado de sucursales
	        header("Location: ../complejo.php?id_complejo=$id_complejo&mensaje=eliminado");
	        exit();
	    } else {
	        echo "Error al eliminar la sucursal: " . $conexion->error;
	    }
	} else {
	    echo "ID de sucursal no especificado.";
	}

$conexion->close();



?>