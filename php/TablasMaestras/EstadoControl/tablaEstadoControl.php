<?php 
	
	require_once("../../../config/database/conexion.php");
    session_start();

   

	require_once('../../../config/database/db_functions.php');

	$registros = obtenerControles();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tabla estados de control</title>
	<link rel="stylesheet" href="../css/index.css">
</head>
<body>
	<h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px;">Modulo de estados del control de una reserva</h1>
	<a class="back-button" href="../../../index_tincho.php">Volver</a>
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

			$id 		 = $reg['id_estado_control'];
			$descripcion = $reg['descripcion_estado_control'];

			$modificar = "<a href='tablaestadocontrol_modificacion.php?id_estado_control=$id'>
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
 				<a href="tablaEstadocontrol_alta.php">
 					<img src="../../../assets/icons/agregar.png" type="icon">
 				</a>
 			</td>
 		</tr>

 		</tbody>
 	</table>

 	<script>
        function confirmDelete(id) {
            var respuesta = confirm("¿Estás seguro de que deseas eliminar este registro?");
            if (respuesta) {
                // Si el usuario hace clic en "Aceptar", redirige a la página de eliminación
                window.location.href = "tablaestadocontrol_baja.php?id_estado_control=" + id;
            }
        }
    </script>
</body>
</html>
	
