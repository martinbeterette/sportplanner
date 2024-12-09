<?php
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //asignacion variables necesarias
        $username      = $_POST['username'];
        $token = bin2hex(random_bytes(16)); // Genera un token único
        $expiry = date('Y-m-d H:i:s', strtotime('+30 seconds'));

        // buscar coincidencia con correo
        $stmt = $pdo->prepare("SELECT id_usuario, username, descripcion_contacto FROM usuarios JOIN contacto ON usuarios.rela_contacto = contacto.id_contacto WHERE username LIKE ?");
        $stmt->execute([$username]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario){
            $id_usuario = $usuario['id_usuario'];
            $username = $usuario['username'];
            $email      = $usuario['descripcion_contacto'];
            //actualizamos el token y el expiro del token del usuario
            $stmt = $pdo->prepare("UPDATE usuarios SET token = ?, expiry = NOW() + INTERVAL 5 minute WHERE id_usuario = ?");
            $stmt->execute([$token, $id_usuario]);

            // Enviar correo de verificación
            $verification_link = BASE_URL . "login/recuperar_contrasena/verify.php?email=$email&token=$token";
            $subject = 'Verificacion de correo electronico';
            $message = "<h2>Hola $username, haz clic en el siguiente enlace para verificar tu correo electr&oacute;nico: $verification_link"."</h2>";

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cambia al servidor SMTP que uses
            $mail->SMTPAuth = true;
            $mail->Username = 'maurinprueba@gmail.com'; // Cambia al correo desde el que enviarás el mensaje
            $mail->Password = 'xzpdakudwjcsyhci'; // Cambia a la contraseña del correo
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('maurinprueba@gmail.com', 'BestSports');
            $mail->addAddress($email, $username);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            // header("Location: ../inicio_sesion.php?correo_enviado");
            echo "TODO CORRECTO!";

        } else {
            echo "no se encontro usuario con ese correo <br> <a href='../../index2.php'>Volver al login</a>";
        }

        
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Error de correo: {$mail->ErrorInfo}";
}
?>
