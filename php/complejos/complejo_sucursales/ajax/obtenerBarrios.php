<?php  

require_once("../../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");
// Obtención y validación del ID de provincia
$id_localidad = isset($_REQUEST['id_localidad']) ? filter_var($_REQUEST['id_localidad'], FILTER_VALIDATE_INT) : null;
if ($id_localidad === null || $id_localidad === false) {
    exit("Error: 'id_localidad' es requerido y debe ser un número entero válido.");
}

$registros = obtenerBarrios($id_localidad);
echo json_encode($registros);

function obtenerBarrios($id_localidad) {
	global $conexion;

	$sql = "
		SELECT * FROM barrio WHERE rela_localidad = ?
	";
	$stmt = $conexion->prepare($sql);
	$stmt->bind_param("i",$id_localidad);
	$stmt->execute();
	$registros = $stmt->get_result();
	$barrios = [];
	foreach($registros as $reg) {
		$barrios[] = $reg;
	}
	return $barrios;


}

?>