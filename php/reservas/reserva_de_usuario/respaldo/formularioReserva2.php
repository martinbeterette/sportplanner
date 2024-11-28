<?php 
    session_start();
    require_once('../../../config/root_path.php');
    require_once(RUTA . "config/database/db_functions/zonas.php");


    $fecha   = $_GET['fecha_reserva'];
    $cancha  = $_GET['cancha'];

    // Calcula las fechas anteriores y siguientes
    $fecha_anterior = date('Y-m-d', strtotime($fecha . ' -1 day'));
    $fecha_siguiente = date('Y-m-d', strtotime($fecha . ' +1 day'));
    // echo $fecha_anterior."<br>".$fecha_siguiente; die;



    $registros = ObtenerHorariosDisponibles($cancha, $fecha);
?>

 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<title>RESERVA</title>
 	<style>
        @import url(../../../css/header.css);
        @import url(../../../css/aside.css);

 		body {
 			padding:0;
 			margin:0;
 			font-family: arial;
 			background-color: rgba(0, 0, 0, 0.0);
 		}

        .navigation {
            margin: auto;
            width: 95%;
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #f7f7f7;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navigation a, a.volver {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .navigation a:hover, a.volver:hover {
            background-color: #0056b3;
        }

 		table {
 			margin: auto;
 			margin-top: 10vh;
 			
 		}

 		.disponible{
 			background-color: #6EFF6B;
 			color: white;
 			padding: 10px;
            transition: color 0.3s;
 		}

 		.no-disponible {
 			background-color: #FF4500;
 			color: white;
 			padding: 10px;
 		}

        a {
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s;
        }

        .disponible:hover {
            background-color: #3FFF3B;
        }

 	</style>
 </head>
 <body>
    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>


    <a href="<?php echo BASE_URL. 'index_tincho.php'; ?>" class="volver">Volver al inicio</a>
    <div class="navigation">
        <a href="formularioReserva2.php?fecha_reserva=<?php echo $fecha_anterior; ?>&cancha=<?php echo $cancha; ?>">Anterior</a>

        <a href="formularioReserva2.php?fecha_reserva=<?php echo $fecha_siguiente; ?>&cancha=<?php echo $cancha; ?>">Siguiente</a>
    </div>

    <table class="horarios">
        <tbody>
            
            <?php
                $index = 0;
                foreach ($registros as $reg) {

                    if ($index % 6 == 0) {
                        if ($index != 0) {
                            echo '</tr>';
                        }
                        echo '<tr>';
                    }

                    $horario = substr($reg['horario_inicio'], 0, 2);
                    $estado = $reg['estado'];
                    $id_horario = $reg['id_horario'];
                    ?>
                    <td class="<?php echo $estado; ?>" id-hora="<?php echo $id_horario; ?>">
                        <?php echo htmlspecialchars($horario) ?>
                    </td>
                    <?php
                    $index++;
                }

                if ($index % 6 != 0) {
                    echo '</tr>';
                }
            ?>

        </tbody>
    </table>
 	
</body>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script>
    let fecha       = <?php echo json_encode($fecha); ?>;
    let id_usuario  = <?php echo json_encode($_SESSION['id_usuario']); ?>;
    let cancha      = <?php echo json_encode($cancha); ?>;
</script>
<script src="<?php echo BASE_URL.'js/horarios_disponibles.js'; ?>"></script>
<script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
<script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>

 </html>