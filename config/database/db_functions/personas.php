<?php 
	require_once(RUTA. "config/database/conexion.php");

	function obtenerPersonas($id_persona = null) {
		global $conexion;

        if (is_null($id_persona) || $id_persona == '') {

            $sql = "SELECT
                    persona.id_persona,
                    persona.nombre,
                    persona.apellido,
                    documento.descripcion_documento,
                    sexo.descripcion_sexo
                FROM
                    persona
                JOIN 
                    documento
                ON
                    persona.rela_documento = documento.id_documento
                JOIN 
                    sexo 
                ON
                    persona.rela_sexo = sexo.id_sexo
                WHERE
                    persona.estado IN (1)";

            $stmt = $conexion->prepare($sql);
            $registros = [];

            if($stmt->execute()) {
                $registros = $stmt->get_result();
                return $registros;
            } 
            
        }

		

        if (!is_null($id_persona)) {
            if (is_numeric($id_persona)) {

                $sql = "SELECT
                    persona.id_persona,
                    persona.nombre,
                    persona.apellido,
                    documento.descripcion_documento,
                    sexo.descripcion_sexo
                FROM
                    persona
                JOIN 
                    documento
                ON
                    persona.rela_documento = documento.id_documento
                JOIN 
                    sexo 
                ON
                    persona.rela_sexo = sexo.id_sexo
                WHERE
                    id_persona = $id_persona
                AND
                    persona.estado IN (1)";

                $stmt = $conexion->prepare($sql);
                $registros = [];

                if($stmt->execute()) {
                    $registros = $stmt->get_result();
                    return $registros;
                } 

            }
        }

	}

    function ObtenerPersonaPorUsuario($id_usuario=null) {
        
        global $conexion;
        $sql = "SELECT 
                    persona.id_persona,
                    persona.nombre,
                    persona.apellido,
                    documento.descripcion_documento 
                FROM 
                    persona
                JOIN
                    documento
                ON
                    persona.id_persona = documento.rela_persona
                JOIN
                    contacto
                ON
                    persona.id_persona = contacto.rela_persona
                JOIN
                    usuarios
                ON
                    usuarios.rela_contacto = contacto.id_contacto
                WHERE 
                    id_usuario = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i",$id_usuario);
        $registros = [];

        if($stmt->execute()) {
            $registros = $stmt->get_result();
            return $registros;
        }

    }

?>