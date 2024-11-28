<?php
// Conexión a la base de datos (asegúrate de cambiar los valores según tu configuración)
session_start();
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Recibimos los parámetros del formulario (asegúrate de que los nombres coincidan con los del select en el form)
$deporte 		= isset($_GET['deporte']) 		? $_GET['deporte'] 		: null;
$tipo_deporte 	= isset($_GET['tipoDeporte']) 	? $_GET['tipoDeporte'] 	: null;
// $superficie 	= isset($_GET['superficie']) 	? $_GET['superficie'] 	: null;
$fecha 			= isset($_GET['fecha'])			? $_GET['fecha'] 		: null;
$horario 		= isset($_GET['horario']) 		? $_GET['horario'] 		: null;


//obtenemos los horarios
	$sqlHorario = "SELECT horario_inicio, horario_fin 
					FROM horario 
					WHERE id_horario = {$horario}";
	$registros = $conexion->query($sqlHorario);
	$registrosHorario = $registros->fetch_assoc();

//obtenemos los datos personales
	$id_persona = $_SESSION['id_persona'];
	$usuario    = $_SESSION['usuario'];
	$sqlPersona = "SELECT nombre, apellido, descripcion_documento
					FROM persona
					JOIN documento ON rela_persona = id_persona
					WHERE id_persona = {$id_persona}";
	$registros = $conexion->query($sqlPersona);
	foreach ($registros as $reg) {
		$nombre 	= $reg['nombre'];
		$apellido 	= $reg['apellido'];
		$documento  = $reg['descripcion_documento'];
	}

// Empezamos a armar la consulta base
	$sql = "SELECT 
	                    zona.id_zona,
	                    zona.descripcion_zona,
	                    sucursal.descripcion_sucursal,
	                    reserva.fecha_reserva,
	                    IF (reserva.id_reserva IS NULL, 'disponible', 'no-disponible') AS estado
	                FROM 
	                    zona
	                JOIN 
	                    sucursal ON zona.rela_sucursal = sucursal.id_sucursal
	                LEFT JOIN 
	                    reserva ON reserva.rela_zona = zona.id_zona 
	                              AND reserva.fecha_reserva = '$fecha' 
	                              AND reserva.rela_horario = $horario 
	                WHERE
	                    
	                    rela_formato_deporte = $tipo_deporte
	                    and id_reserva is null

	                ORDER BY 
	                    (zona.id_zona);";
			  
//harcode
	$tarifa_seleccionada['precio'] = 10000;	                    

if ($registros = $conexion->query($sql)) {

} else {
	die("error durante la consulta");
}


// Cerramos la conexión
$conexion->close();
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		* {
			font-family: arial;
		}
		.contenido  {
		  position: relative;
		  height: 90%;
		  width: 100%;
		  display: flex;
		  justify-content: center;
		  align-items: center;
		}

		.container {
		  background-color: rgb(240, 255, 255, 0.5);
		  position: relative;
		  height: 90%;
		  width: 60%;
		  display: flex;
		  flex-direction: column;
		  justify-content: center;
		  align-items: center;
		  border-radius: 10px;
		  padding-top:10px;
		  padding-bottom:10px;
		}

		.container input {
		  width: 50%;
		}

		.cancha {
		  background-color: lightgray;
		  display: flex;
		  align-items: center;
		  gap: 30px;
		  width: 75%;
		  padding: 10px;
		  border: 1.5px solid grey;
		  border-radius: 10px;
		  cursor: pointer;
		  transition: all 0.5s ease;
		}

		.cancha:not(:first-child) {
		  margin-top: 10px;
		}

		.cancha:hover {
		  transform: scale(1.05);
		}

		.cancha picture,
		.cancha h2 {
		  pointer-events: none;
		}

		picture img {
		  height: 30px;
		  width: 30px;
		  border-radius: 50%;
		}

		.reserva{
        	text-align: left;
        }

        ul {
        	list-style: none;
        }
	</style>
</head>
<body>
	<div class="canchas">
		
		<?php foreach ($registros as $reg) :?>
			<div 
				class="cancha" 
				id="<?php echo $reg['id_zona'];?>" 
				horario="<?php echo $horario; ?>"
				fecha="<?php echo $fecha; ?>"
				descripcion="<?php echo $reg['descripcion_zona']; ?>"
			>
				<h2><?php echo $reg['id_zona'] . $reg['descripcion_zona']; ?></h2>

			</div>
		<?php endforeach; ?>
	</div>

	<script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js"; ?>"></script>
	<script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
	<script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>
	<script>
		$(document).ready(function () {
			let contenidoModal = `
			        <h2>¿Quiere reservar la hora?</h2>
			        <div class="reserva">
			            <ul>
			                <li>Hora de inicio: <?php echo $registrosHorario['horario_inicio']; ?></li>
			                <li>Hora de Fin: <?php echo $registrosHorario['horario_fin']; ?></li>
			                <li>Fecha de reserva: <?php echo $fecha; ?></li>
			                <li>Nombre de Usuario: <?php echo $usuario; ?></li>
			                <li>Titular: <?php echo $nombre . " - " . $apellido . " - " . $documento; ?></li>
			                <li>Precio: <?php echo $tarifa_seleccionada['precio']; ?></li>
			                <br>
			                <li>Ingresar Pago: <input type="text" name="monto_pagado"></li>
			            </ul>
			        </div>
			    `;
			$('.cancha').on('click', function () {
				let id_zona = $(this).attr('id');
				let horario = $(this).attr('horario');//aqui va el horario
				let fecha 	= $(this).attr('fecha');//aqui va la hora AMBOS ATTR DEBERIAN IR EN EL DIV
		        Swal.fire({
		            html: contenidoModal, // Aquí colocamos el contenido que hemos generado
		            showCancelButton: true,
		            confirmButtonText: 'Confirmar',
		            cancelButtonText: 'Cancelar',
		            focusConfirm: false,
		            preConfirm: () => {
		                let montoPagado = document.querySelector('input[name="monto_pagado"]').value;
		                if (!montoPagado) {
		                    Swal.showValidationMessage('Por favor ingresa el monto pagado');
		                }
		                return { montoPagado: montoPagado };
		            }
		        }).then((result) => {
		            if (result.isConfirmed) {
		                // Aquí puedes procesar el monto pagado o cualquier otra acción que necesites
		                console.log('Monto pagado:', result.value.montoPagado);
		            }
		        });

			}); // onclick cancha


		});
	</script>
</body>
</html>
