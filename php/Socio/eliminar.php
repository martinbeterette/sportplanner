<?php 

require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

$id = isset($_GET['id']) ? $_GET['id'] : die("Ha ocurrido un error :( <br> <a href='". BASE_URL .  "index_tincho.php" . "'>volver</a>");
$id_complejo = isset($_GET['id_complejo']) ? $_GET['id_complejo'] : die("Ha ocurrido un error :( <br> <a href='". BASE_URL .  "index_tincho.php" . "'>volver</a>");

//eliminar el producto
$sql = "UPDATE socio 
        	SET
        		estado = 0
        	WHERE id_socio = $id;";

//ejecutar la consulta o error
if ($conexion->query($sql)) {
    header("Location: " . BASE_URL . "php/socio/tabla_socios.php?id_complejo={$id_complejo}"); 
} else {
    echo "error al actualizar el registro: " . $conexion->error;
}
?>