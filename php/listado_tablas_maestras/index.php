<?php  

session_start();
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablas Maestras</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9; /* Casi blanco */
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background-color: #e8f5e9; /* Verde clarito */
            border: 1px solid #c8e6c9; /* Borde verde */
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            width: 200px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .card a {
            text-decoration: none;
            color: #388e3c; /* Verde oscuro */
            font-weight: bold;
        }

        .card a:hover {
            color: #1b5e20; /* Más oscuro aún */
        }

        h1 {
            text-align: center;
            color: #388e3c; /* Verde oscuro */
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php include(RUTA . "includes/header.php"); ?>

<?php include(RUTA . "includes/menu_aside.php"); ?>
<h1>Tablas Maestras</h1>
<div class="container">
    <?php
    $tablas_maestras = [
        "sexos", "estado control", "estado reserva", "estado zona", "tipo documento",
        "provincia", "localidad", "barrio", "tipo contacto", "deporte",
        "formato deporte", "tipo terreno", "perfil", "servicio"
    ];

    foreach ($tablas_maestras as $tabla) {
        echo "<div class='card'>";
        echo "<a href='#'>" . ucfirst($tabla) . "</a>"; // Puedes reemplazar '#' con tus links
        echo "</div>";
    }
    ?>
</div>

</body>
</html>
