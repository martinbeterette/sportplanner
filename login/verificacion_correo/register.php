<?php
require_once('../../config/root_path.php');
require_once(RUTA . 'config/database/conexion.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$base_url = BASE_URL;
try {

    $token = bin2hex(random_bytes(16)); // Genera un token único
    $expiry = date('Y-m-d H:i:s', strtotime('+30 minutes'));

    function enviarVerificacion($email, $username, $token) {
         // Enviar correo de verificación
        $verification_link = htmlSpecialchars(BASE_URL . "login/verificacion_correo/verify.php?email=" . $email . "&token=" . $token . "&username=" . $username);

        
        $subject = 'Verificación de Correo Electrónico';
        $message = "Hola $username, haz clic en el siguiente enlace para verificar tu correo electrónico: $verification_link";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Cambia al servidor SMTP que uses
        $mail->SMTPAuth = true;
        $mail->Username = 'maurinprueba@gmail.com'; // Cambia al correo desde el que enviarás el mensaje
        $mail->Password = 'xzpdakudwjcsyhci'; // Cambia a la contraseña del correo
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('maurinprueba@gmail.com', 'SportPlanner');
        $mail->addAddress($email, $username);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        // if($mail->send()){
        //     return $verification_link;
        // }

        if ($mail->send()) {
            header("Location: ".BASE_URL."login/inicio_sesion/inicio_sesion.php?correo_enviado");
            exit();
        } else {
            echo "ha ocurrido un error al enviar el correo". $mail->errorInfo;
            exit();
        }
    }

    if (isset($_GET['registro_usuario'])) {

        try {

            /*
                ACA IRIA LA VALIDACION ANTES DE EMPEZAR LA TRANSACCION
            */

            $pdo->beginTransaction();

            $nombre         = $_REQUEST['nombre'];
            $apellido       = $_REQUEST['apellido'];
            $documento      = $_REQUEST['documento'];
            $tipo_documento = $_REQUEST['tipo_documento'];
            $sexo           = $_REQUEST['sexo'];
            $username       = $_REQUEST['username'];
            $email          = $_REQUEST['email'];
            $password       = $_REQUEST['password'];
            $contrasena_hasheada = password_hash($password, PASSWORD_DEFAULT);

            //inicializamos la transaccion
            $existe_usuario = $conexion->query("SELECT * FROM usuarios WHERE username LIKE '$username'");
            if($existe_usuario->num_rows > 0){
                header("Location: ../registrarse/registro_usuario.php?username_repetido");
            }
            //Insertar en persona
            $stmt = $pdo->prepare("INSERT INTO persona (nombre, apellido, rela_sexo, fecha_alta) VALUES (?, ?, ?, CURRENT_DATE())");
            $stmt->execute([$nombre, $apellido, $sexo]);
            $id_persona = $pdo->lastInsertId();

            //Insertar documento
            $stmt = $pdo->prepare("INSERT INTO documento (descripcion_documento,rela_tipo_documento,rela_persona) VALUES ( ?, ?, ?)");
            $stmt->execute([$documento, $tipo_documento, $id_persona]);



            //Insertar en contacto
            $stmt = $pdo->prepare("INSERT INTO contacto (descripcion_contacto,rela_persona,rela_tipo_contacto) VALUES (?, ?, ?)");
            $stmt->execute([$email, $id_persona, 1]);
            $id_contacto = $pdo->lastInsertId();

            // Insertar el usuario
            $stmt = $pdo->prepare("INSERT INTO usuarios (username, password,token,expiry,rela_contacto, rela_perfil) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $contrasena_hasheada,$token, $expiry,$id_contacto, 1]);

            enviarVerificacion($email, $username, $token);

            $pdo->commit();
        } catch (exception $e) {
            $pdo->rollBack();
            echo "Error en la transacción: " . $e->getMessage();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        if (isset($_GET['verificar_propietario'])) {

            // hay que meter todo esto en una funcion PORQUE SE VA A USAR 3 VECES
            $email = $_GET['email'];
            $username = $_GET['username']; //esto solo para enviarlo en el mail
            $sql = "UPDATE usuarios u
                    JOIN contacto c
                    SET u.token = ?, u.expiry = ?
                    WHERE u.username = ?";
            $stmt = $pdo->prepare($sql);
            if($stmt->execute([$token, $expiry, $username])) {
                enviarVerificacion($email, $username, $token);
            } else {
                die("Ocurrio un error durante la creacion de credenciales");
            }
        }

        if(isset($_GET['reenviar_verificacion'])) {
            $email = $_GET['email'];
            $username = $_GET['username']; //esto solo para enviarlo en el mail
            $sql = "UPDATE usuarios u
                    JOIN contacto c
                    SET u.token = ?, u.expiry = ?
                    WHERE u.username = ?";

            $stmt = $pdo->prepare($sql);
            if($stmt->execute([$token, $expiry, $username])) {
                enviarVerificacion($email, $username, $token);
            } else {
                die("Ocurrio un error durante la creacion de credenciales");
            }
        }

        if (isset($_GET['verificar_socio'])) {}


    }

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
} catch (Exception $e) {
    echo "ha ocurrido un error :(";
}
?>
