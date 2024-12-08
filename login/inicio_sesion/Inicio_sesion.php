<?php require_once("../../config/root_path.php"); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <!-- <link rel="stylesheet" href="../css/style.css"> -->
    <link rel="icon" type="image/x-icon" href="icons/pestaña.jpg">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div id="image">

         <!--  -->
            <?php if(isset($_GET["error"])) { ?>
                <?php if($_GET['error'] == 1) { ?>
                    <div id="disabled-message">
                        <p>Cuenta inexistente o deshabilitada.</p>
                    </div>

                <?php } else {?>
                    <div id="disabled-message">
                        <p>Credenciales Incorrectas.</p>
                    </div>
            <?php } }?>

        <form class="login-form" action="../verificar_login/verificar_login.php" method="POST">

            <h1>Iniciar sesión</h1>

            <div class="form-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" id="username" name="username" >
                <span>Error Correo</span>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="text" id="password" name="password" >
                <span>Contraseña incorrecta</span>
            </div>

            <div style="text-align: center;">
                <span id="mensaje-error"></span>    
            </div>

            <div class="buttons">
                
                <button type="submit">Ingresar</button>

                <a href="../registrarse/registro_usuario.php" class="create-user">Crear Usuario</a>
            
                <a href="../recuperar_contrasena/formulario_recuperacion.php" class="forgot-password">¿Olvidaste tu contraseña?</a>

            </div>

           

        </form>


    </div>

<script src="<?php echo BASE_URL; ?>libs/jquery-3.7.1.min.js"></script>
<script src="<?php echo BASE_URL; ?>libs/sweetalert2.all.min.js"></script>
<script src="js/validaciones.js"></script>
<script>
    $(document).ready(function() {
        //Mensajes de errores php
            var spanerror = $('#mensaje-error');
            spanerror.hide();

            //modal para volver a enviar la verificacion por correo
            <?php 
                if (isset($_GET['verificacion_expirada'])) : 
                    $email = $_GET['email'];
                    $username = $_GET['username'];
            ?>
                Swal.fire({
                    title: 'No verifico su correo',
                    text: '¿Desea volver a enviar la verificacion?',
                    icon: 'question',
                    animation: false,
                    iconColor: '#ffe05f',
                    showCancelButton: true,
                    confirmButtonText: 'Enviar Verificacion',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#ff6448',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si confirma, redirigir a verify
                        window.location.href = '../verificacion_correo/register.php?<?php echo "email={$email}&username={$username}&reenviar_verificacion"; ?>';
                    }
                });
            <?php endif; ?>


            //hacemos visible el mensaje 
            <?php if (isset($_GET['correo_enviado'])) { ?>
                    spanerror.css({
                        'display'           : 'inline-block',
                        'background-color'  : '#6EFF6B',
                        'margin'            : '10px',
                        'color'             : 'white',
                        'padding'           : '10px'
                });
                spanerror.html('Verifique su Email');
            <?php } else if (isset($_GET['correo_verificado'])) {?>
                spanerror.css({
                        'display'           : 'inline-block',
                        'background-color'  : '#6EFF6B',
                        'margin'            : '10px',
                        'color'             : 'white',
                        'padding'           : '10px'
                });
                spanerror.html('Email Verificado');
            <?php } ?>


       
        
    });

</script>
</body>
</html>

