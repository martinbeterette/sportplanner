<?php  
	session_start();
	require_once('../../../../config/root_path.php');
	require_once(RUTA .'config/database/conexion.php');
	$registros_por_pagina = isset($_GET['registros_por_pagina']) ? $_GET['registros_por_pagina'] : 5; 

	if (isset($_GET['pagina']) && is_numeric($_GET['pagina'])) {
	    $pagina_actual = (int)$_GET['pagina'];
	} else {
	    $pagina_actual = 1;
	}

	$offset = ($pagina_actual - 1) * $registros_por_pagina;

	// Obtenemos el filtro
	$search = isset($_GET['filtro']) ? $_GET['filtro'] : '';
	$search_param = "%" . $conexion->real_escape_string($search) . "%";

	function obtenerMisReservas($conexion, $id_persona, $search_param, $registros_por_pagina, $offset) {
		// Consulta
		$select = "SELECT 
						id_reserva,
						horario_inicio,
						horario_fin,
						fecha_reserva,
						reserva.fecha_alta,
						descripcion_zona,
						descripcion_sucursal,
						monto_pagado,
						monto_total
					FROM reserva
					JOIN horario ON rela_horario = id_horario
					JOIN zona ON rela_zona = id_zona
					JOIN sucursal ON rela_sucursal = id_sucursal ";	
		$condicional = "WHERE (reserva.rela_persona = ?) ";
		$condicional .= "AND 
		(
			id_reserva LIKE ? OR 
			horario_inicio LIKE ? OR
			fecha_reserva LIKE ? OR
			descripcion_sucursal LIKE ? 
		) 
		";
		$orderBy =  "ORDER BY (fecha_reserva) DESC ";
		$paginado = "LIMIT $registros_por_pagina OFFSET $offset ";

		$query = $select. $condicional .$orderBy. $paginado;
		$stmt = $conexion->prepare($query);
		$stmt->bind_param("issss",	$id_persona, 
									$search_param,
									$search_param,
									$search_param,
									$search_param
						);
		$stmt->execute();
		$registros = $stmt->get_result();
		
		$stmt->close(); // Cerramos $stmt
		return $registros;
	}

	function obtenerPaginasMisReservas($conexion, $id_persona, $search_param, $registros_por_pagina) {
		// Consulta
		$select = "SELECT 
						count(id_reserva) as total
					FROM reserva
					JOIN horario ON rela_horario = id_horario
					JOIN zona ON rela_zona = id_zona
					JOIN sucursal ON rela_sucursal = id_sucursal ";	
		$condicional = "WHERE (reserva.rela_persona = ?) ";
		$condicional .= "AND 
		(
			id_reserva LIKE ? OR 
			horario_inicio LIKE ? OR
			fecha_reserva LIKE ? OR
			descripcion_sucursal LIKE ? 
		) 
		";

		$query = $select. $condicional;
		$stmt_count = $conexion->prepare($query);
		$stmt_count->bind_param("issss",	$id_persona, 
										$search_param,
										$search_param,
										$search_param,
										$search_param
							);
		$stmt_count->execute();
		$result_count = $stmt_count->get_result();
		$total_items = $result_count->fetch_assoc()['total'];
		$total_pages = ceil($total_items / $registros_por_pagina);

		$stmt_count->close(); // Cerramos $stmt_count
		return $total_pages;
	}

	// Llamadas a las funciones
	$registrosMisReservas = obtenerMisReservas(
												$conexion, 
												$_SESSION['id_persona'], 
												$search_param, 
												$registros_por_pagina, 
												$offset
											);
	$tabla = "<table>";

		$tabla .= "<thead>";
			$tabla .= "<tr>";

				// Cabecera de la tabla
				$tabla .= "<th>ID reserva</th>";
				$tabla .= "<th>Horario</th>";
				$tabla .= "<th>Fecha de reserva</th>";
				$tabla .= "<th>Sucursal</th>";
				$tabla .= "<th>Ver</th>";
				$tabla .= "<th colspan='2'>Acciones</th>";


			$tabla .= "</tr>";
		$tabla .= "</thead>";

		$tabla .= "</tbody>";

			foreach ($registrosMisReservas as $reg) {
				$tabla .= "<tr>";

					$tabla .= "<td>" . $reg['id_reserva'] . "</td>";
					$tabla .= "<td>" . $reg['horario_inicio'] . "</td>";
					$tabla .= "<td>" . $reg['fecha_reserva'] . "</td>";
					$tabla .= "<td>" . $reg['descripcion_sucursal'] . "</td>";
					// Botón ver más
					$tabla .= 
					"<td 
						class='ver_mas'
						data-ver_mas='". htmlspecialchars(json_encode($reg), ENT_QUOTES, 'UTF-8') ."'
					>
						Ver
					</td>";
					// Botón eliminar
					$tabla .= 
						'<td class="acciones">' . 
							'<accion href="" 
								class="eliminar" 
								valor="' . $reg['id_reserva'] . '"
							><img src="' . BASE_URL . 'assets/icons/icons8-basura-llena.svg' . '"></accion>' . '</td>';

				$tabla .= "</tr>";
			}

		$tabla .= "</tbody>";

	$tabla .= "</table>";

	$total_pages = obtenerPaginasMisReservas(
												$conexion, 
												$_SESSION['id_persona'], 
												$search_param,
												$registros_por_pagina
											);
	$registros = [
		"tabla" 		=> $tabla,
		"total_pages" 	=> $total_pages,
		"current_page" 	=> $pagina_actual
	];

	echo json_encode($registros);
?>
