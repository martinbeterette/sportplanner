<?php  
require_once("config/root_path.php");
require_once("login/enviar_correo/enviar_correo.php");
$base_url = BASE_URL;
$ruta = RUTA;
$email="maurinasd@gmail.com";
$username="maurinasd0001";
$subject="Hola $username Este es un correo de prueba";

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
        href=\"{$base_url}index2.php\">Verificar Cuenta
    </a>
";

// enviarVerificacion($email,$username,$subject,$message);
$icon_path = "{$ruta}assets/icons/juego.png";
        $icon_data = base64_encode(file_get_contents($icon_path));
        $icon_src = "data:image/png;base64," . $icon_data;
        echo $icon_src;
?>