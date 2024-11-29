<?php 
    require_once('../../config/root_path.php');
    require_once(RUTA . 'config/database/db_functions.php'); 
    require_once(RUTA . 'config/database/conexion.php');
    require_once('includes/functions.php');

    //validaciones
    require_once('includes/validaciones.php');
    $sexos = obtenerSexos();
    $documentos = $conexion->query("SELECT * FROM tipo_documento");
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registro usuario</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <!-- Breadcrumb con flecha hacia atrás -->
    <div class="breadcrumb" onclick="window.location.href='login.php';">
        Volver al Login
    </div>

    <form class="login-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

        <h1>Registrar Usuario</h1>

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo isset($nombre) ? $nombre : ''?>">
        </div>

        <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" value="<?php echo isset($apellido) ? $apellido : ''?>">
        </div>

        <div class="form-group">
            <label for="documento">Documento</label>
            <input type="text" id="documento" name="documento" value="<?php echo isset($documento) ? $documento : ''?>">
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
            <input type="text" id="email" name="email" value="<?php echo isset($email) ? $email : ''?>">
        </div>

        <div class="form-group">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" value="<?php echo isset($username) ? $username : ''?>">
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" value="<?php echo isset($contrasena) ? $contrasena : ''?>">
        </div>

        <div class="form-group">
            <label for="password">Confirmar Contraseña</label>
            <input type="password" id="password2" name="confirmar-contrasena" value="<?php echo isset($confirmar_contrasena) ? $confirmar_contrasena : ''?>">
        </div>		

        <div style="text-align: center;">
            <span id="mensaje-error"></span>	
        </div>

        <button type="submit">Ingresar</button>

    </form>

    <!-- Contenedor de mensaje tipo "naranja" -->
    <div class="message-container" id="mensaje-prueba">
        <span id="mensaje"></span>
        <span class="close-btn" onclick="cerrarMensaje()">×</span>
    </div>

<script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
<script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js" ?>"></script>
<script>
    // Función para mostrar el contenedor de mensaje
    function mostrarMensaje() {
        $("#mensaje-prueba").css('display','block');
    }

    // Función para cerrar el contenedor de mensaje
    function cerrarMensaje() {
        $('#mensaje-prueba').css('display','none');
    }
</script>
<script>
    let mensaje_error = <?php 
        if (isset($mensaje_error)) {
            echo json_encode($mensaje_error);
        } else {
            echo 'false';
        }
        ?>;
    let error_documento = <?php echo isset($error_documento) ? json_encode($error_documento)  : 'false' ?>;
    let error_usuario = <?php echo isset($error_usuario) ? json_encode($error_usuario)      : 'false' ?>; 
</script>

<script src="js/alertas_formulario.js"></script>
</body>
</html>