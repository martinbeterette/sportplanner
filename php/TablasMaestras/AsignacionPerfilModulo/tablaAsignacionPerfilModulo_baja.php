<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
    
$id = $_GET['id_asignacion_perfil_modulo'];

//eliminar el producto
$sql = "DELETE FROM asignacion_perfil_modulo 
        	WHERE id_asignacion_perfil_modulo = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: tablaAsignacionPerfilModulo.php"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>