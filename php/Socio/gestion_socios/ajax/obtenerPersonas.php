<?php  
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
header('Content-Type: application/json');
$filtro = $_REQUEST['filtro'];
$parametro = filter_var($filtro, FILTER_SANITIZE_SPECIAL_CHARS);

$filtro = "%" . $parametro ."%";
$registros = obtenerPersonas($filtro);

echo json_encode($registros);



function obtenerPersonas($filtro) {
	global $conexion;
	$query = "
		SELECT id_persona, nombre,apellido,descripcion_documento, descripcion_tipo_documento, descripcion_sexo
		FROM persona p JOIN documento d ON p.id_persona = d.rela_persona
		JOIN sexo s ON p.rela_sexo = s.id_sexo
		JOIN tipo_documento ON d.rela_tipo_documento = id_tipo_documento
		WHERE 
			(
				nombre LIKE ?
				OR apellido LIKE ?
				OR descripcion_documento LIKE ?
				OR descripcion_tipo_documento LIKE ?
				OR descripcion_sexo LIKE ?
			)
		LIMIT 10
	";

	$stmt_persona = $conexion->prepare($query);
	$stmt_persona->bind_param("sssss",$filtro, $filtro, $filtro, $filtro, $filtro);
	if($stmt_persona->execute()) {
		$registros = $stmt_persona->get_result();
		$personas = [];
		foreach($registros as $reg){
			$personas[] = $reg;
		}
		return $personas;
	}
	return false;
}
?>