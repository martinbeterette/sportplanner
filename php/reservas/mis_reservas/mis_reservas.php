<?php  
	session_start();
	require_once('../../../config/root_path.php');
	require_once(RUTA .'config/database/conexion.php');
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mis reservas</title>
	<link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css"; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css"; ?>">
    <link rel="stylesheet" href="css/mis_reservas.css">
</head>
<body>

	<?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php"); ?>
    <div class="container">
        
    	<h1>Mis reservas</h1>
        <!-- buscador -->
        <input type="text" id="buscador" placeholder="Buscar...">

        <!-- tabla -->
        <div id="tabla-container"></div>
        <div id="paginacion-container"></div>
    	
    </div>

    <!-- scripts -->

    <script src="<?php echo BASE_URL; ?>libs/jquery-3.7.1.min.js"></script>
    <script src="<?php echo BASE_URL; ?>libs/sweetalert2.all.min.js"></script>
    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>
    <script src="js/eliminar.js"></script>
    <script>let id_persona = <?php echo json_encode($_SESSION['id_persona']); ?>;</script>
    <script src="js/paginado_buscador.js"></script>
    <script src="js/ver_mas.js"></script>


</body>
</html>