<?php 
    require_once('../config/root_path.php');
    require_once('../config/database/db_functions.php'); 
    $sexos = obtenerSexos();
    require_once(RUTA . 'config/database/conexion.php');
    $documentos = $conexion->query("SELECT * FROM tipo_documento");
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registro usuario</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>






    <form class="login-form" action="verificacion_correo/register.php" method="POST">

        <h1>Registrar Usuario</h1>

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" >
        </div>

        <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" >
        </div>

        <div class="form-group">
            <label for="documento">Documento</label>
            <input type="text" id="documento" name="documento" >
        </div>

        <div class="form-group">
            <label for="documento">Tipo de documento</label>
            <select name="tipo_documento" id="">
                <?php foreach($documentos as $reg) { ?>
                    <option value="<?php echo $reg['id_tipo_documento'] ?>"><?php echo $reg['descripcion_tipo_documento'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="sexo">Sexo</label>
            <select name="sexo">
                <option disabled selected value="">Seleccione un sexo</option>
                <?php foreach ($sexos as $reg) { ?>

                    <option value="<?php echo $reg['id_sexo'];?>"><?php echo $reg['descripcion_sexo']; ?></option>

                <?php }?>
            </select>
        </div>

         <div class="form-group">
            <label for="email">Correo</label>
            <input type="text" id="email" name="email" >
        </div>

        <div class="form-group">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" >
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" >
        </div>

        <div class="form-group">
            <label for="password">Confirmar Contraseña</label>
            <input type="password" id="password2" name="confirmar-contrasena" >
        </div>		

        <div style="text-align: center;">
            <span id="mensaje-error"></span>	
        </div>

        <button type="submit">Ingresar</button>

    </form>

<script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
<script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js" ?>"></script>
<?php if(isset($_GET['username_repetido'])) { ?>
    <script>
        Swal.fire("El username ya esta ocupado","","warning");
    </script>

<?php } ?>
<script>
$(document).ready(function() {

    //inicializamos los pan para ocultarlos

    var spanerror = $('#mensaje-error');
    spanerror.hide();

    $('form').on('submit', function(event) {
      //obtenemos variables para operar la validacion
        var nombreInput         = $('#nombre'); 
        var apellidoInput       = $('#apellido'); 
        var dniInput            = $('#dni'); 
        var emailInput          = $('#email');

        var usuarioInput        = $('#username'); 
        var contrasenaInput     = $('#password');
        var contrasenaInput2    = $('#password2');

        //ocultamos por defecto los span PREVIO a su validacion
        var nombre      = nombreInput        .val();
        var apellido    = apellidoInput      .val();
        var dni         = dniInput           .val();
        var email       = emailInput         .val();

        var usuario     = usuarioInput      .val();
        var contrasena  = contrasenaInput   .val();
        var contrasena2 = contrasenaInput2  .val();

        var regexusuario    = /^(?=.*[a-zA-Z])[a-zA-Z\d]{5,}$/;
        var regexcontrasena = /^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
        var regexEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;


        if (nombre == '') {

            event.preventDefault();
            nombreInput.css('border', '2px solid #FF4500');
            spanerror.css({
                'display'           : 'inline-block',
                'background-color'  : '#FF4500',
                'margin'            : '10px',
                'color'             : 'white',
                'padding'           : '10px'
            });
            spanerror.html('Ingrese un Nombre');

        } else {
            nombreInput.css('border', '2px solid green');
        }

        if (apellido == '') {

            event.preventDefault();
            apellidoInput.css('border', '2px solid #FF4500');
            spanerror.css({
                'display'           : 'inline-block',
                'background-color'  : '#FF4500',
                'margin'            : '10px',
                'color'             : 'white',
                'padding'           : '10px'
            });
            spanerror.html('Ingrese un Apellido');

        } else {
            apellidoInput.css('border', '2px solid green');
        }

        if (dni == '') {

            event.preventDefault();
            dniInput.css('border', '2px solid #FF4500');
            spanerror.css({
                'display'           : 'inline-block',
                'background-color'  : '#FF4500',
                'margin'            : '10px',
                'color'             : 'white',
                'padding'           : '10px'
            });
            spanerror.html('Ingrese un DNI');

        } else {
            dniInput.css('border', '2px solid green');
        }

        if (email == '') {

            event.preventDefault();
            emailInput.css('border', '2px solid #FF4500');
            spanerror.css({
                'display'           : 'inline-block',
                'background-color'  : '#FF4500',
                'margin'            : '10px',
                'color'             : 'white',
                'padding'           : '10px'
            });
            spanerror.html('Ingrese un Correo');



        } else if(!regexEmail.test(email)) {

            event.preventDefault();
            emailInput.css('border', '2px solid #FF4500');
            spanerror.css({
                'display'           : 'inline-block',
                'background-color'  : '#FF4500',
                'margin'            : '10px',
                'color'             : 'white',
                'padding'           : '10px'
            });
            spanerror.html('Correo no Valido');

        } else {

            emailInput.css('border', '2px solid green');

        }

        if (!regexusuario.test(usuario)) {

            event.preventDefault();
            usuarioInput.css('border', '2px solid #FF4500');
            spanerror.css({
                'display'           : 'inline-block',
                'background-color'  : '#FF4500',
                'margin'            : '10px',
                'color'             : 'white',
                'padding'           : '10px'
            });

            spanerror.html('Usuario Invalido');
            alert('El nombre de usuario debe tener almenos 5 caracteres. No utilice caracteres especiales');

        } else {

            usuarioInput.css('border', '2px solid green');

        }

        if (!regexcontrasena.test(contrasena)) {

            event.preventDefault();
            contrasenaInput.css('border', '2px solid #FF4500');
            spanerror.css({
                'display'           : 'inline-block',
                'background-color'  : '#FF4500',
                'margin'            : '10px',
                'color'             : 'white',
                'padding'           : '10px'
            });

            spanerror.html('Contraseña no Valida');
            alert('La contraseña debe tener almenos 8 caracteres y 1 número.');

        } else {

            contrasenaInput.css('border', '2px solid green');
            
        }

        if(contrasena !== contrasena2) {

            event.preventDefault();
            contrasenaInput.css('border', '2px solid #FF4500');
            contrasenaInput2.css('border', '2px solid #FF4500');
            spanerror.css({
                'display'           : 'inline-block',
                'background-color'  : '#FF4500',
                'margin'            : '10px',
                'color'             : 'white',
                'padding'           : '10px'
            });

            spanerror.html('Las contraseñas no coinciden');

        } else if (contrasena === "" || contrasena2 === "") {

            event.preventDefault();
            contrasenaInput.css('border', '2px solid #FF4500');
            contrasenaInput2.css('border', '2px solid #FF4500');

            spanerror.css({
                'display'           : 'inline-block',
                'background-color'  : '#FF4500',
                'margin'            : '10px',
                'color'             : 'white',
                'padding'           : '10px'
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