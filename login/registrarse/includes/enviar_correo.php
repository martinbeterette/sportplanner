<?php  
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once(RUTA . "login/enviar_correo/enviar_correo.php");
$base_url = BASE_URL;
$ruta = RUTA;

if(isset($_GET['enviar_correo'])){
    // credenciales del usuario
    $email              = $_REQUEST['email'];
    $username           = $_REQUEST['username'];
    $nombre             = $_REQUEST['nombre'];
    $apellido           = $_REQUEST['apellido'];
    $documento          = $_REQUEST['documento'];
    $tipo_documento     = $_REQUEST['tipo_documento'];
    $sexo               = $_REQUEST['sexo'];
    $contrasena         = $_REQUEST['password'];
    // credenciales del usuario

    // Generar token y expiry (simulados para este caso)
    $token  = bin2hex(random_bytes(16)); // Token aleatorio
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expiración en 1 hora

    $subject="Hola $username, Gracias por registrarte en SportPlanner";

    //mensaje con el boton
    $message = "
        <p>Hola <strong>$username</strong>,</p>
        <p>Por favor, verifica tu cuenta haciendo clic en el siguiente botón:</p>
        <a class='boton-verificacion' 
            style='
            	text-decoration:none;
                cursor: pointer;
                background-color: #28a745; 
                color: white; 
                border: none; 
                padding: 10px 20px; 
                text-align: center; 
                display: inline-block; 
                font-size: 16px; 
                border-radius: 5px;
            '
            href=\"{$base_url}login/registrarse/includes/verificar_correo.php?token=$token&username=$username&email=$email\">Verificar Cuenta
        </a>
    ";

    // enviarVerificacion($email,$username,$subject,$message);
    require_once("insertar_usuario.php");

}
?>