<?php  

require_once("../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");
// Obtención y validación del ID de provincia
$id_provincia = isset($_REQUEST['id_provincia']) ? filter_var($_REQUEST['id_provincia'], FILTER_VALIDATE_INT) : null;
if (!$id_provincia) {
    die("Error: 'id_provincia' es requerido y debe ser un número entero válido.");
}

$registros = obtenerLocalidades($id_provincia);
echo json_encode($registros);

function obtenerLocalidades($id_provincia) {
	global $conexion;

	$sql = "
		SELECT * FROM localidad WHERE rela_provincia = ?
	";
	$stmt = $conexion->prepare($sql);
	$stmt->bind_param("i",$id_provincia);
	$stmt->execute();
	$registros = $stmt->get_result();
	$localidades = [];
	foreach($registros as $reg) {
		$localidades[] = $reg;
	}
	return $localidades;


}

?>