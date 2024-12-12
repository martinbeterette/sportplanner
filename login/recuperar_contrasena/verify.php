<?php
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require 'vendor/autoload.php';


try {

    if (isset($_GET['email']) && isset($_GET['token'])) {
        $email = $_GET['email'];
        $token = $_GET['token'];

        // Verificar el token en la base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuarios u JOIN contacto c ON u.rela_contacto = c.id_contacto WHERE c.descripcion_contacto = ? AND u.token = ? AND expiry > NOW()");
        $stmt->execute([$email, $token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $id_usuario = $usuario['id_usuario'];
            
            header("Location: formulario_reestablecimiento.php?id_usuario=$id_usuario");
            
        } else {
            echo 'Token inválido, expirado o correo electrónico no encontrado. <br> <a href="'. BASE_URL .'login/inicio_sesion/inicio_sesion.php">Volver al login</a>';
        }
    } else {
        echo 'Parámetros insuficientes para la verificación.';
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
