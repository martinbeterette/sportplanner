<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
session_start();
$id_complejo = $_GET['id_complejo'] ?? null;
if (!$id_complejo) {
    header("Location: ../index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once("insertar_membresia.php");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Membresia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/footer.css" ?>">
    <style>
        .containermembresia {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease;
            margin: auto;
        }

        .containermembresia:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Estilos para el encabezado */
        h1 {
            color: #4a90e2;
            font-size: 24px;
            margin-bottom: 20px;
            transition: color 0.3s ease;
        }

        h1:hover {
            color: #2c6eb2;
        }

        /* Estilos de los formularios */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 14px;
            color: #555;
            font-weight: 600;
        }

        input[type="number"],
        input[type="text"] {
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease, transform 0.3s ease;
        }

        input[type="number"]:focus,
        input[type="text"]:focus {
            border-color: #4a90e2;
            transform: scale(1.05);
        }

        input[type="number"]:hover,
        input[type="text"]:hover {
            border-color: #4a90e2;
        }

        /* Estilo para el botón */
        button {
            background-color: #4a90e2;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #357ab7;
            transform: translateY(-3px);
        }

        button:active {
            transform: translateY(1px);
        }
    </style>
</head>

<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php"); ?>

    <div class="containermembresia">
        <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; ">Modulo Alta de Membresia</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?id_complejo=$id_complejo" ?>" method="post">

            <label for="beneficio">Descuento: (%)</label>
            <input type="number" id="beneficio" name="descuento">

            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion">

            <label for="descripcion">Precio: $</label>
            <input type="number" id="precio" name="precio">

            <input type="hidden" name="id_complejo" value="<?php echo $id_complejo; ?>">

            <button type="submit">Enviar</button>
        </form>
    </div>

    <?php include(RUTA . "includes/footer.php") ?>

    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/terminoscondiciones.js" ?>"></script>
</body>

</html>