<?php
session_start();
// Supongamos que $conexion es tu conexión a la base de datos
require_once("../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");
$id_sucursal = isset($_GET['id_sucursal']) ? filter_input(INPUT_GET, 'id_sucursal',FILTER_SANITIZE_NUMBER_INT) : die("falta GET de sucursal");
$consulta_reservas = 
    "SELECT id_reserva, rela_persona, rela_zona, rela_estado_reserva, monto_pagado, monto_total FROM RESERVA 
        JOIN zona on zona.id_zona = reserva.rela_zona
        JOIN sucursal ON zona.rela_sucursal = sucursal.id_sucursal
        WHERE id_sucursal = {$id_sucursal}
    ";
$reservas_hechas = $conexion->query($consulta_reservas);
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Reservas</title>
    <style>
        @import url(/../../css/header.css);
        @import url(/../../css/aside.css);

            /* Estilos para el contenedor principal de la tabla */
        .tabla-reservas {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #f4f6f7;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        /* Estilos para los encabezados de la tabla */
        .tabla-reservas th {
            background-color: #3085d6; /* Color azul similar al de SweetAlert */
            color: white;
            text-align: left;
            padding: 15px;
            font-size: 18px;
        }

        /* Estilos para las celdas de la tabla */
        .tabla-reservas td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
            color: #333;
        }

        /* Estilos para las filas alternas */
        .tabla-reservas tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Estilos para el botón "Marcar" */
        .btn-marcar {
            background-color: #3085d6; /* Mismo azul que SweetAlert */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        /* Hover para el botón */
        .btn-marcar:hover {
            background-color: #2874c8;
        }

        /* Estilos para el contenedor principal de la página */
        .contenedor-listado {
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: 40px auto;
        }

        /* Título de la página */
        .titulo-listado {
            font-size: 24px;
            color: #444;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside/menu_aside_beterette.css'; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css' ?>">
    <script src="https://kit.fontawesome.com/03cc0c0d2a.js" crossorigin="anonymous"></script>

</head>
<body>

    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>

    <div class="contenedor-listado">

        <h1 class="titulo-listado">Listado de Reservas</h1>
        <table class="tabla-reservas">
            <thead>
                <tr>
                    <th>ID Reserva</th>
                    <th>Persona</th>
                    <th>Zona</th>
                    <th>Estado Reserva</th>
                    <th>Monto Pagado</th>
                    <th>Monto Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $reservas_hechas->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $fila['id_reserva'] ?></td>
                        <td><?= $fila['rela_persona'] ?></td>
                        <td><?= $fila['rela_zona'] ?></td>
                        <td><?= $fila['rela_estado_reserva'] ?></td>
                        <td><?= $fila['monto_pagado'] ?></td>
                        <td><?= $fila['monto_total'] ?></td>
                        <td>
                            <a href="verificar_pago.php?id_reserva=<?= $fila['id_reserva'] ?>" class="btn-marcar">Marcar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>

    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>

</body>
</html>