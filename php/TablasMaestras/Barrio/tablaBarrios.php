<?php
require_once("../../../config/database/conexion.php");
session_start();

// SETEAMOS EL PAGINADO
$registros_por_pagina = 5;

// Determinar la página actual
if (isset($_GET['pagina']) && is_numeric($_GET['pagina'])) {
	$pagina_actual = (int) $_GET['pagina'];
} else {
	$pagina_actual = 1;
}

//DEFINIMOS EL OFSET O LA CANTIDAD DE REGISTROS SKIPEADOS
$offset = ($pagina_actual - 1) * $registros_por_pagina;

require_once('../../../config/database/db_functions.php');
require_once('../../../config/root_path.php');
$registros = obtenerBarrios($offset, $registros_por_pagina);

// Número de registros por página
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tabla Barrios</title>
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

	<h1 style="text-align: center;">Modulo Barrio</h1>

	<table>
		<thead>
			<tr>
				<th>C&oacute;digo</th>
				<th>Descripcion</th>
				<th>Localidad</th>
				<th></th>
				<th></th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ($registros as $reg) :

				$id 		 = $reg['id_barrio'];
				$descripcion = $reg['descripcion_barrio'];
				$localidad   = $reg['descripcion_localidad'];

				$modificar = "<a href='tablaBarrios_modificacion.php?id_barrio=$id'>
								<img src='../../../assets/icons/editar_azul.png'>
							</a>";

				$eliminar =	"<a href=\"javascript:confirmDelete($id)\">
								<img src='../../../assets/icons/eliminar.png'>
							</a>";

			?>


				<tr>
					<td> <?php echo $id;										?></td>
					<td> <?php echo $descripcion;								?></td>
					<td> <?php echo $localidad;								?></td>
					<td> <?php echo $modificar; 								?></td>
					<td> <?php echo $eliminar;									?></td>
				</tr>

			<?php endforeach; ?>

			<tr>
				<td colspan="8" class="alta">
					<a href="tablabarrios_alta.php">
						<img src="../../../assets/icons/agregar.png" type="icon">
					</a>
				</td>
			</tr>

		</tbody>
	</table>

	<?php

	//calculamos total de paginas necesarias
	$sql = "SELECT COUNT(*) FROM barrio WHERE estado = 1";
	$registros = $conexion->query($sql);
	$reg = $registros->fetch_array();
	$total_registros = $reg[0];
	$total_paginas 	= ceil($total_registros / $registros_por_pagina);

	echo "<center>";
	if ($pagina_actual > 1) {
		echo "<a href='?pagina=" . ($pagina_actual - 1) . "' style='padding: 10px; border-radius: 5px; background-color: white; margin: 10px; margin-top:20px'>Previo</a> ";
	}

	if ($pagina_actual < $total_paginas) {
		echo "<a href='?pagina=" . ($pagina_actual + 1) . "' style='padding: 10px; border-radius: 5px; background-color: white; margin: 10px; margin-top: 20px'>Siguiente</a>";
	}
	echo "</center>";

	?>

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
				window.location.href = "tablabarrios_baja.php?id_barrio=" + id;
			}
		}
	</script>
</body>

</html>