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
$persona     = $_GET['id_persona'];


$registrosZona = $conexion->query("SELECT * FROM zona WHERE id_zona = $cancha")->fetch_assoc();
$registros = obtenerHorario($id_horario);

if ($reg = $registros->fetch_assoc()) {
    $horario_inicio = $reg['horario_inicio'];
    $horario_fin    = $reg['horario_fin'];
}
$sql_persona = "
        SELECT id_persona, nombre, apellido, descripcion_documento
        FROM persona JOIN documento ON persona.id_persona = documento.rela_persona
        WHERE id_persona = $persona
    ";

$registros = $conexion->query($sql_persona);
if ($reg = $registros->fetch_assoc()) {
    $persona = $reg['id_persona'];
    $nombre = $reg['nombre'];
    $apellido = $reg['apellido'];
    $documento = $reg['descripcion_documento'];
}

$queryPrecio = "
        SELECT 
                s.descripcion_sucursal,
                z.descripcion_zona AS nombre_cancha,
                t.id_tarifa,
                t.descripcion_tarifa,
                CAST(t.precio AS UNSIGNED) AS precio_base,
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
                socio so ON so.rela_complejo = c.id_complejo AND so.rela_persona = $persona
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

$id_tarifa   = $precios['id_tarifa'];
$precio_base = $precios['precio_base'];
$precio_final = $precios['precio_final'] ?? '-';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Formulario de Reserva</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/footer.css" ?>">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        /* Estilos generales para el contenedor del formulario */
        form#reserva-form {
            width: 400px;
            margin: 10vh auto;
            /* Centrado vertical y horizontal */
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Sombra sutil */
            font-family: Arial, sans-serif;
            position: relative;
        }

        /* Flecha hacia atrás (breadcrumb) */
        form#reserva-form .breadcrumb {
            display: flex;
            align-items: center;
            font-size: 14px;
            margin-bottom: 15px;
            color: #0074D9;
            cursor: pointer;
            text-decoration: none;
        }

        form#reserva-form .breadcrumb i {
            margin-right: 8px;
            font-size: 16px;
        }

        form#reserva-form .breadcrumb:hover {
            text-decoration: underline;
        }

        /* Título del formulario */
        form#reserva-form h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }

        /* Lista de información */
        form#reserva-form ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        form#reserva-form ul li {
            margin: 10px 0;
            font-size: 14px;
            color: #555;
        }

        /* Botones */
        form#reserva-form .form-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        form#reserva-form button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            width: 48%;
            transition: background-color 0.3s;
        }

        /* Botón aceptar */
        form#reserva-form button[name="aceptar"] {
            background-color: #28a745;
            /* Verde */
            color: #fff;
        }

        form#reserva-form button[name="aceptar"]:hover {
            background-color: #218838;
        }

        /* Botón cancelar */
        form#reserva-form button[name="cancelar"] {
            background-color: #dc3545;
            /* Rojo */
            color: #fff;
        }

        form#reserva-form button[name="cancelar"]:hover {
            background-color: #c82333;
        }



        /* Estilo para la flechita de breadcrumb */
        .breadcrumb {
            margin: 20px 0;
            padding-left: 20px;
            /* Ajusta según necesites */
        }

        .back-arrow {
            font-size: 20px;
            color: #007bff;
            /* Color azul para la flecha */
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .back-arrow:hover {
            color: #0056b3;
            /* Color más oscuro al pasar el mouse */
        }

        /* Estilo opcional para la flecha */
        .back-arrow::before {
            content: "\2190";
            /* Código Unicode para la flecha hacia la izquierda */
            margin-right: 5px;
            font-size: 24px;
            /* Tamaño de la flecha */
        }
    </style>
</head>

<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php") ?>

    <div>
        <!-- Flechita de breadcrumb -->
        <div class="breadcrumb">
            <a href="formularioReserva2.php?cancha=<?php echo $cancha ? $cancha : null; ?>&fecha_reserva=<?php echo $fecha ? $fecha : null; ?>" class="back-arrow">
                &#8592; Volver
            </a>
        </div>

        <form id="reserva-form" action="formularioReserva4.php" method="post">

            <h2>¿Quiere reservar la hora?</h2>
            <div>
                <ul>
                    <li>Hora de inicio: <?php echo $horario_inicio; ?></li>
                    <li>Hora de Fin: <?php echo $horario_fin; ?></li>
                    <li>Fecha de reserva: <?php echo $fecha; ?></li>
                    <li>Titular: <?php echo $nombre . " - " . $apellido . " - " . $documento; ?></li>
                    <li>
                        Precio:
                        <?php echo $precio_base ? $precio_base : 'No se encontró una tarifa'; ?>
                    </li>
                    <?php if ($precio_base !== $precio_final): ?>
                        <li>Precio final: <?php echo $precio_final; ?></li>
                    <?php endif; ?>
                </ul>
            </div>
            <input type="hidden" name="id_horario" value="<?php echo $id_horario; ?>">
            <input type="hidden" name="fecha" value="<?php echo $fecha; ?>">
            <input type="hidden" name="persona" value="<?php echo $persona; ?>">
            <input type="hidden" name="cancha" value="<?php echo $cancha; ?>">
            <input type="hidden" name="monto_base" value="<?php echo $precio_base ?? '' ?>">
            <input type="hidden" name="monto_final" value="<?php echo $precio_final ?? ''; ?>">
            <input type="hidden" name="id_tarifa" value="<?php echo $id_tarifa ?? ''; ?>">

            <!-- Botones -->
            <div class="form-buttons">
                <button type="submit" name="aceptar">Aceptar</button>
                <button type="button" name="cancelar" id="cancelar">Cancelar</button>
            </div>
        </form>
    </div>

    <?php include(RUTA . "includes/footer.php"); ?>

    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/terminoscondiciones.js" ?>"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#cancelar').on('click', function() {
                location.href = 'formularioReserva1.php?';
            });
        });
    </script>
</body>

</html>