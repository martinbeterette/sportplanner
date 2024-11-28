<?php 

	require_once('conexion.php');
/*
	function obtenerMembresias($registros_por_pagina=null, $offset=null) {
		global $conexion;

		$sql = "SELECT 	
						id_membresia,
						beneficio_membresia,
						descripcion_membresia,
						precio_membresia
					FROM
						membresia
					WHERE
						estado IN(1)
					LIMIT
						$registros_por_pagina
					OFFSET 
						$offset";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}
*/

	function obtenerModulos() {

		global $conexion;

		$sql = "SELECT 	
						id_modulo,
						descripcion_modulo
					FROM
						modulo";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerPerfiles() {

		global $conexion;

		$sql = "SELECT 	
						id_perfil,
						descripcion_perfil
					FROM
						perfil";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerPerfilesModulos() {

		global $conexion;

		$sql = "SELECT 	
						id_asignacion_perfil_modulo,
						descripcion_modulo,
						descripcion_perfil
					FROM
						asignacion_perfil_modulo apm
					JOIN
						modulo m
					ON
						apm.rela_modulo = m.id_modulo
					JOIN 
						perfil p
					ON
						apm.rela_perfil = p.id_perfil";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerEstadoReservas() {

		global $conexion;

		$sql = "SELECT 	
						id_estado_reserva,
						descripcion_estado_reserva
					FROM
						estado_reserva
					WHERE
						estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerControles() {

		global $conexion;

		$sql = "SELECT 	
						id_estado_control,
						descripcion_estado_control
					FROM
						estado_control
					WHERE
						estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerBarrios($offset=null, $registros_por_pagina=null) {

		global $conexion;

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
					LIMIT
					$offset, $registros_por_pagina";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerFormatoDeportes() {

		global $conexion;

		$sql = "SELECT 	
						id_formato_deporte,
						descripcion_formato_deporte,
						descripcion_deporte
					FROM
						formato_deporte
					JOIN
						deporte
					ON
						rela_deporte = id_deporte
					WHERE
						formato_deporte.estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerLocalidades() {

		global $conexion;

		$sql = "SELECT 	
						id_localidad,
						descripcion_localidad,
						descripcion_provincia
					FROM
						localidad
					JOIN
						provincia
					ON
						rela_provincia = id_provincia
					WHERE
						localidad.estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerProvincias() {

		global $conexion;

		$sql = "SELECT 	
						id_provincia,
						descripcion_provincia
					FROM
						provincia
					WHERE
						estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerServicios() {

		global $conexion;

		$sql = "SELECT 	
						id_servicio,
						descripcion_servicio
					FROM
						servicio
					WHERE
						estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerSexos() {

		global $conexion;

		$sql = "SELECT 	
						id_sexo,
						descripcion_sexo
					FROM
						sexo
					WHERE
						estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerTipoContactos() {

		global $conexion;

		$sql = "SELECT 	
						id_tipo_contacto,
						descripcion_tipo_contacto
					FROM
						tipo_contacto
					WHERE
						estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerTipoDocumentos() {

		global $conexion;

		$sql = "SELECT 	
						id_tipo_documento,
						descripcion_tipo_documento
					FROM
						tipo_documento
					WHERE
						estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerDeportes() {

		global $conexion;

		$sql = "SELECT 	
						id_deporte,
						descripcion_deporte
					FROM
						deporte
					WHERE
						estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerTipoTerrenos() {

		global $conexion;

		$sql = "SELECT 	
						id_tipo_terreno,
						descripcion_tipo_terreno
					FROM
						tipo_terreno
					WHERE
						estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerPersonas() {
		global $conexion;

		$sql = "SELECT 	nombre,
						apellido,
						dni,	
						fecha_nacimiento
					FROM
						persona
					WHERE
						estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}
	}

	function obtenerComplejos() {
		global $conexion;
		
		$sql = "SELECT 	descripcion_complejo,
						direccion						
					FROM
						complejo
					WHERE
						estado IN(1)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}
	}

	function obtenerZonasFutbol($id_sucursal) {
		global $conexion;

		$sql = "SELECT 	
						id_zona,
						descripcion_zona,
						descripcion_tipo_terreno,
						descripcion_formato_deporte,
						descripcion_sucursal						
					FROM
						zona
					JOIN
						servicio
					ON
						zona.rela_servicio = servicio.id_servicio
                    JOIN 
                    	sucursal
					ON 
						zona.rela_sucursal = sucursal.id_sucursal
					JOIN 
						formato_deporte
					ON 
						zona.rela_formato_deporte = formato_deporte.id_formato_deporte
					JOIN
						tipo_terreno
					ON
						zona.rela_tipo_terreno = tipo_terreno.id_tipo_terreno
					WHERE
						descripcion_servicio LIKE 'cancha'
					AND
						id_sucursal = $id_sucursal
					AND
						zona.estado IN(1)
					ORDER BY (zona.id_zona)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}
	}

	function obtenerInsumos() {
		global $conexion;

		$sql = "SELECT 	
						insumo.id_insumo,
						insumo.descripcion_insumo,
						insumo.cantidad,
						insumo.fecha_alta,
						insumo.categoria,
						estado_insumo.descripcion_estado_insumo					
					FROM
						insumo
					JOIN
						estado_insumo
					ON
						insumo.rela_estado_insumo = estado_insumo.id_estado_insumo
					WHERE
						insumo.estado IN(1)
					ORDER BY (id_insumo)";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerEmpleados($id_sucursal = '') {
		global $conexion;


		$orden = " ORDER BY (empleado.id_empleado)";


		$sql = "SELECT 	
						empleado.id_empleado,
						persona.nombre,
						persona.apellido,
						documento.descripcion_documento,
						persona.fecha_nacimiento,
						empleado.empleado_cargo,
						empleado.fecha_alta,
						sucursal.descripcion_sucursal
					FROM
						empleado
					JOIN
						persona
					ON
						empleado.rela_persona = persona.id_persona
					JOIN
						sucursal
					ON
						empleado.rela_sucursal = sucursal.id_sucursal
					JOIN 
						documento
					ON
						documento.rela_persona = persona.id_persona
					WHERE
						empleado.estado IN(1)";

		if ($id_sucursal) {
			$sql .= " AND empleado.rela_sucursal = $id_sucursal";
		}

		$sql .= " $orden";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}

	function obtenerMembresias($registros_por_pagina=null, $offset=null) {
		global $conexion;

		$sql = "SELECT 	
						id_membresia,
						beneficio_membresia,
						descripcion_membresia,
						precio_membresia
					FROM
						membresia
					WHERE
						estado IN(1)
					LIMIT
						$registros_por_pagina
					OFFSET 
						$offset";

		if ($registros = $conexion->query($sql)) {
			return $registros;
		} else {
			return false;
		}

	}
 ?>