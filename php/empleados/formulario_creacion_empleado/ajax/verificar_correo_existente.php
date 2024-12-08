<?php
require_once("../../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");

if(isset($_POST['correo'])){
    $correo = $_POST['correo'];
    $query = "SELECT * FROM usuarios JOIN contacto WHERE descripcion_contacto = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        echo "existe"; // El correo existe
    } else {
        echo "no_existe"; // El correo no existe
    }
}
?>
