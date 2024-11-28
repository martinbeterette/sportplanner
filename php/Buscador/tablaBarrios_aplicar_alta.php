<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_perfil'])) {
        header("Location: ../../../error403.php");
        exit();
    }

    $modulo = "Domicilios";

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
                        '{$_SESSION['perfil']}' 
                    AND 
                        m.descripcion_modulo 
                    LIKE 
                        '{$modulo}'";

    $resultado = $conexion->query($sql_acceso);

    if ($reg = $resultado->fetch_assoc()) {
        if ($reg['tiene_acceso'] == 0) {
            header("Location: ../../../error403.php");
            exit();
        }
    }
    
$descripcion 		= $_POST['descripcion'];
$localidad 			= $_POST['localidad'];

$sql = "INSERT INTO 
					barrio(descripcion_barrio, rela_localidad)
		VALUES
			('$descripcion', $localidad)";

if ($conexion->query($sql)) {
	header("Location: tablabarrios.php");
}

?>