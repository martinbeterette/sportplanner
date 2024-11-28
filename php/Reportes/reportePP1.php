<?php 

require_once('../../config/database/conexion.php');
//Primero verificamos y actualizamos le listado de morosos

//Consultamos para conseguir los registros
$sql = "SELECT reserva.id_reserva, zona.id_zona, reserva.fecha_reserva, horario.horario_inicio, horario.
	horario_fin, estado_reserva.descripcion_estado_reserva, persona.nombre, persona.apellido, persona.dni FROM reserva JOIN horario ON reserva.rela_horario = horario.id_horario JOIN estado_reserva ON estado_reserva.id_estado_reserva = reserva.rela_estado_reserva JOIN zona ON reserva.rela_zona = zona.id_zona JOIN persona ON reserva.rela_persona = persona.id_persona WHERE reserva.fecha_reserva BETWEEN '2023-11-01' AND '2023-11-31' AND estado_reserva.descripcion_estado_reserva LIKE 'cancelado' OR estado_reserva.descripcion_estado_reserva LIKE 'finalizado' ORDER BY(reserva.id_reserva)";

$registros = mysqli_query($conexion,$sql);


 ?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>REPORTE</title>
	<style>
		body {
			background-color: rgba(0, 0, 0, 0.8);
			color: white;
			font-family: arial;
		}

		table {
			border-collapse: collapse;
			color: black;
		}

		table thead {
			background-color:#43FF66;
		}

		table tbody tr:nth-child(odd) {
			background-color: #F1F1F1;
		}

		table tbody tr:nth-child(even) {
			background-color: #CACACA;
		}

		td,th {
			padding: 10px;
		}

	</style>
	<script src="tableToExcel.js"></script>
</head>
<body>
	<p style="color: rgba(255,255,255,0.4)">Fecha Reporte: 29/11/2023</p>
	<h1 align="center">Reporte de Reservas Canceladas y Finalizadas del Mes de Noviembre</h1>

	<input type="button" onclick="tableToExcel('testTable', 'W3C Example Table')" value="Export to Excel" style="margin-left:17vw">
	<table align="center" id="testTable">
		<thead>
			<th>Código Reserva</th>
			<th>Zona</th>
			<th>Fecha de Reserva</th>
			<th>Hora Desde</th>
			<th>Hora Hasta</th>
			<th>Estado</th>
			<th colspan="3">Titula Reserva</th>
		</thead>

		<tbody>
			<?php foreach($registros as $reg) :?>
				<tr>
					<td><?php echo $reg['id_reserva']; 					?></td>
					<td><?php echo $reg['id_zona']; 					?></td>
					<td><?php echo $reg['fecha_reserva']; 				?></td>
					<td><?php echo $reg['horario_inicio']; 				?></td>
					<td><?php echo $reg['horario_fin']; 				?></td>
					<td><?php echo $reg['descripcion_estado_reserva']; 	?></td>
					<td><?php echo $reg['nombre']; 						?></td>
					<td><?php echo $reg['apellido']; 					?></td>
					<td><?php echo $reg['dni']; 						?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php 
		$sql= "SELECT
					COUNT(*) as cantidad,
					estado_reserva.descripcion_estado_reserva
				FROM 
					reserva JOIN horario ON reserva.rela_horario = horario.id_horario JOIN estado_reserva ON estado_reserva.id_estado_reserva = reserva.rela_estado_reserva JOIN zona ON reserva.rela_zona = zona.id_zona JOIN persona ON reserva.rela_persona = persona.id_persona WHERE reserva.fecha_reserva BETWEEN '2023-11-01' AND '2023-11-31' AND estado_reserva.descripcion_estado_reserva LIKE 'cancelado' GROUP BY (estado_reserva.descripcion_estado_reserva)";
		$registros = $conexion->query($sql);
		foreach ($registros as $reg) { 
			$cantidadCanceladas = $reg['cantidad'];
		}

		$sql= "SELECT
					COUNT(*) as cantidad,
					estado_reserva.descripcion_estado_reserva
				FROM 
					reserva JOIN horario ON reserva.rela_horario = horario.id_horario JOIN estado_reserva ON estado_reserva.id_estado_reserva = reserva.rela_estado_reserva JOIN zona ON reserva.rela_zona = zona.id_zona JOIN persona ON reserva.rela_persona = persona.id_persona WHERE reserva.fecha_reserva BETWEEN '2023-11-01' AND '2023-11-31' AND estado_reserva.descripcion_estado_reserva LIKE 'finalizado' GROUP BY (estado_reserva.descripcion_estado_reserva)";
		$registros = $conexion->query($sql);
		foreach ($registros as $reg) { 
			$cantidadFinalizados = $reg['cantidad'];
		}
	?>

	
	<center>
		<p>Reservas Canceladas:<?php echo $cantidadCanceladas; ?></p>
	</center>
	<center>
		<p>Reservas Finalizadas:<?php echo $cantidadFinalizados; ?></p>
	</center>
</body>
</html>