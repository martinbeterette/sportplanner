<?php
session_start();
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once(RUTA . "php/functions/consulta_reutilizable_mysql.php");
require_once(RUTA . "config/database/db_functions.php");

if (isset($_GET['id']) && isset($_GET['id_complejo'])) {
	$id_socio = $_GET['id'];
	$id_complejo = $_GET['id_complejo'];
} else {
	echo "ha ocurrido un error :(" . "<br>";
	echo "<a href='" . BASE_URL . "index_tincho.php" . "'></a>";
	die;
}

if (isset($_POST['formulario'])) {
	$id_complejo 			= $_POST['id_complejo'];
	$nombre 				= $_POST['nombre'];
	$apellido 				= $_POST['apellido'];
	$descripcion_documento 	= $_POST['descripcion_documento'];
	$rela_sexo 				= (int) $_POST['rela_sexo'];
	$descripcion_contacto 	= $_POST['telefono'];
	$fecha_nacimiento 		= $_POST['fecha_nacimiento'];
	$rela_tipo_documento 	= $_POST['tipo_documento'];
	$rela_membresia 		= $_POST['rela_membresia'];
	$id_documento 			= $_POST['id_documento'];

	$sqlValidarPersona = "SELECT p.id_persona, p.nombre, p.apellido, d.descripcion_documento, p.fecha_nacimiento
							FROM persona p
							JOIN documento d ON d.id_documento = p.rela_documento
							WHERE d.descripcion_documento = ? 
							AND d.rela_tipo_documento = ?
							AND d.id_documento != ?";
	$stmt = $conexion->prepare($sqlValidarPersona);
	$stmt->bind_param('sii', $descripcion_documento, $rela_tipo_documento, $id_documento);
	$stmt->execute();
	$resultado = $stmt->get_result();

	if ($resultado->num_rows > 0) {
		$persona = $resultado->fetch_assoc();
		$nombre = $persona['nombre'];
		$apellido = $persona['apellido'];
		$documento = $persona['descripcion_documento'];
		$fechaNacimiento = $persona['fecha_nacimiento'];

		$get = "id={$id_socio}&id_complejo={$id_complejo}&persona_repetida&nombre={$nombre}&apellido={$apellido}&descripcion_documento={$documento}&fecha_nacimiento={$fechaNacimiento}";
		header("Location: " . BASE_URL . "php/socio/tabla_socios.php?" . $get);
		exit();
	} else {

		$sqlUpdate = "UPDATE persona p JOIN documento ON rela_documento = id_documento
			JOIN contacto c ON p.id_persona = c.rela_persona
			JOIN socio s ON p.id_persona = s.rela_persona
			SET descripcion_documento = '$descripcion_documento', rela_tipo_documento = $rela_tipo_documento,
			nombre = '$nombre', apellido = '$apellido', fecha_nacimiento = '$fecha_nacimiento', 
			rela_membresia = $rela_membresia, descripcion_contacto = '$descripcion_contacto', p.rela_sexo = $rela_sexo
			WHERE id_socio = $id_socio";

		if ($conexion->query($sqlUpdate)) {
			header("location: " . BASE_URL . "php/socio/tabla_socios.php?id_complejo={$id_complejo}");
			exit();
		}
	}
}

$registrosSexo = obtenerSexos();
$registrosTipoDocumento = obtenerTipoDocumentos();
$registrosMembresia = obtenerMembresias(20, 0);

$campos = ['id_socio as id', 'nombre', 'apellido', 'id_documento', 'descripcion_contacto', 'rela_sexo', 'descripcion_documento', 'rela_tipo_documento', 'fecha_nacimiento', 'rela_membresia', 'descripcion_complejo'];
$tabla = 'socio s'; // La tabla principal

// Define el JOIN con la tabla ciudades
$join = ' JOIN persona p ON p.id_persona = s.rela_persona
			JOIN contacto c ON p.id_persona = c.rela_persona
			JOIN documento d ON p.rela_documento = d.id_documento
			JOIN complejo ON id_complejo = s.rela_complejo';

// Define la condición WHERE para buscar 
$condicion = "s.estado IN(1) AND rela_complejo = {$id_complejo} AND id_socio = {$id_socio}";

// Obtén los registros de la base de datos con JOIN y WHERE
$registros = obtenerRegistros($tabla, $campos, $join, $condicion);
$regi = $registros->fetch_assoc();
$id_documento = $regi['id_documento'];
// var_dump($regi);die;
//titulos y alta
$titulo_pagina = "Alta de Socio";
$modulo = "Alta de socios complejo {$regi['descripcion_complejo']}";


?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $titulo_pagina; ?></title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<style>
		@import url(../../css/header.css);
		@import url(../../css/aside.css);

		body {
			background: #161616;
			font-family: Arial, Helvetica, sans-serif;
		}

		/* Formulario Empleado/////////////////////////////////////77 */
		/* Estilos generales para el contenedor del formulario */
		.containerEmpleado {
			width: 60%;
			margin: auto;
			margin-top: 10px;
			padding: 20px;
			background-color: #212121;
			border-radius: 8px;
			box-shadow: 0px 0px 10px rgb(128, 128, 128, 0.7);
		}

		.containerEmpleado h1 {
			color: #fff;
			text-align: center;
		}

		.containerEmpleado form {
			margin-top: 10px;
		}

		/* Estilos para las etiquetas de los campos */
		.containerEmpleado label {
			display: block;
			margin-bottom: 8px;
			font-weight: bold;
			color: #fff;
			text-align: center;
		}

		/* Estilos para los campos de entrada de texto */
		.containerEmpleado input[type="text"],
		.containerEmpleado input[type="date"],
		.containerEmpleado select {
			width: 100%;
			padding: 10px;
			margin-bottom: 20px;
			border: 1px solid #2c2c2c;
			border-radius: 4px;
			font-size: 16px;
			box-sizing: border-box;
			transition: border-color 0.3s ease;
		}

		/* Estilos para cambiar el color del borde cuando el campo está enfocado */
		.containerEmpleado input[type="text"]:focus,
		.containerEmpleado input[type="date"]:focus,
		.containerEmpleado select:focus {
			border-color: grey;
			box-shadow: 1px 0px 3px grey;
			outline: none;
		}

		/* Estilos para el botón de enviar */
		.containerEmpleado button {
			width: 40%;
			padding: 12px;
			background-color: #2c2c2c;
			color: #fff;
			border: none;
			border-radius: 4px;
			font-size: 16px;
			cursor: pointer;
			transition: background-color 0.3s ease;
		}

		/* Cambio de color al pasar el cursor sobre el botón */
		.containerEmpleado button:hover {
			background-color: #0b0b0b;
			border: 1px solid grey;
			box-shadow: 1px 0px 3px gray;
		}

		/* Ajustes para pantallas pequeñas */
		@media (max-width: 480px) {
			.containerEmpleado {
				padding: 10px;
			}

			.containerEmpleado label {
				font-size: 14px;
			}

			.containerEmpleado input[type="text"],
			.containerEmpleado input[type="date"],
			.containerEmpleado select {
				font-size: 14px;
			}

			.containerEmpleado button {
				font-size: 14px;
				padding: 10px;
			}
		}

		.error {
			color: #ff6448;
			margin-bottom: 10px;
		}
	</style>
</head>

<body>
	
	<?php include(RUTA. "includes/header.php"); ?>

	<?php include(RUTA."includes/menu_aside.php") ?>


	<div class="containerEmpleado">
		<h1>Modificar Socio</h1>
		<form action="<?php echo $_SERVER['PHP_SELF'] . "?id={$id_socio}&id_complejo={$id_complejo}"; ?>" method="post">

			<label for="nombre">Nombre:</label>
			<input type="text" id="nombre" name="nombre" value="<?php echo $regi['nombre']; ?>">

			<label for="apellido">Apellido:</label>
			<input type="text" id="apellido" name="apellido" value="<?php echo $regi['apellido']; ?>">

			<label for="descripcion_documento">Documento:</label>
			<input type="text" id="documento" name="descripcion_documento" value="<?php echo $regi['descripcion_documento']; ?>">

			<label for="tipo_documento">Tipo de Documento:</label>
			<select name="tipo_documento" required>
				<option value='' disabled selected>Seleccione un tipo de documento...</option>
				<?php foreach ($registrosTipoDocumento as $reg) : ?>
					<option value="<?php echo $reg['id_tipo_documento']; ?>" <?php if ($reg['id_tipo_documento'] == $regi['rela_tipo_documento']) {
																					echo 'selected';
																				} ?>><?php echo $reg['descripcion_tipo_documento']; ?></option>
				<?php endforeach; ?>
			</select>

			<label for="fecha_nacimiento">Fecha de nacimiento:</label>
			<input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $regi['fecha_nacimiento']; ?>">
			<p id="error_message" style="color: red; display: none;">Solo se permite mayores de 18 años</p>

			<label for="rela_sexo">Sexo:</label>
			<select name="rela_sexo">
				<option value='' disabled selected>Seleccione un sexo...</option>
				<?php foreach ($registrosSexo as $reg) : ?>
					<option value="<?php echo $reg['id_sexo']; ?>" <?php if ($reg['id_sexo'] == $regi['rela_sexo']) {
																		echo 'selected';
																	} ?>> <?php echo $reg['descripcion_sexo']; ?></option>
				<?php endforeach; ?>
			</select>

			<label for="telefono">Teléfono:</label>
			<input type="text" id="telefono" name="telefono" value="" placeholder="ej: 1239874321" required>
			<p id="error_contacto" style="color: red; display: none;">El teléfono debe tener 10 dígitos numéricos.</p>

			<label for="rela_membresia">Membresia:</label>
			<select name="rela_membresia">
				<option value='' disabled selected>Seleccione una membresia...</option>
				<?php foreach ($registrosMembresia as $reg) : ?>
					<option value="<?php echo $reg['id_membresia']; ?>" <?php if ($reg['id_membresia'] == $regi['rela_membresia']) {
																			echo 'selected';
																		} ?>><?php echo $reg['descripcion_membresia'] . ' - %' . $reg['beneficio_membresia']; ?></option>
				<?php endforeach; ?>
			</select>

			<input type="hidden" name="id_complejo" value="<?php echo $id_complejo; ?>">
			<input type="hidden" name="id_documento" value="<?php echo $id_documento; ?>">

			<button type="submit" id="formulario" name="formulario">Enviar</button>
		</form>
	</div>

	<script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>
	<script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
	<script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>

	<script src="<?php echo BASE_URL . 'libs/sweetalert2.all.min.js' ?>"></script>
	<script src="../../js/validacionForm.js"></script>
	<script src="../../js/validarEdad.js"></script>
	<script src="../../js/validartelefono.js"></script>
	<?php if (isset($_GET['persona_repetida'])) { ?>
		<script>
			swal.fire({
				icon: 'warning',
				text: 'Esta persona ya existe en la base de datos'
			})
		</script>
	<?php } ?>

</body>

</html>