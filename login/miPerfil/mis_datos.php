<?php
require_once("../../config/root_path.php");
require_once("../../config/database/conexion.php");
require_once("../../config/database/db_functions.php");
session_start();

$sql = "SELECT
    			persona.nombre,
    			persona.apellido,
    			documento.descripcion_documento,
    			sexo.descripcion_sexo,
				sexo.id_sexo
    		FROM
    			persona 
    		JOIN 
    			contacto
    		ON
    			contacto.rela_persona = persona.id_persona
            JOIN 
    			documento
    		ON
    			persona.id_persona = documento.rela_persona
    		JOIN
    			sexo
    		ON
    			persona.rela_sexo = sexo.id_sexo
    		WHERE
				contacto.descripcion_contacto LIKE ?
    		";

$stmt = $conexion->prepare($sql);
$stmt->bind_param('s', $_SESSION['email']);
$stmt->execute();
$resultado = $stmt->get_result();
$datos_personales = $resultado->fetch_assoc();

$nombre = $datos_personales['nombre'];
$apellido = $datos_personales['apellido'];
$documento = $datos_personales['descripcion_documento'];
$sexo = $datos_personales['descripcion_sexo'];
unset($_SESSION['datos_personales']);
$_SESSION['datos_personales'] = $datos_personales;

$registros_sexo = obtenerSexos();

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Datos de Usuario</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
		rel="stylesheet">
	<link rel="stylesheet" href="../../css/header.css">
	<link rel="stylesheet" href="../../css/aside.css">
	<link rel="stylesheet" href="../../css/footer.css">
	<link rel="stylesheet" href="misDatos.css">
</head>

<body>
	<?php include(RUTA . "includes/header.php"); ?>
	<?php include(RUTA . "includes/menu_aside.php") ?>

	<form>
		<h1>Datos de Usuario</h1>

		<fieldset>
			<legend align="center">Informacion Personal</legend>

			<div class="modificar">
				<button id="modificarpersonales">
					<i class="fa-solid fa-user-pen fa-2xl" style="color: #28a745;"></i>
				</button>
				<br>
			</div>

			<label for="nombre">nombre:</label>
			<h3 name="nombre"><?php echo $nombre; ?></h3>

			<label for="apellido">apellido:</label>
			<h3 name="apellido"><?php echo $apellido; ?></h3>

			<label for="dni">dni:</label>
			<h3 name="dni"><?php echo $documento; ?></h3>

			<label for="sexo">sexo:</label>
			<h3 name="sexo"><?php echo $sexo; ?></h3>

		</fieldset>

		<fieldset>
			<legend align="center">Datos del Usuario</legend>

			<div class="modificar">
				<button id="modificarusuario" onclick="location.href='modificar_mis_datos.php?datos_de_usuario'">
					<i class="fa-solid fa-user-pen fa-2xl" style="color: #28a745;"></i>
				</button>
				<br>
			</div>

			<label for="email">Email:</label>
			<h3 name="email"><?php echo $_SESSION['email']; ?></h3>

			<label for="usuario">Usuario:</label>
			<h3 name="usuario"><?php echo $_SESSION['usuario']; ?></h3>

			<label for="perfil">Perfil:</label>
			<h3 name="perfil"><?php echo $_SESSION['perfil']; ?></h3>

			<div class="modificarcontrasena">
				<button onclick="location.href='cambiar_contrasena.php'">
					Cambiar contraseña
				</button>
			</div>
		</fieldset>
	</form>

	<?php include(RUTA . "includes/footer.php") ?>

	<script src="../../libs/jquery-3.7.1.min.js"></script>
	<script src="../../libs/sweetalert2.all.min.js"></script>
	<script src="../../js/aside.js"></script>
	<script src="../../js/header.js"></script>
	<script src="../../js/terminoscondiciones.js"></script>

	<script>
		document.getElementById('modificarpersonales').addEventListener('click', function(e) {
			e.preventDefault(); // Evita la redirección por defecto del botón.

			// Obtén los datos actuales desde PHP (ya están cargados en las variables de PHP).
			const nombre = "<?php echo $nombre; ?>";
			const apellido = "<?php echo $apellido; ?>";
			const dni = "<?php echo $documento; ?>";
			const sexoActual = "<?php echo $sexo; ?>";

			// Crea el formulario dentro del modal.
			Swal.fire({
				title: 'Modificar Datos Personales',
				html: `
                <form id="formModificarPersonales">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="swal2-input" value="${nombre}" required>
                    
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" class="swal2-input" value="${apellido}" required>
                    
                    <label for="dni">DNI:</label>
                    <input type="text" id="dni" name="dni" class="swal2-input" value="${dni}" required>
                    
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo" class="swal2-select" required>
                        <?php foreach ($registros_sexo as $reg) { ?>
						<option value="<?php echo $reg['id_sexo']; ?>" <?php if ($reg['descripcion_sexo'] == $_SESSION['datos_personales']['descripcion_sexo']) {
																			echo 'selected';
																		} ?>> <?php echo $reg['descripcion_sexo']; ?></option>
						<?php } ?>
                    </select>
                </form>
            `,
				showCancelButton: true,
				confirmButtonText: 'Guardar',
				preConfirm: () => {
					// Obtén los valores del formulario.
					const form = document.getElementById('formModificarPersonales');
					const formData = new FormData(form);
					return {
						nombre: formData.get('nombre'),
						apellido: formData.get('apellido'),
						dni: formData.get('dni'),
						sexo: formData.get('sexo')
					};
				}
			}).then((result) => {
				if (result.isConfirmed) {
					const datos = result.value;

					// Enviar los datos al servidor mediante AJAX para guardarlos.
					$.ajax({
						url: 'aplicar_modificar_datos_personales.php', // Cambia por la URL de tu endpoint.
						method: 'POST',
						data: datos,
						success: function(response) {
							Swal.fire('¡Guardado!', 'Tus datos han sido actualizados.', 'success').then(() => {
								location.reload(); // Recarga la página para reflejar los cambios.
							});
						},
						error: function() {
							Swal.fire('Error', 'Ocurrió un problema al guardar los datos.', 'error');
						}
					});
				}
			});
		});
	</script>
</body>

</html>