<?php  

session_start();
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if(!isset($_SESSION['id_usuario'])){
    header("Location: " . BASE_URL);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablas Maestras</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include(RUTA . "includes/header.php"); ?>

<?php include(RUTA . "includes/menu_aside.php"); ?>
<h1>reportes</h1>
<div class="container">
    <?php
    $tablas_maestras = [
        ["nombre" => "Reporte de complejo", "ruta" => "../TablasMaestras/sexo/tablaSexos.php"],
        ["nombre" => "",                    "ruta" => "../TablasMaestras/EstadoControl/tablaEstadoControl.php"],
        ["nombre" => "estado reserva",      "ruta" => "../TablasMaestras/EstadoReserva/tablaEstadoReserva.php"],
        
    ];

    foreach ($tablas_maestras as $tabla) {
        echo "<div class='card' onclick='window.location.href=`". $tabla['ruta'] ."`'>";
        echo "<a href='". $tabla['ruta'] ."'>" . ucfirst($tabla['nombre']) . "</a>"; // Puedes reemplazar '#' con tus links
        echo "</div>";
    }
    ?>
</div>
<script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
<script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
<script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
</body>
</html>
