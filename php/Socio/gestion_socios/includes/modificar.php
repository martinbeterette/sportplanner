<?php 

session_start();
require_once("../../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once("functions.php");
$registrosSexo = $conexion->query("SELECT * FROM sexo");
$registrosTipoDocumento = $conexion->query("SELECT * FROM tipo_documento");
$registrosMembresia = $conexion->query("SELECT * FROM membresia");

$id_socio = $_GET['id_socio'] ?? 12;
$id_complejo = $_GET['id_complejo'] ?? null;

$consulta = "
	SELECT id_socio, nombre, apellido, id_documento, rela_sexo, descripcion_documento, rela_tipo_documento, fecha_nacimiento, rela_membresia, descripcion_complejo
	FROM
	socio s
	JOIN persona p ON p.id_persona = s.rela_persona
				JOIN documento d ON p.id_persona = d.rela_persona
				JOIN complejo ON id_complejo = s.rela_complejo
	WHERE 
	s.estado IN(1) AND rela_complejo = $id_complejo AND id_socio = $id_socio
";
$regi = $conexion->query($consulta)->fetch_assoc();


if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$id_complejo 			= $_POST['id_complejo'];
	$nombre 				= $_POST['nombre'];
	$apellido 				= $_POST['apellido'];
	$descripcion_documento 	= $_POST['descripcion_documento'];
	$rela_sexo 				= (int) $_POST['rela_sexo'];
	$fecha_nacimiento 		= $_POST['fecha_nacimiento'];
	$rela_tipo_documento 	= $_POST['tipo_documento'];
	$rela_membresia 		= $_POST['rela_membresia'];
	$id_documento 			= $_POST['id_documento'];

	$validacion = validarPersona($id_documento, $descripcion_documento, $rela_tipo_documento);
	if($validacion->num_rows >= 1) {
		echo "ya existe"; die;
	} else {
		// Intentar actualizar el registro
        if (actualizarSocio($conexion, $id_socio, $descripcion_documento, $rela_tipo_documento, $nombre, $apellido, $fecha_nacimiento, $rela_membresia, $rela_sexo)) {
            // Redirigir si la actualización fue exitosa
            
            header("Location: ../?id_complejo=$id_complejo");
            exit();
        } else {
            echo "Hubo un error al actualizar el registro.";
        }
	}
}

?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Modificar Socio</title>
	<link rel="stylesheet" href="../css/modificar.css">
	<link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css' ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside.css' ?>">
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
			<select name="tipo_documento" >
				<option value='' disabled selected>Seleccione un tipo de documento...</option>
				<?php foreach ($registrosTipoDocumento as $tp) : ?>
					<option 
						value="<?= $tp['id_tipo_documento'] ?>"
						<?php 
							if ($regi['rela_tipo_documento'] == $tp['id_tipo_documento']){
								echo "selected";
							}
						?>
					><?php echo $tp['descripcion_tipo_documento']; ?></option>
					
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



			<label for="rela_membresia">Membresia:</label>
			<select name="rela_membresia">
				<option value='' disabled selected>Seleccione una membresia...</option>
				<?php foreach ($registrosMembresia as $reg) : ?>
					<option 
						value="<?php echo $reg['id_membresia']; ?>" 
						<?php 

							if ($reg['id_membresia'] == $regi['rela_membresia']) {
								echo 'selected';
							} 
						?>
					>
						<?php echo $reg['descripcion_membresia'] . ' - %' . $reg['descuento']; ?>
					</option>
				<?php endforeach; ?>
			</select>

			<input type="hidden" name="id_complejo" value="<?php echo $id_complejo; ?>">
			<input type="hidden" name="id_documento" value="<?php echo $regi['id_documento']; ?>">

			<button type="submit" id="formulario" name="formulario">Enviar</button>
		</form>
	</div>
</body>
</html>
