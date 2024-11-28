<?php
require_once('../../config/database/conexion.php');
session_start();
$user       = $_POST['usuario'];
$password   = $_POST['contrasena_actual'];
$new_password = $_POST['contrasena_nueva'];



//echo $user;
//echo $name;
if(isset($_SESSION['usuario'])) {
    $sql="SELECT * FROM usuario WHERE id_usuario = '{$_SESSION['id_usuario']}'";
} else {
    echo "no se encontro un usuario...";
    die;
}

$datos = $conexion->query($sql);

// el compañero usa mysql->query 
if($datos->num_rows == 1) {

    $row = $datos->fetch_assoc();

    if(password_verify($password, $row['contrasena'])) {

        $conexion->close();
      
         echo "
        <form id='formulario-automatico' method='POST' action='aplicar_modificar_mis_datos.php'>
            <input type='hidden' name='usuario' value='" . htmlspecialchars($user, ENT_QUOTES, 'UTF-8') . "'>
            <input type='hidden' name='contrasena_nueva' value='" . htmlspecialchars($new_password, ENT_QUOTES, 'UTF-8') . "'>
        </form> 
        
        <script type='text/javascript'>
            document.getElementById('formulario-automatico').submit();
        </script>";


    } else {
//        echo"no existe";
        $conexion->close();
        header('location: modificar_mis_datos.php?error=2');
        exit();
    }

} else {
//    echo"usuario inexistente";
    header('location: modificar_mis_datos.php?error=1');
    
}
?>