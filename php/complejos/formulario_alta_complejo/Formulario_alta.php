<?php  
session_start();
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

$errores = isset($_GET['errores']) ? $_GET['errores'] : [];

// PERSISTENCIA DE DATOS
    $descripcion_complejo = $_GET['descripcion_complejo'] ?? '';
    $fecha_fundacion_complejo = $_GET['fecha_fundacion_complejo'] ?? '';
    $descripcion_sucursal = $_GET['descripcion_sucursal'] ?? '';
    $fecha_fundacion_sucursal = $_GET['fecha_fundacion_sucursal'] ?? '';
    $rela_barrio = $_GET['barrio'] ?? '';
    $direccion = $_GET['direccion'] ?? '';
    $email = $_GET['email'] ?? '';
    $username = $_GET['username'] ?? '';
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="<?php echo BASE_URL. "css/header.css" ?>">
	<link rel="stylesheet" href="<?php echo BASE_URL. "css/aside.css" ?>">
	<link rel="stylesheet" href="css/formulario_wizard.css">
</head>
<body>
	<?php include(RUTA . "includes/header.php"); ?>
	<?php include(RUTA . "includes/menu_aside.php"); ?>

	<div class="formulario-wizard">
		
		<form action="includes/alta_complejo.php" method="POST" id="formulario">
			
			<div class="paso" id="paso1">
				<h3>Datos del Complejo</h3>

				<label for="descripcion_complejo">Nombre del complejo:</label>
				<input type="text" name="descripcion_complejo" id="descripcion_complejo"
				<?php echo "value='$descripcion_complejo'" ?>
				>
				<span id="error_descripcion_complejo"></span>

				<label for="fecha_fundacion_complejo">Fecha de Fundaci&oacute;n</label>
				<input type="date" name="fecha_fundacion_complejo" id="fecha_fundacion_complejo"
				<?php echo "value='$fecha_fundacion_complejo'"; ?>
				>
				<span id="error_fecha_fundacion_complejo"></span>


				<div class="botones">
					<button type="button" class="siguiente" paso="1">Siguiente</button>
				</div>

			</div>

			<div class="paso" id="paso2">
				<h3>Datos de la Sucursal</h3>

				<label for="descripcion_sucursal">Nombre de la sucursal:</label>
				<input type="text" name="descripcion_sucursal" id="descripcion_sucursal"
				<?php echo "value='$descripcion_sucursal'"; ?>
				>
				<span id="error_descripcion_sucursal"></span>

				<label for="provincia">Provincia</label>
				<select name="provincia" id="provincia" class="domicilio">
					<option value="" disabled selected>Seleccione una Provincia...</option>
					<!-- esto con php -->
				</select>

				<label for="localidad">Localidad</label>
				<select name="localidad" id="localidad" class="domicilio">
					<option value="" disabled selected>Seleccione una localidad...</option>
					<!-- esto con ajax -->
				</select>

				<label for="barrio">Barrio</label>
				<select name="barrio" 	 id="barrio" class="domicilio">
					<option value="" disabled selected>Seleccione un barrio...</option>
					<!-- esto con ajax -->
				</select>

				<label for="direccion">Direccion:</label>
				<input type="text" name="direccion" id="direccion"
				<?php echo "value='$direccion'" ?>
				>
				<span id="direccion_error"></span>


				<label for="fecha_fundacion_sucursal">Fecha de Fundaci&oacute;n</label>
				<input type="date" name="fecha_fundacion_sucursal" id="fecha_fundacion_sucursal"
				<?php echo "value='$fecha_fundacion_sucursal'" ?>
				>
				<span id="error_fecha_fundacion_sucursal"></span>
				<div class="botones">
					<button type="button" class="anterior" paso="2">Anterior</button>
					<button type="button" class="siguiente" paso="2">Siguiente</button>
				</div>


			</div>

			<div class="paso" id="paso3">
			    <h3>Crear Cuenta de Administrador</h3>

<!-- 			    <label for="email">Correo Electrónico:</label>
			    <input type="email" name="email" id="email">
			    <span id="error_email"></span> -->

			    <label for="username">Nombre de Usuario:</label>
			    <input type="text" name="username" id="username"
			    <?php echo "value='$username'"; ?>
			    >
			    <span id="username-feedback" class="error"></span><br>
			    <span id="error_username"></span>

			    <label for="password">Contraseña:</label>
			    <input type="password" name="password" id="password">
			    <span id="error_password"></span>

			    <label for="confirm_password">Confirmar Contraseña:</label>
			    <input type="password" name="confirm_password" id="confirm_password">
			    <span id="error_confirm_password"></span>

			    <div class="botones">
			        <button type="button" class="anterior" paso="3">Anterior</button>
			        <button type="submit" id="btn-finalizar">Finalizar</button>
			    </div>
			</div>


		</form>

		<!-- Contenedor de mensaje tipo "naranja" -->
	    <div class="message-container" id="mensaje-prueba">
	        <span id="mensaje">Formulario rechazado. Verifique los campos del formulario.</span>
	        <span class="close-btn" onclick="cerrarMensaje()">×</span>
	    </div>

	</div>
	<!-- <script>alert("falta los divs de los inputs (no los pongo porque rompe el css)");</script> -->
	<script src="<?php echo BASE_URL; ?>libs/jquery-3.7.1.min.js"></script>
	<script src="js/pasos_y_obtener_domicilios.js"></script>
	<!-- <script src="js/validaciones_de_formulario.js"></script> -->
	<script src="js/verificar_usuario_existente.js"></script>
	<script src="<?php echo BASE_URL ?>js/header.js"></script>
	<script src="<?php echo BASE_URL ?>js/aside.js"></script>

	<script>
	    // Función para mostrar el contenedor de mensaje
	    function mostrarMensaje() {
	        $("#mensaje-prueba").css('display','block');
	    }

	    // Función para cerrar el contenedor de mensaje
	    function cerrarMensaje() {
	        $('#mensaje-prueba').css('display','none');
	    }

	    let errores = 
	    <?php 
	        if (!empty($errores)) {
	            echo json_encode($errores);
	        } else {
	            echo 'false';
	        }
        ?>;

        $(document).ready(function() {
        	if (errores) {
        		mostrarMensaje();
        	}
        });
	</script>
</body>
</html>