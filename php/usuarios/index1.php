<?php
session_start();
require_once("../../config/root_path.php");
require_once('../../config/database/db_functions.php');
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>TABLA USUARIOS</title>

	<link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside.css'; ?>">
	<link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css' ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="stylesheet" href="./css/tabla_usuarios.css">
</head>

<body>
	<?php include(RUTA . "includes/header.php"); ?>
	<?php include(RUTA . "includes/menu_aside.php"); ?>

	<div class="container">
		<h1 align="center">Módulo de Usuarios</h1>
		<p>Buscar:</p>
		<input type="text" id="buscador" placeholder="Nombre, Apellido, Usuario, Contacto, Estado de Verificación">
		<div class="tabla-container" id="tabla-container"></div>
		<div class="paginacion-container" id="paginacion-container"></div>
	</div>

	<script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>

	<script>
		const registrosPorPagina = 5;
		let paginaActual = 1;

		$(document).ready(function() {
			cargarDatos();

			$("#buscador").on("input", function() {
				let filtro = $(this).val();
				paginaActual = 1;
				cargarDatos(filtro);
			});

			function cargarDatos(filtro = '') {
				$.getJSON("./ajax/obtenerUsuarios.php", {
					pagina: paginaActual,
					filtro: filtro
				}, function(response) {
					if (response) {
						$("#tabla-container").html(response.tabla);
						$("#paginacion-container").html(
							`<p>Página ${response.current_page} de ${response.total_pages}</p>` +
							generarPaginacion(response.total_pages)
						);
					} else {
						$("#tabla-container").html("<p>No se encontraron resultados.</p>");
					}
				});
			}

			function generarPaginacion(totalPages) {
				let botones = '';
				for (let i = 1; i <= totalPages; i++) {
					botones += `<button class="pagina-boton" data-pagina="${i}">${i}</button>`;
				}
				return botones;
			}

			$(document).on('click', '.eliminar', function() {
				const idEmpleado = $(this).attr('id'); // Obtener el ID del empleado
				const estadoActual = $(this).data('estado'); // Obtener el estado actual (1 o 0)

				// Confirmar eliminación
				if (confirm("¿Estás seguro de que deseas eliminar este registro?")) {
					$.ajax({
						url: './ajax/tablaUsuarios_baja.php', // Ruta al archivo PHP
						type: 'GET',
						data: {
							id: idEmpleado,
							actual_estado: estadoActual
						},
						success: function(response) {
							if (response === 'success') {
								alert("El registro se actualizó correctamente.");
								location.reload(); // Recargar la página para reflejar los cambios
							} else {
								alert("Error al actualizar el registro: " + response);
							}
						},
						error: function() {
							alert("Hubo un problema con la solicitud.");
						}
					});
				}
			});

			$(document).on("click", ".pagina-boton", function() {
				paginaActual = $(this).data("pagina");
				cargarDatos($("#buscador").val());
			});
		});
	</script>
	<script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
	<script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>
</body>

</html>