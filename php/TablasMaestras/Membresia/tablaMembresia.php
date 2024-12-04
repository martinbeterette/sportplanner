<?php 

	require_once("../../../config/database/conexion.php");
	require_once("../../../config/root_path.php");
    session_start();

   
    
	require_once('../../../config/database/db_functions.php');
	$registros_por_pagina 	= 5;
	$pagina_actual 			= 1;
	$offset 				= ($pagina_actual - 1) * $registros_por_pagina;

	$registros = obtenerMembresias($registros_por_pagina, $offset);
	$tabla = "Membresias";
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tabla <?php echo $tabla ?></title>
	<link rel="stylesheet" href="../css/index.css">
</head>
<body>
	<h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px;">Modulo de <?php echo $tabla; ?></h1>
	<a class="back-button" href="../../../index_tincho.php">Volver</a>
	<table>
		<thead>
			<tr>
				<th>C&oacute;digo</th>
				<th>Beneficio</th>
				<th>Descripcion</th>
				<th></th>
				<th></th>
			</tr>
		</thead>

		<tbody>

		<?php foreach ($registros as $reg) : 

			$id 		 = $reg['id_membresia'];
			$beneficio   = $reg['beneficio_membresia'];
			$descripcion = $reg['descripcion_membresia'];

			$modificar = "<a href='tablaMembresia_modificacion.php?id_membresia=$id'>
								<img src='../../../assets/icons/editar_azul.png'>
							</a>";

			$eliminar =	"<a href=\"javascript:confirmDelete($id)\">
								<img src='../../../assets/icons/eliminar.png'>
							</a>";

		?>


				 <tr>
					 <td> <?php echo $id;										?></td>
					 <td> <?php echo $beneficio; 								?></td>
					 <td> <?php echo $descripcion;								?></td>
					 <td> <?php echo $modificar; 								?></td>
					 <td> <?php echo $eliminar;									?></td>
				 </tr>
				 
		<?php endforeach; ?>
		 
 		<tr>
 			<td colspan="8" class="alta">
 				<a href="tablaMembresia_alta.php">
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
                window.location.href = "tablaMembresia_baja.php?id_membresia=" + id;
            }
        }
    </script>
</body>
</html>
	
