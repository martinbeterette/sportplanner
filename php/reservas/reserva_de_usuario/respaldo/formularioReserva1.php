<?php
    session_start();
    require_once("../../../config/root_path.php");
    $ruta = RUTA;
    require_once($ruta . "config/database/db_functions/zonas.php");
    $registrosCancha = obtenerZonas();
?>
<html>
<head>
    <title>Buscar Reservas</title>
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
            background-color: lightgray;
            border: 2px solid darkgray;
            padding: 20px;
            width: 300px;
            margin: 0 auto;
            margin-top:10vh;
            text-align: center;
            border-radius: 15px;
        }
        input[type="date"] {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid darkgray;
        }
        button[type="submit"] {
            background-color: #0074D9; /* Azul */
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>

    <a href="<?php echo BASE_URL. 'index_tincho.php'; ?>" class="volver">Volver al inicio</a>
    <h1 style="text-align: center; margin-top: 25px;">Modulo de Busqueda de Reservas</h1>
    <h2 align="center"><?php echo "Hola ". $_SESSION['usuario']. " tu ID: ". $_SESSION['id_usuario']; ?></h2>
    <form action="formularioReserva2.php" method="get">
        <h2>Buscar reservas por cancha y fecha</h2>
        <input type="date" name="fecha_reserva" required>
        <select name="cancha" id="" required>
            <option value="" disabled selected>Eliga Cancha</option>
            <?php foreach ($registrosCancha as $reg) :?>
                <option value="<?php echo $reg['id_zona']; ?> ">
                    <?php echo $reg['descripcion_zona']. ' - '.$reg['descripcion_sucursal']. ' - ' .$reg['descripcion_complejo'];?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Buscar</button>


    </form>
    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>
</body>
</html>
