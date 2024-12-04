<?php
require_once("../../config/root_path.php");
require_once("../../config/database/conexion.php");
require_once("../../config/database/db_functions.php");
session_start();

if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_perfil'])) {
	header("Location: ../../index_tincho.php");
	exit();
}


?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Modificar Usuario</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="stylesheet" href="../../css/header.css">
	<link rel="stylesheet" href="../../css/aside.css">
	<link rel="stylesheet" href="modificarMisDatos.css">
</head>

<body>
	<?php include(RUTA . "includes/header.php"); ?>
	<?php include(RUTA . "includes/menu_aside.php") ?>

	<div class="perfil-modulo">
		<a class="back-button" href="../../index2.php">Volver</a>

		<?php
		if (isset($_GET['datos_personales'])) {
			$registros_sexo = obtenerSexos();
		?>

			<form action="aplicar_modificar_datos_personales.php" method="POST">
				<h1>Modificar mis Datos</h1>
				<input type="hidden" name="tipo_formulario" value="datos_personales">
				<label for="nombre">nombre:</label>
				<input type="text" id="username" name="nombre" value="<?php echo $_SESSION['datos_personales']['nombre']; ?>">
				<br>

				<label for="apellido">apellido:</label>
				<input type="text" id="username" name="apellido" value="<?php echo $_SESSION['datos_personales']['apellido']; ?>">
				<br>

				<label for="documento">documento:</label>
				<input type="text" id="username" name="documento" value="<?php echo $_SESSION['datos_personales']['descripcion_documento']; ?>">
				<br>

				<label for="sexo">sexo:</label>
				<select name="sexo">
					<option selected value="" disabled>Seleccione un sexo...</option>
					<?php foreach ($registros_sexo as $reg) { ?>
						<option value="<?php echo $reg['id_sexo']; ?>" <?php if ($reg['descripcion_sexo'] == $_SESSION['datos_personales']['descripcion_sexo']) {
																			echo 'selected';
																		} ?>> <?php echo $reg['descripcion_sexo']; ?></option>
					<?php } ?>
				</select>
				<br>

				<button type="submit">Enviar</button>
			</form>
		<?php } ?>

		<?php if (isset($_GET['datos_de_usuario'])) { ?>

			<form action="aplicar_modificar_datos_de_usuario.php" method="POST">
				<h1>Modificar mis Datos</h1>
				<input type="hidden" name="tipo_formulario" value="datos_de_usuario">

				<label for="email">E-mail:</label>
				<input type="text" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" disabled>
				<br>

				<label for="username">Usuario:</label>
				<input type="text" id="username" name="username" value="<?php echo $_SESSION['usuario']; ?>">
				<br>

				<button type="submit">Enviar</button>
			</form>
		<?php } ?>

		<?php if (isset($_GET["error"])) { ?>
			<?php if ($_GET['error'] == '1') { ?>
				<span class="error-message">Error: 1</span>
			<?php } else { ?>
				<span class="error-message">Error: 2 Contraseñas no coinciden</span>
			<?php } ?>
		<?php } ?>
	</div>

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="../../js/aside.js"></script>
	<script src="../../js/header.js"></script>
	<script>
		$(document).ready(function() {

			$('form').on('submit', function(event) {

				//obtenemos variables para operar la validacion
				var usuarioInput = $('#username');


				//ocultamos por defecto los span PREVIO a su validacion

				var usuario = usuarioInput.val();


				var regexusuario = /^(?=.*[a-zA-Z])[a-zA-Z\d]{5,}$/;
			});
		});
	</script>
	<script>
		$(document).ready(function() {
			$('form').on('submit', function(event) {
				let isValid = true;
				$('input, select').each(function() {
					if (!$(this).val()) {
						$(this).css('border', '2px solid #e74c3c');
						isValid = false;
					} else {
						$(this).css('border', '1px solid #bdc3c7');
					}
				});

				if (!isValid) {
					alert('Por favor, complete todos los campos.');
					event.preventDefault();
				}
			});
		});
	</script>
</body>

</html>