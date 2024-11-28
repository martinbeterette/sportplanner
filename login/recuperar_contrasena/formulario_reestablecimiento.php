<?php 
    $id_usuario = $_GET['id_usuario'];        
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro usuario</title>
    <!-- <link rel="stylesheet" type="text/css" href="../../css/style.css"> -->
    <style>
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

        .login-form {
            max-width: 400px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
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

        #mensaje-error {
            display: none;
            font-size: 14px;
            margin-top: 10px;
        }

        .back-link {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>

    <form class="login-form" action="modificar_contrasena.php" method="post">

        <h1>Restablecer Contrase&ntilde;a</h1>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="password2">Confirmar Contraseña</label>
            <input type="password" id="password2" name="confirmar-contrasena">
        </div>

        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

        <center>
            <div id="mensaje-error"></div>
            <button type="submit">Ingresar</button>
            <a href="../../index_tincho.php" class="back-link">Volver al inicio</a>
        </center>


    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            var spanerror = $('#mensaje-error');
            spanerror.hide();

            $('form').on('submit', function(event) {
                var contrasenaInput = $('#password');
                var contrasenaInput2 = $('#password2');

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
                    }).html('Contraseña no válida');
                    alert('La contraseña debe tener al menos 8 caracteres y 1 número.');
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
                    }).html('Las contraseñas no coinciden');
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
                    }).html('Rellene las contraseñas');
                } else {
                    contrasenaInput2.css('border', '2px solid green');
                }
            });
        });
    </script>
</body>
</html>













