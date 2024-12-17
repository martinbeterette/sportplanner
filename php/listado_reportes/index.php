<?php
session_start();
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: " . BASE_URL);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablas Maestras</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/footer.css" ?>">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php"); ?>

    <h1>reportes</h1>
    <div class="container">
        <?php
        $tablas_maestras = [
            ["nombre" => "Reporte de complejo", "ruta" => "../Reportes/propietario/dashboard.php"],
            ["nombre" => "Reporte Socio",                    "ruta" => "../Reportes/socio/index.php"],
            ["nombre" => "Estado Reserva",      "ruta" => "../Reportes/reporte/dashboardSucursal.php?id_sucursal=1"],

        ];

        foreach ($tablas_maestras as $tabla) {
            echo "<div class='card' onclick='window.location.href=`" . $tabla['ruta'] . "`'>";
            echo "<a href='" . $tabla['ruta'] . "'>" . ucfirst($tabla['nombre']) . "</a>"; // Puedes reemplazar '#' con tus links
            echo "</div>";
        }
        ?>
    </div>

    <?php include(RUTA . "includes/footer.php") ?>

    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/terminoscondiciones.js" ?>"></script>
</body>

</html>