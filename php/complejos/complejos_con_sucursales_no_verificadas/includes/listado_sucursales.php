<?php  
session_start();
require_once("../../../../config/root_path.php");
require_once(RUTA."config/database/conexion.php");
$id_complejo = $_GET['id_complejo'] ?? null;
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL. "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL. "css/aside.css" ?>">
    <link rel="stylesheet" href="../css/listado_complejos.css">
	<title>Sucursales no verificadas</title>
</head>
<body>
	<?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php"); ?>
    <div class="container">
        <h2>Gestión de Complejos No Verificados</h2>
        <input type="text" id="filtro" placeholder="Buscar complejos...">
        <div id="tabla-container"></div>
        <div id="paginacion-container"></div>
    </div>
    <script src="<?php echo BASE_URL. "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL. "libs/sweetalert2.all.min.js" ?>"></script>
    <script>let id_complejo = <?php echo $id_complejo; ?></script>
    <script src="../js/tabla_y_paginado_sucursales.js"></script> 
    <script src="../js/ver_mas_sucursal.js"></script>
</body>
</html>