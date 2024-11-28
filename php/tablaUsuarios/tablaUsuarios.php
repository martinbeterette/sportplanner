<?php 
	require_once("../../config/root_path.php");
	require_once('../../config/database/db_functions/usuarios.php');

	$registros = obtenerUsuarios();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>TABLA ZONAS</title>
	<link rel="stylesheet" type="text/css" href="../../css/zonas.css">
</head>
<body>
	<h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px;">Modulo Zonas de Complejos Deportivos</h1>
	<table>
		<thead>
			<tr>
				<th>ID Usuario</th>
				<th>Nombre</th>
				<th>Apellido</th>
				<th>Tipo de Documento</th>
				<th>Documento</th>
				<th>Usuario</th>
				<th>Email</th>
				<th></th>
				<th></th>
			</tr>
		</thead>

		<tbody>

		<?php foreach ($registros as $reg) : 

			$id 			= $reg['id_usuario'];
			$nombre 		= $reg['nombre'];
			$apellido		= $reg['apellido'];
			$tipoDocumento  = $reg['descripcion_tipo_documento'];
			$documento      = $reg['descripcion_documento'];
			$usuario        = $reg['username'];
			$email			= $reg['descripcion_contacto'];
			

			$modificar = "<a href='tablaUsuarios_modificacion.php?id_usuario=$id'>
								<img src='../../assets/icons/editar_azul.png'>
							</a>";

			$eliminar =	"<a href=\"javascript:confirmDelete($id)\">
								<img src='../../assets/icons/eliminar.png'>
							</a>";

		?>


				 <tr>
					<td> <?php echo $id;										?></td>
					<td> <?php echo $nombre;									?></td>
					<td> <?php echo $apellido;									?></td>
					<td> <?php echo $tipoDocumento;								?></td>
					<td> <?php echo $documento;									?></td>
					<td> <?php echo $usuario;									?></td>
					<td> <?php echo $email;										?></td>
					<td> <?php echo $modificar;									?></td>
					<td> <?php echo $eliminar;									?></td>
				 </tr>
				 
		<?php endforeach; ?>
		 
 		<tr>
 			<td colspan="9" class="alta">
 				<a href="tablaZonas_alta.php">
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
                window.location.href = "tablaUsuarios_baja.php?id_usuario=" + id;
            }
        }
    </script>
</body>
</html>
	
