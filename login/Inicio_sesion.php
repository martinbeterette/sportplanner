<?php require_once("../config/root_path.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <!-- <link rel="stylesheet" href="../css/style.css"> -->
    <link rel="icon" type="image/x-icon" href="icons/pestaña.jpg">
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 2px 20px rgba(0, 0, 0, 0.5);
            margin: 0px auto;
            margin-top: 15vh;
            margin-bottom: 20px;
            padding: 20px;
            width: 400px;
            padding-right: 30px;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-form {
            max-width: 400px;
            margin: auto;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group span {
            display: none;
            font-size: 12px;
            margin-top: 5px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }

        .buttons {
            width: 100%;
            text-align: center;
        }
        .create-user {
            display: inline-block;
            margin-top: 10px;
            margin-bottom: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .create-user:hover {
            background-color: #0056b3;
        }

        .forgot-password {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: #0056b3;
        }

        #mensaje-error {
            display: none;
            font-size: 14px;
            margin-top: 10px;
        }

        #disabled-message {
            display: block;
            text-align: center;
            color: white;
            background-color: #FF4500;
            font-size: 20px;
            width: 100vw;
            padding: 10px;
            border-radius: 5px;
            opacity: 0;
            filter: blur(10px);
            animation: fadeInBlur .5s ease forwards;
            position: fixed;
            bottom: 0;
            left: 0;
        }
        #disabled-message p {
            margin:0;
        }

        /* Animación para el fade-in y blur */
        @keyframes fadeInBlur {
            0% {
                opacity: 0;
                filter: blur(10px);
            }
            100% {
                opacity: 1;
                filter: blur(0);
            }
        }


    </style>
</head>
<body>

    <div id="image">
        
                <?php if(isset($_GET["error"])) { ?>
                    <?php if($_GET['error'] == 1) { ?>
                    <div id="disabled-message">
                        <p>Tu cuenta ha sido deshabilitada temporalmente.</p>
                    </div>

                <?php } else {?>
                    <div id="disabled-message">
                        <p>Credenciales Incorrectas.</p>
                    </div>
                <?php } }?>

        <form class="login-form" action="verificar_login.php" method="POST">

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

                <a href="registro_usuario.php" class="create-user">Crear Usuario</a>
            
                <a href="recuperar_contrasena/formulario_recuperacion.php" class="forgot-password">¿Olvidaste tu contraseña?</a>

            </div>

           

        </form>


    </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?php echo BASE_URL; ?>libs/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
        //inicializamos el mensaje de error oculto
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
                    window.location.href = 'verificacion_correo/register.php?<?php echo "email={$email}&username={$username}&reenviar_verificacion"; ?>';
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


        //inicializamos los span para ocultarlos
        // var spanemail       = $('span:eq(0)');
        var spancontrasena  = $('span:eq(1)');

        spanemail     .hide();
        spancontrasena  .hide();

        $('form').on('submit', function(event) {
          //obtenemos variables para operar la validacion
            var emailInput    = $('#email'); 
            var contrasenaInput = $('#password');

            //ocultamos por defecto los span PREVIO a su validacion

            var email = emailInput.val();
            var contrasena = contrasenaInput.val();

            //expresiones regulares
            var regexemail      = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            var regexcontrasena = /^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;

            //testeamos el email
            if (!regexemail.test(email)) {
                //si da incorrecto
                event.preventDefault();
                emailInput.css('border', '2px solid #FF4500');
                spanemail.css({
                    'display'           : 'inline-block',
                    'background-color'  : '#FF4500',
                    'margin'            : '10px',
                    'color'             : 'white',
                    'padding'           : '10px'
                });
                spanemail.html('email Invalido.');
                alert('Almenos 5 caracteres. No utilice caracteres especiales');

            } else {
                //si da correcto
                emailInput.css('border', '2px solid green');
                spanemail.css({
                    'display'           : 'inline-block',
                    'background-color'  : 'green',
                    'margin'            : '10px',
                    'color'             : 'white',
                    'padding'           : '10px'
                });
                spanemail.html('Ok.');
            }

            //testeamos la contraseña
            if (!regexcontrasena.test(contrasena)) {
                //si da incorrecto
                event.preventDefault();
                contrasenaInput.css('border', '2px solid #FF4500');
                spancontrasena.css({
                    'display'           : 'inline-block',
                    'background-color'  : '#FF4500',
                    'margin'            : '10px',
                    'color'             : 'white',
                    'padding'           : '10px'
                });
                spancontrasena.html('contrasena Invalida.');
                alert('Almenos 8 caracteres y 1 número.');
            } else {
                //si da correcto
                contrasenaInput.css('border', '2px solid green');
                spancontrasena.css({
                    'display'           : 'inline-block',
                    'background-color'  : 'green',
                    'margin'            : '10px',
                    'color'             : 'white',
                    'padding'           : '10px'
                });
                spancontrasena.html('Ok.');
            }

        });
        
    });

</script>
</body>
</html>

