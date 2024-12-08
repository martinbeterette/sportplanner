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
<h1>Tablas Maestras</h1>
<div class="container">
    <?php
    $tablas_maestras = [
        ["nombre" => "sexos",           "ruta" => "../TablasMaestras/sexo/tablaSexos.php"],
        ["nombre" => "estado control",  "ruta" => "../TablasMaestras/EstadoControl/tablaEstadoControl.php"],
        ["nombre" => "estado reserva",  "ruta" => "../TablasMaestras/EstadoReserva/tablaEstadoReserva.php"],
        ["nombre" => "estado zona",     "ruta" => "../TablasMaestras/estadozona"],
        ["nombre" => "tipo documento",  "ruta" => "../TablasMaestras/TipoDocumento/tabla_tipo_documentos.php"],
        ["nombre" => "provincia",       "ruta" => "../TablasMaestras/Provincia/tablaProvincias.php"],
        ["nombre" => "localidad",       "ruta" => "../TablasMaestras/Localidad/tablaLocalidades.php"],
        ["nombre" => "barrio",          "ruta" => "../TablasMaestras/Barrio/tablaBarrios.php"],
        ["nombre" => "tipo contacto",   "ruta" => "../TablasMaestras/TipoContacto/tablaTipoContactos.php"],
        ["nombre" => "deporte",         "ruta" => "../TablasMaestras/Deportes/tablaDeportes.php"],
        ["nombre" => "formato deporte", "ruta" => "../TablasMaestras/FormatoDeporte/tablaFormatoDeportes.php"],
        ["nombre" => "tipo terreno",    "ruta" => "../TablasMaestras/TipoTerreno/tablaTipoTerrenos.php"],
        ["nombre" => "perfil",          "ruta" => "../TablasMaestras/Perfiles/tablaPerfil.php"],
        ["nombre" => "servicio",        "ruta" => "../TablasMaestras/Servicio/tablaServicios.php"]
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
