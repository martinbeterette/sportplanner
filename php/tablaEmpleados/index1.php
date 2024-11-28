<?php
session_start();
require_once("../../config/root_path.php");
require_once('../../config/database/db_functions.php');
if (isset($_GET['id_sucursal'])) {
	$id_sucursal = $_GET['id_sucursal'];
} else {
	echo "ha ocurrido un error :(" . "<br>";
	echo "<a href='" . BASE_URL . "index_tincho.php" . "'>Volver</a>";
	die;
}

$registros = obtenerEmpleados($id_sucursal);
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>TABLA EMPLEADOS</title>

	<link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside.css'; ?>">
	<link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css' ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="stylesheet" href="css/index.css">
</head>

<body>
	<?php include(RUTA. "includes/header.php"); ?>

	<?php include(RUTA."includes/menu_aside.php") ?>

	<script src="js/jquery-3.7.1.min.js"></script>
	<div class="container">

		<h1 align="center">Modulo de Empleados de Complejos Deportivos</h1>
		<p>buscar</p>
		<input type="text" id="buscador" placeholder="Nombre, apellido, documento">
		<div class="tabla-container" id="tabla-container"></div>
		<div class="paginacion-container" id="tabla-container"></div>

		<button class="btn-agregar" onclick="window.location.href='formulario_creacion_empleado/?id_sucursal=<?php echo $id_sucursal; ?>'">Agregar Empleado</button>
	</div>

	<script> let id_sucursal = <?php echo $id_sucursal; ?>;</script>
	<script>
		function confirmDelete(id, id_sucursal) {
			var respuesta = confirm("¿Estás seguro de que deseas eliminar este registro?");
			if (respuesta) {
				// Si el usuario hace clic en "Aceptar", redirige a la página de eliminación
				window.location.href = "formulario_creacion_empleado/includes/eliminar.php?id_empleado=" + id + "&id_sucursal=" + id_sucursal;
			}
		}
	</script>
	<script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>
	<script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
	<script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>
	<script src="js/tablaYPaginado.js"></script>
	<script>
		$(document).ready(function(){
			$(document).on('click', '.eliminar', function(){
				let id_empleado = $(this).attr('valor');
				confirmDelete(id_empleado, id_sucursal)
			})
		});
	</script>

</body>

</html>