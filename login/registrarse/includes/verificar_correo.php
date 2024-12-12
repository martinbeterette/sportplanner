<?php
require_once('../../../config/root_path.php');
require_once(RUTA . 'config/database/conexion.php');

try {


    if (isset($_GET['email']) && isset($_GET['token'])) {
        $email = $_GET['email'];
        $username = $_GET['username'];
        $token = $_GET['token'];

        // Verificar el token en la base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuarios u JOIN contacto c ON u.rela_contacto = c.id_contacto WHERE u.username = ? AND u.token = ? AND expiry > NOW()");
        $stmt->execute([$username, $token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Actualizar estado del usuario a 'verificado'
            $stmt = $pdo->prepare("UPDATE usuarios u
                                    JOIN contacto c ON u.rela_contacto = c.id_contacto
                                    SET u.estado = 'verificado',
                                    u.token = NULL, u.expiry = NULL
                                    WHERE u.username = ?");
            $stmt->execute([$username]);

            header("Location: ".BASE_URL."login/inicio_sesion/inicio_sesion.php?correo_verificado");
            exit();
        } else {
            $stmt = $pdo->prepare("SELECT username FROM usuarios u
                                    JOIN contacto c ON u.rela_contacto = c.id_contacto
                                    WHERE u.username = ?");
            $stmt->execute([$username]);
            $reg = $stmt->fetch(PDO::FETCH_ASSOC);

            if($reg) {
                $username = $reg['username'];
                header("Location: ". BASE_URL ."login/inicio_sesion/inicio_sesion.php?verificacion_expirada&email={$email}&username={$username}");
            }
        }
    } else {
        echo 'Parámetros insuficientes para la verificación.';
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
