<?php
// require_once('../../config/root_path.php');
require_once (__DIR__ . '/vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



    // $token = bin2hex(random_bytes(16)); // Genera un token único
    // $expiry = date('Y-m-d H:i:s', strtotime('+30 minutes'));

    function enviarVerificacion($email, $username, $subject, $message) {
        global $base_url, $ruta, $token, $expiry;
        $icon_path = "{$ruta}assets/icons/juego.png";
        $icon_data = base64_encode(file_get_contents($icon_path));
        $icon_src = "data:image/png;base64," . $icon_data;
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cambia al servidor SMTP que uses
            $mail->SMTPAuth = true;
            $mail->Username = 'maurinprueba@gmail.com'; // Cambia al correo desde el que enviarás el mensaje
            $mail->Password = 'xzpdakudwjcsyhci'; // Cambia a la contraseña del correo
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            // Configuración del remitente y destinatario
            $mail->setFrom('maurinprueba@gmail.com', 'SportPlanner');
            $mail->addAddress($email, $username);

            // Generar estilos para el cuerpo del correo
            $styledMessage = "
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .email-container {
                        max-width: 600px;
                        margin: 20px auto;
                        background: white;
                        padding: 20px;
                        border-radius: 10px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    .header img {
                        max-height: 50px;
                    }
                    .content {
                        font-size: 16px;
                        color: #333;
                        margin-bottom: 20px;
                    }
                    .button {
                        display: inline-block;
                        padding: 10px 20px;
                        background: #28a745;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                        font-size: 16px;
                    }
                    .footer {
                        text-align: center;
                        font-size: 12px;
                        color: #666;
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='header'>
                        <img src='$icon_src' alt='Icono'>
                    </div>
                    <div class='content'>
                        $message
                    </div>
                    <div class='footer'>
                        Si no solicitaste este correo, ignóralo.
                    </div>
                </div>
            </body>
            </html>";

            // Configuración del correo
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $styledMessage;

            // Enviar el correo
            if ($mail->send()) {
                return true;
            } else {
                return "Error al enviar el correo: " . $mail->ErrorInfo;
            }
            
        } catch (Exception $e) {
            echo "error: " . $e->get_message();
        }
    }


?>
