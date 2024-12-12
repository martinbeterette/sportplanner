<?php
//validaciones de perfil
require_once("../../../config/root_path.php");
require_once("../../../config/database/conexion.php");
session_start();

//para el abm
require_once('../../../config/database/db_functions.php');

$registros = obtenerPerfiles();
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tabla Perfiles</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../../../css/header.css">
	<link rel="stylesheet" href="../../../css/aside.css">
	<link rel="stylesheet" href="../../../css/footer.css">
	<link rel="stylesheet" href="../css/index.css">
	<style>
		* {
			padding: 0;
			margin: 0;
			box-sizing: border-box;
			font-family: "Poppins", sans-serif;
		}

		center {
			margin: 12px auto;
		}

		table {
			width: 90%;
			margin: 0 auto;
		}
	</style>
</head>

<body>
	<?php include(RUTA . "includes/header.php"); ?>
	<?php include(RUTA . "includes/menu_aside.php") ?>

	<h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px;">Modulo de Perfil</h1>

	<table>
		<thead>
			<tr>
				<th>C&oacute;digo</th>
				<th>Descripcion</th>
				<th></th>
				<th></th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ($registros as $reg) :

				$id 		 = $reg['id_perfil'];
				$descripcion = $reg['descripcion_perfil'];

				$modificar = "<a href='tablaPerfil_modificacion.php?id_perfil=$id'>
								<img src='../../../assets/icons/editar_azul.png'>
							</a>";

				$eliminar =	"<a href=\"javascript:confirmDelete($id)\">
								<img src='../../../assets/icons/eliminar.png'>
							</a>";

			?>


				<tr>
					<td> <?php echo $id;										?></td>
					<td> <?php echo $descripcion;								?></td>
					<td> <?php echo $modificar; 								?></td>
					<td> <?php echo $eliminar;									?></td>
				</tr>

			<?php endforeach; ?>

			<tr>
				<td colspan="8" class="alta">
					<a href="tablaPerfil_alta.php">
						<img src="../../../assets/icons/agregar.png" type="icon">
					</a>
				</td>
			</tr>

		</tbody>
	</table>

	<?php include(RUTA . "includes/footer.php") ?>

	<script src="../../../libs/jquery-3.7.1.min.js"></script>
	<script src="../../../libs/sweetalert2.all.min.js"></script>
	<script src="../../../js/header.js"></script>
	<script src="../../../js/aside.js"></script>
	<script src="../../../js/terminoscondiciones.js"></script>

	<script>
		function confirmDelete(id) {
			var respuesta = confirm("¿Estás seguro de que deseas eliminar este registro?");
			if (respuesta) {
				// Si el usuario hace clic en "Aceptar", redirige a la página de eliminación
				window.location.href = "tablaPerfil_baja.php?id_perfil=" + id;
			}
		}
	</script>
</body>

</html>