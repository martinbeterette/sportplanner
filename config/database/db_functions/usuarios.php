<?php 
	
	require_once(RUTA. "config/database/conexion.php");

	function obtenerUsuarios($usuario=null) {
		global $conexion;

		$consulta = "SELECT 
							usuarios.id_usuario,
							persona.nombre,
							persona.apellido,
							tipo_documento.descripcion_tipo_documento,
							documento.descripcion_documento,
							sexo.descripcion_sexo,
							usuarios.username,
							contacto.descripcion_contacto
						FROM
							persona 
						JOIN
							sexo
						ON
							persona.rela_sexo = sexo.id_sexo
						JOIN
							documento 
						ON
							persona.rela_documento = documento.id_documento
						JOIN 
							tipo_documento 
						ON 
							documento.rela_tipo_documento = tipo_documento.id_tipo_documento
						JOIN
							contacto
						ON
							contacto.rela_persona = persona.id_persona
						JOIN 
							usuarios
						ON
							usuarios.rela_contacto = contacto.id_contacto";

		$orderBy = "ORDER BY id_usuario ASC";		

		if (is_null($usuario)) {
			$sql = $consulta . " " . $orderBy;
			$stmt = $conexion->prepare($sql);
		}

		if (!is_null($usuario)) {
			$where = "WHERE id_usuario = ?";
			$sql = $consulta. " " . $where. " " . $orderBy;
			$stmt = $conexion->prepare($sql);
			$stmt->bind_param("i", $usuario);
		}

		$registros = [];
		if ($stmt->execute()) {
			$registros = $stmt->get_result();
			$stmt->close();
			return $registros;
		}


	}

?>