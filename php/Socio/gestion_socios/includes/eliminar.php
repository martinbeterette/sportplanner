<?php  
require_once("../../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");
// Función para realizar la baja lógica
function bajaLogicaSocio($conexion, $id_socio) {
    $sql = "UPDATE socio SET estado = 0 WHERE id_socio = ?";
    
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("i", $id_socio);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    } else {
        return false;
    }
}

// Lógica principal
if (isset($_GET['id_socio'])) {
    $id_socio = (int) $_GET['id_socio']; // Convertimos el ID a entero para evitar problemas de seguridad.
    $id_complejo = (int) $_GET['id_complejo']?? die("falta get de complejo");
    // Llamada a la función para la baja lógica
    if (bajaLogicaSocio($conexion, $id_socio)) {
        header("Location: ../?id_complejo=$id_complejo");
        exit();
    } else {
        echo "Hubo un error al realizar la baja lógica.";
    }
} else {
    echo "ID de socio no especificado.";
}


?>