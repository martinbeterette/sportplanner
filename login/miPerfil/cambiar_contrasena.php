<?php
require_once("../../config/root_path.php");
require_once("../../config/database/conexion.php");
session_start();
$id_usuario = $_SESSION['id_usuario'];
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro usuario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/aside.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="cambiarContrasena.css">
</head>

<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php") ?>

    <form class="login-form" action="cambiar_contrasena_aplicar.php" method="POST">

        <h1>Cambiar contrase&ntilde;a</h1>

        <div class="form-group">
            <label for="password">Contraseña Actual</label>
            <input type="password" id="contrasena_actual" name="contrasena_actual">
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="password">Confirmar Contraseña</label>
            <input type="password" id="password2" name="confirmar-contrasena">
        </div>
        <!-- ENVIAMOS EL USUARIO TAMBIEN EN EL FORMULARIO -->
        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

        <div style="text-align: center;">
            <span id="mensaje-error"></span>
        </div>

        <button type="submit">Confirmar</button>

    </form>

    <?php include(RUTA . "includes/footer.php") ?>

    <script src="../../libs/jquery-3.7.1.min.js"></script>
    <script src="../../libs/sweetalert2.all.min.js"></script>
    <script src="../../js/aside.js"></script>
    <script src="../../js/header.js"></script>
    <script src="../../js/terminoscondiciones.js"></script>

    <script>
        $(document).ready(function() {

            //inicializamos los pan para ocultarlos

            var spanerror = $('#mensaje-error');
            spanerror.hide();

            $('form').on('submit', function(event) {
                //obtenemos variables para operar la validacion

                var contrasenaInput = $('#password');
                var contrasenaInput2 = $('#password2');

                //ocultamos por defecto los span PREVIO a su validacion
                var contrasena = contrasenaInput.val();
                var contrasena2 = contrasenaInput2.val();

                var regexcontrasena = /^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;


                if (!regexcontrasena.test(contrasena)) {

                    event.preventDefault();
                    contrasenaInput.css('border', '2px solid #FF4500');
                    spanerror.css({
                        'display': 'inline-block',
                        'background-color': '#FF4500',
                        'margin': '10px',
                        'color': 'white',
                        'padding': '10px'
                    });

                    spanerror.html('Contraseña no Valida');
                    alert('La contraseña debe tener almenos 8 caracteres y 1 número.');

                } else {

                    contrasenaInput.css('border', '2px solid green');

                }

                if (contrasena !== contrasena2) {

                    event.preventDefault();
                    contrasenaInput.css('border', '2px solid #FF4500');
                    contrasenaInput2.css('border', '2px solid #FF4500');
                    spanerror.css({
                        'display': 'inline-block',
                        'background-color': '#FF4500',
                        'margin': '10px',
                        'color': 'white',
                        'padding': '10px'
                    });

                    spanerror.html('Las contraseñas no coinciden');

                } else if (contrasena === "" || contrasena2 === "") {

                    event.preventDefault();
                    contrasenaInput.css('border', '2px solid #FF4500');
                    contrasenaInput2.css('border', '2px solid #FF4500');

                    spanerror.css({
                        'display': 'inline-block',
                        'background-color': '#FF4500',
                        'margin': '10px',
                        'color': 'white',
                        'padding': '10px'
                    });

                    spanerror.html('Rellene las contraseñas');

                } else {

                    contrasenaInput2.css('border', '2px solid green');

                }

            });
        });
    </script>
</body>

</html>