<?php 
session_start();
require_once('../../../config/root_path.php');
require_once(RUTA . 'config/database/conexion.php');
require_once(RUTA . 'config/database/db_functions/zonas.php');
require_once(RUTA . 'config/database/db_functions/personas.php');
$id_horario     = $_GET['id_horario']; 
$fecha          = $_GET['fecha_reserva'];
$id_usuario     = $_SESSION['id_usuario'];
$usuario        = $_SESSION['usuario'];
$cancha         = $_GET['cancha'];

$registrosZona = $conexion->query("SELECT * FROM zona WHERE id_zona = $cancha")->fetch_assoc();

$registros = obtenerHorario($id_horario);

if($reg = $registros->fetch_assoc()) {
	$horario_inicio = $reg['horario_inicio'];
	$horario_fin    = $reg['horario_fin'];
}


$registros = obtenerPersonaPorUsuario($id_usuario);

if($reg = $registros->fetch_assoc()) {
    $id_persona = $reg['id_persona'];
    $nombre = $reg['nombre'];
    $apellido = $reg['apellido'];
    $documento = $reg['descripcion_documento'];
}



// Obtener la hora actual en formato 24 horas
$hora_actual = date('H:i:s');

// ID de la sucursal (supongamos que viene de una petición GET o de una variable)
$id_sucursal = $registrosZona['rela_sucursal'] ?? null;

// Consulta para obtener las tarifas de la sucursal
$sql = "SELECT * FROM tarifa WHERE rela_sucursal = ? AND estado = 1 ORDER BY hora_inicio";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_sucursal);
$stmt->execute();
$result_tarifa = $stmt->get_result();

// Variable para guardar la tarifa seleccionada
$tarifa_seleccionada = null;

// while ($fila = $result_tarifa->fetch_assoc()) {
//     $hora_desde = $fila['hora_inicio'];
//     $hora_hasta = $fila['hora_fin'];
    
//     // Comparar si la hora actual está en el rango
//     if ($hora_desde <= $hora_hasta) {
//         // Caso donde el rango es del mismo día (ejemplo 06:00 - 12:00)
//         if ($hora_actual >= $hora_desde && $hora_actual <= $hora_hasta) {
//             $tarifa_seleccionada = $fila;
//             break;
//         }
//     } else {
//         // Caso donde el rango cruza la medianoche (ejemplo 20:00 - 05:00)
//         if ($hora_actual >= $hora_desde || $hora_actual <= $hora_hasta) {
//             $tarifa_seleccionada = $fila;
//             break;
//         }
//     }
// }

// // Mostrar la tarifa seleccionada
// if ($tarifa_seleccionada) {
//     echo "Tarifa: " . $tarifa_seleccionada['descripcion_tarifa'] . "<br>";
//     echo "Precio: $" . $tarifa_seleccionada['precio'] . "<br>";
//     echo $hora_actual;
// } else {
//     echo "No se encontró una tarifa para la hora actual.";
// }
    

$queryPrecio = "
        SELECT 
        s.descripcion_sucursal,
        z.descripcion_zona AS nombre_cancha,
        t.descripcion_tarifa,
        cast(t.precio as unsigned) AS precio_base,
        CASE 
            WHEN so.id_socio IS NOT NULL THEN 
                t.precio * (1 - (m.descuento / 100))
            ELSE 
                t.precio
        END AS precio_final
    FROM 
        zona z
    JOIN 
        sucursal s ON s.id_sucursal = z.rela_sucursal AND id_zona = $cancha
    JOIN 
        complejo c ON c.id_complejo = s.rela_complejo
    JOIN 
        tarifa t ON t.rela_sucursal = s.id_sucursal 
    LEFT JOIN 
        socio so ON so.rela_complejo = c.id_complejo AND so.rela_persona = $id_persona
    LEFT JOIN 
        membresia m ON m.id_membresia = so.rela_membresia
    WHERE 
        z.estado = 1
        AND (
            (t.hora_inicio < t.hora_fin AND '$horario_inicio' BETWEEN t.hora_inicio AND t.hora_fin)
            OR 
            (t.hora_inicio > t.hora_fin AND 
                ('$horario_inicio' >= t.hora_inicio OR '$horario_inicio' <= t.hora_fin)
            )
        )

";
$precios = $conexion->query($queryPrecio)->fetch_assoc();

$precio_base = $precios['precio_base'];
$precio_final = $precios['precio_final'];







?>
<html>
<head>
    <title>Formulario de Reserva</title>
    <style>
        @import url(../../../css/header.css);
        @import url(../../../css/aside.css);
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            color: #333;
        }
        form {
            width: 350px;
            height: 300px;
            margin: 0 auto;
            margin-top: 10vh;
            text-align: center;
            background-color: lightgray;
            border-radius: 15px;
            border: 1px solid darkgray;
        }

        div{
        	text-align: left;
        }

        ul {
        	list-style: none;
        }
        
        input[type="hidden"] {
            display: none; /* Input oculto */
        }

        button[type="submit"], button[type="button"]{
            padding: 10px 20px;
            margin: 10px;
            border: none;
            cursor: pointer;
        }

        button[name="aceptar"] {
            background-color: #0074D9; /* Azul */
            color: #fff;
        }

        button[name="cancelar"] {
            background-color: #FF4136; /* Rojo */
            color: #fff;
        }
    </style>
</head>
<body>
    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>

    <form action="formularioReserva4.php" method="post">
    	<h2>¿Quiere reservar la hora?</h2>
    	<div>
    		<ul>
    			<li>Hora de inicio: <?php echo $horario_inicio; ?></li>
    			<li>Hora de Fin: <?php echo $horario_fin; ?></li>
    			<li>Fecha de reserva: <?php echo $fecha; ?></li>
                <li>Nombre de Usuario <?php echo $usuario; ?></li>
                <li>Titular: <?php echo $nombre. " - " .$apellido. " - " . $documento?></li>
                <li>
                    Precio: 
                    <?php 
                        if ($precio_base) {
                            echo $precio_base;
                        } else {
                            echo 'No se encontro una tarifa';
                        }
                    ?>
                        
                </li>
                <?php if ($precio_base !== $precio_final) :?>
                    <li>precio final: <?php echo $precio_final?></li>
                <?php endif; ?>
                <br>
                <li>Ingresar Pago: <input type="text" name="monto_pagado"></li>
    		</ul>
    	</div>
        <input type="hidden" name="id_horario" value="<?php echo $id_horario; ?>">
        <input type="hidden" name="fecha" value="<?php echo $fecha; ?>">
        <input type="hidden" name="persona" value="<?php echo $id_persona; ?>">
        <input type="hidden" name="cancha" value="<?php echo $cancha; ?>">
        <input type="hidden" name="monto_total" value="<?php if ($precio_final){echo $precio_final;}; ?>">

        <button type="submit" name="aceptar" id="submit">Aceptar</button>
        <button type="button" name="cancelar" id="cancelar">Cancelar</button>
    </form>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        let boton_cancelar = $('#cancelar');
        boton_cancelar.on('click', function() { 
            location.href='formularioReserva1.php?';
        });
    });

</script>
<script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
<script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>
</html>

<?php 
    
?>