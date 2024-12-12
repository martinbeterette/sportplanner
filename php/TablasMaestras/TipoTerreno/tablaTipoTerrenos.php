<?php 

	require_once("../../../config/database/conexion.php");
	require_once("../../../config/root_path.php");
    session_start();


	require_once('../../../config/database/db_functions.php');

	$registros = obtenerTipoTerrenos();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tabla Tipo Terrenos</title>
	<link rel="stylesheet" href="../css/index.css">
	<link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
	<link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
</head>
<body>
	<?php include(RUTA . "includes/header.php") ?>
	<?php include(RUTA . "includes/menu_aside.php") ?>
	<div class="container">
		
		<h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px;">Modulo de Tipos de Terrenos</h1>
		<a class="back-button" href="<?php echo BASE_URL; ?>">Volver</a>
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

				$id 		 = $reg['id_tipo_terreno'];
				$descripcion = $reg['descripcion_tipo_terreno'];

				$modificar = "<a href='tablaTipoTerrenos_modificacion.php?id_tipo_terreno=$id'>
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
	 				<a href="tablaTipoTerrenos_alta.php">
	 					<img src="../../../assets/icons/agregar.png" type="icon">
	 				</a>
	 			</td>
	 		</tr>

	 		</tbody>
	 	</table>
	</div>

	<script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
	<script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
	<script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
 	<script>
        function confirmDelete(id) {
            var respuesta = confirm("¿Estás seguro de que deseas eliminar este registro?");
            if (respuesta) {
                // Si el usuario hace clic en "Aceptar", redirige a la página de eliminación
                window.location.href = "tablaTipoTerrenos_baja.php?id_tipo_terreno=" + id;
            }
        }
    </script>
</body>
</html>
	
