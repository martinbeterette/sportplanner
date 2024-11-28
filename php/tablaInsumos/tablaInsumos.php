<?php 

	require_once('../../config/database/db_functions.php');

	$registros = obtenerInsumos();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>TABLA INSUMOS</title>
	<style>
		/* GENERALES */
		body{
			padding: 0;
			margin: 0;
			font-family: sans-serif;
			background-color: #96E072/*rgba(0, 0, 0, 0.85)*/;
			color: white;
		}

		table {
			border-collapse: collapse;
			width: 65%;
			margin: 0 auto;
			margin-top: 15vh;
			color: rgba(0, 0, 0, 0.58);
			font-weight: bold;
		}

		th, td {
			text-align: center;
			padding: 8px 10px;
		}

		td, th:nth-child(7),
		td, th:nth-child(8) {
			padding: 8px 5px;
		}

		/* ESTILOS ESPECIALES A LOS THEAD */

		body table thead tr {
			background-color: #25ac5c ;
			color:rgba(255, 255, 255, 0.9);
			text-align: left;
		}


		/* RENGLONES DE COLORES DIFERENTES */
		table tbody tr:nth-child(odd) {
			background-color: #f4f4f4 ;
		}

		table tbody tr:nth-child(even) {
			background-color: #E6E6E6 ;
		}

		.alta {
			text-align: center;
			
		}

		table tbody img{
			height:30px;
		}

		table tbody a:hover {
			cursor: pointer;
		}
	</style>
</head>
<body>
	<h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px;">Modulo de Insumos de Complejos Deportivos</h1>
	<table>
		<thead>
			<tr>
				<th>C&oacute;digo</th>
				<th>Descripcion</th>
				<th>Cantidad U.</th>
				<th>Fecha de Alta</th>
				<th>Categoria</th>
				<th>Estado</th>
				<th></th>
				<th></th>
			</tr>
		</thead>

		<tbody>

		<?php foreach ($registros as $reg) : 

			$id 		 = $reg['id_insumo'];
			$descripcion = $reg['descripcion_insumo'];
			$cantidad 	 = $reg['cantidad'];
			$fechaAlta 	 = $reg['fecha_alta'];
			$categoria   = $reg['categoria'];
			$estado  	 = $reg['descripcion_estado_insumo'];

			$modificar = "<a href='tablaInsumos_modificacion.php?id_insumo=$id'>
								<img src='../../assets/icons/editar_azul.png'>
							</a>";

			$eliminar =	"<a href=\"javascript:confirmDelete($id)\">
								<img src='../../assets/icons/eliminar.png'>
							</a>";

		?>


				 <tr>
					 <td> <?php echo $id;										?></td>
					 <td> <?php echo $descripcion;								?></td>
					 <td> <?php echo $cantidad;									?></td>
					 <td> <?php echo $fechaAlta;								?></td>
					 <td> <?php echo $categoria;								?></td>
					 <td> <?php echo $estado;									?></td>
					 <td> <?php echo $modificar 								?></td>
					 <td> <?php echo $eliminar 									?></td>
				 </tr>
				 
		<?php endforeach; ?>
		 
 		<tr>
 			<td colspan="8" class="alta">
 				<a href="tablaInsumos_alta.php">
 					<img src="../../assets/icons/agregar.png" type="icon">
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
                window.location.href = "tablaInsumos_baja.php?id_insumo=" + id;
            }
        }
    </script>
</body>
</html>
	
