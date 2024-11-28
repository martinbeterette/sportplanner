<?php 
require_once(RUTA . "config/database/conexion.php");


function validarAcceso($modulo=null,$perfil=null) {
	global $_SESSION, $conexion;
    $no_tiene_sesion_url = BASE_URL . "errors/error403.php?no_tiene_sesion";
    $no_tiene_acceso_url = BASE_URL . "errors/error403.php?no_tiene_acceso";

	if($modulo == null || $perfil == null ) {
        header("Location: {$no_tiene_sesion_url}");
        exit();
	} elseif ($modulo == "" || $perfil == "") {
        header("Location: {$no_tiene_sesion_url}");       
        exit();
	}

    $sql_acceso = "SELECT COUNT(*) AS tiene_acceso
                    FROM 
                        asignacion_perfil_modulo asp
                    JOIN 
                        perfil p 
                    ON 
                        asp.rela_perfil = p.id_perfil
                    JOIN 
                        modulo m ON asp.rela_modulo = m.id_modulo
                    WHERE 
                        p.descripcion_perfil 
                    LIKE 
                        '{$perfil}' 
                    AND 
                        m.descripcion_modulo 
                    LIKE 
                        '{$modulo}'";

    $resultado = $conexion->query($sql_acceso);

    if ($reg = $resultado->fetch_assoc()) {
        if ($reg['tiene_acceso'] == 0) {
            header("Location: {$no_tiene_acceso_url}");
            exit();
        } else{
        	return true;
        }

    }



}

?>