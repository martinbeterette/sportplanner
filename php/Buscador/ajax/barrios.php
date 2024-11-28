<?php  
	
	$conexion = new mysqli("localhost", "root", "", "proyecto_pp2");

	function obtenerBarrioBuscador($offset=null, $registros_por_pagina=null, $filtro=null) {
		global $conexion;
		$select = "SELECT 	
								id_barrio,
								descripcion_barrio,
								descripcion_localidad
							FROM
								barrio
							JOIN
								localidad
							ON
								rela_localidad = id_localidad";
		$limit = "LIMIT
					$offset, $registros_por_pagina";
		$where = "WHERE 
			    	barrio.estado IN(1)";

		if(is_null($filtro)) {
			$sql = $select . " " . $where . " " . $limit;
		} else {
			$where .= " " . "AND descripcion_barrio LIKE";
			$sql = "SELECT 	
								id_barrio,
								descripcion_barrio,
								descripcion_localidad
							FROM
								barrio
							JOIN
								localidad
							ON
								rela_localidad = id_localidad
							WHERE
								barrio.estado IN(1)
							AND
								descripcion_barrio LIKE '%{$filtro}%'
							OR
								descripcion_localidad LIKE '%{$filtro}%'
							LIMIT
							$offset, $registros_por_pagina";
		}

		if($registros = $conexion->query($sql)) {
			return $registros;

		} else {
			return false;
		}


	}
	if(isset($_GET['filtro'])) {
	$registros = obtenerBarrioBuscador();

	}


?>