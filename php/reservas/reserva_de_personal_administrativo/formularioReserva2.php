<?php
session_start();
require_once('../../../config/root_path.php');
require_once(RUTA . "config/database/db_functions/zonas.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: " . BASE_URL . "login/inicio_sesion.php");
}

$fecha   = $_GET['fecha_reserva'] ?? date('Y-m-d');
$cancha  = $_GET['cancha'] ?? header("Location: formularioReserva1.php");
$persona = $_GET['persona'] ?? header("Location: formularioReserva1.php");
if (empty($_GET['cancha']) || empty($_GET['fecha_reserva'])) {
    header("Location: formularioReserva1.php");
    exit; // Asegura que el resto del script no se ejecute
}
// Calcula las fechas anteriores y siguientes
$fecha_anterior = date('Y-m-d', strtotime($fecha . ' -1 day'));
$fecha_siguiente = date('Y-m-d', strtotime($fecha . ' +1 day'));
$hoy = date('Y-m-d'); // Fecha actual
if ($fecha < $hoy) {
    $fecha = $hoy;
}
$limite_fecha = date('Y-m-d', strtotime("+7 days")); // Fecha límite de 7 días hacia el futuro
if (($fecha < $hoy) || ($fecha > $limite_fecha)) {
    $fecha = date('Y-m-d');
}
$sql = "
        SELECT 
            horario.id_horario,
            horario.horario_inicio,
            horario.horario_fin,
            reserva.id_reserva,
            reserva.rela_estado_reserva,
            zona.descripcion_zona,
            sucursal.descripcion_sucursal,
            complejo.descripcion_complejo,
            -- Estado basado en reservas
            CASE 
                WHEN reserva.id_reserva IS NOT NULL AND reserva.rela_estado_reserva = 1 THEN 'no-reservable'
                ELSE 'reservable'
            END AS estado_reserva,
            -- Estado basado en itinerario
            CASE 
                WHEN itinerario.horario_desde IS NULL THEN 'fuera-itinerario'
                WHEN horario.horario_inicio < itinerario.horario_desde 
                  OR horario.horario_fin > itinerario.horario_hasta THEN 'fuera-itinerario'
                ELSE 'dentro-itinerario'
            END AS estado_itinerario
        FROM 
            horario
        -- Join con reservas
        LEFT JOIN 
            reserva 
        ON 
            horario.id_horario = reserva.rela_horario 
        AND 
            reserva.fecha_reserva = '$fecha'
        -- Join con zona
        LEFT JOIN 
            zona 
        ON 
            zona.id_zona = $cancha
        -- Join con sucursal
        LEFT JOIN 
            sucursal 
        ON 
            zona.rela_sucursal = sucursal.id_sucursal
        -- Join con complejo
        LEFT JOIN 
            complejo 
        ON 
            sucursal.rela_complejo = complejo.id_complejo
        -- Join con itinerario
        LEFT JOIN 
            itinerario 
        ON 
            itinerario.rela_sucursal = sucursal.id_sucursal 
        AND 
            itinerario.rela_dia = DAYOFWEEK('$fecha') - 1
        GROUP BY(horario.horario_inicio)
        ORDER BY 
            horario.horario_inicio;

    ";

$registros = $conexion->query($sql);
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESERVA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/footer.css" ?>">
    <link rel="stylesheet" href="css/reserva2.css">
</head>

<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php") ?>

    <!-- Flechita de breadcrumb -->
    <div class="breadcrumb">
        <a href="formularioReserva1.php" class="back-arrow">
            &#8592; Volver
        </a>
    </div>

    <div class="navigation">
        <!-- Botón "Anterior" -->
        <?php if (isset($fecha_anterior) && $fecha_anterior >= $hoy) { ?>
            <a href="formularioReserva2.php?fecha_reserva=<?php echo $fecha_anterior; ?>&cancha=<?php echo $cancha; ?>&persona=<?php echo $persona; ?>">Fecha anterior</a>
        <?php } else { ?>
            <span class="boton-placeholder"> </span>
        <?php } ?>

        <!-- Botón "Siguiente" -->
        <?php if (isset($fecha_siguiente) && $fecha_siguiente < $limite_fecha) { ?>
            <a href="formularioReserva2.php?fecha_reserva=<?php echo $fecha_siguiente; ?>&cancha=<?php echo $cancha; ?>&persona=<?php echo $persona; ?>">Fecha siguiente</a>
        <?php } else { ?>
            <span class="boton-placeholder"> </span>
        <?php } ?>
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

                $clase = (
                    $reg['estado_reserva'] == 'reservable' &&
                    $reg['estado_itinerario'] == 'dentro-itinerario'
                )
                    ? 'disponible'
                    : 'no-disponible';

                $horario = substr($reg['horario_inicio'], 0, 5);
                $estado_reserva = $reg['estado_reserva'];
                $estado_itinerario = $reg['estado_itinerario'];
                $id_horario = $reg['id_horario'];

                // Combina los estados para aplicar clases CSS adecuadas
                $clases = "$estado_reserva $estado_itinerario";
            ?>
                <td class="<?php echo $clase; ?>" id-hora="<?php echo htmlspecialchars($id_horario); ?>">
                    <?php echo htmlspecialchars($horario); ?>
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

    <?php include(RUTA . "includes/footer.php"); ?>

    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/terminoscondiciones.js" ?>"></script>

    <script>
        let fecha = <?php echo json_encode($fecha); ?>;
        let persona = <?php echo json_encode($persona); ?>;
        let cancha = <?php echo json_encode($cancha); ?>;
    </script>

    <script src="js/horarios_disponibles.js"></script>
</body>

</html>