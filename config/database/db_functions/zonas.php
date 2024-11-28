<?php 
	require_once(RUTA."config/database/conexion.php");

	function obtenerZonas() {

		global $conexion;

		$sql = "SELECT
                    id_zona,
                    id_sucursal,
                    descripcion_zona,
                    descripcion_sucursal,
                    descripcion_complejo
                FROM
                    zona
                JOIN 
                    sucursal
                ON
                    zona.rela_sucursal = sucursal.id_sucursal
                JOIN 
                	complejo 
                ON
                	sucursal.rela_complejo = complejo.id_complejo
                JOIN
                    servicio
                ON
                    zona.rela_servicio = servicio.id_servicio
                WHERE
                    servicio.descripcion_servicio LIKE 'cancha'
                AND
                    zona.estado IN (1)";

        $stmt = $conexion->prepare($sql);
        $registros = [];

        if($stmt->execute()) {
        	$registros = $stmt->get_result();
        	return $registros;
        } 

    }

    function ObtenerHorariosDisponibles($id_zona=null, $fecha=null) {
    	global $conexion;

    	$sql = "SELECT 
            		horario.id_horario,
            		horario.horario_inicio,
		            horario.horario_fin,
		            reserva.id_reserva,
		            zona.descripcion_zona,
		            sucursal.descripcion_sucursal,
		            complejo.descripcion_complejo,
		            IF (reserva.id_reserva IS NULL, 'disponible', 'no-disponible') AS estado
        		FROM 
	                zona
	            JOIN 
	            	sucursal
	            ON
	            	zona.rela_sucursal = sucursal.id_sucursal
	            JOIN
	                complejo
	            ON
	                sucursal.rela_complejo = complejo.id_complejo
	            AND
	                id_zona = ?
	            JOIN 
	                reserva 
	            ON 
	                reserva.rela_zona = zona.id_zona
	            RIGHT JOIN 
	                horario
	            ON
	                horario.id_horario = reserva.rela_horario
	            AND
	                reserva.fecha_reserva = ?
	        ORDER BY (horario.horario_inicio)";

	        $stmt = $conexion->prepare($sql);
	        $stmt->bind_param("is", $id_zona, $fecha);
	        $registros = [];

	        if($stmt->execute()) {
	        	$registros = $stmt->get_result();
	        	return $registros;
	        } 
    }

    function ObtenerHorario($id_horario=null){

    	global $conexion;
    	$sql = "SELECT 
    				horario_inicio,
    				horario_fin 
    			FROM 
    				horario 
    			WHERE 
    				id_horario = ?";

    	$stmt = $conexion->prepare($sql);
    	$stmt->bind_param("i",$id_horario);
    	$registros = [];

    	if($stmt->execute()) {
    		$registros = $stmt->get_result();
    		return $registros;
    	}

    }

    

    function insertarReserva($rela_horario,$fecha,$rela_zona,$rela_persona) {
    	global $conexion;

    	$sqlInsert = "INSERT INTO 
					reserva(
						rela_estado_reserva,
						fecha_reserva,
						fecha_alta,
						rela_persona,
						rela_zona,
						rela_horario
					) 
				VALUES(
					6,
					?,
					CURRENT_DATE(),
					?,
					?,
					?);";
		$stmt = $conexion->prepare($sqlInsert);
		$stmt->bind_param("siii", $fecha, $rela_persona, $rela_zona, $rela_horario);

		if($stmt->execute()) {
			return true;
		} else{
			return false;
		}
    }

    function insertarReservaCanchero($rela_horario,$fecha,$rela_zona,$rela_persona) {
    	global $conexion;

    	$sqlInsert = "INSERT INTO 
					reserva(
						rela_estado_reserva,
						fecha_reserva,
						fecha_alta,
						rela_persona,
						rela_zona,
						rela_horario
					) 
				VALUES(
					1,
					?,
					CURRENT_DATE(),
					?,
					?,
					?);";
		$stmt_canchero = $conexion->prepare($sqlInsert);
		$stmt_canchero->bind_param("siii", $fecha, $rela_persona, $rela_zona, $rela_horario);

		if($stmt_canchero->execute()) {
			return true;
		} else{
			return false;
		}
    }

    //esta funcion seria para darle acceso si tiene complejo a su nombre
    function obtenerAcessoGestionCanchas($id_persona) {
        global $conexion;

        $sql = "SELECT 
                    zona.id_zona,
                    zona.descripcion_zona,
                    persona.nombre,
                    persona.apellido
                    FROM asignacion_persona_complejo apc
                    JOIN complejo ON apc.rela_complejo = complejo.id_complejo
                    JOIN sucursal ON sucursal.rela_complejo = complejo.id_complejo
                    JOIN zona ON zona.rela_sucursal = sucursal.id_sucursal
                    JOIN persona ON persona.id_persona = apc.rela_persona
                    WHERE apc.rela_persona = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_persona);
        $registros = [];

        if($stmt->execute()) {
            $registros = $stmt->get_result();
            return $registros;
        } 
    }
?>