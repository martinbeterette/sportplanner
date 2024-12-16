<?php
session_start();  
require_once("../../../../config/root_path.php");
require_once("../probando.php");
if($conexion) {
	echo "todo correcto";
}
$id_complejo = $_GET['id_complejo'] ?? false;



?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
	<link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
	<link rel="stylesheet" href="../css/formulario_modificacion.css">
</head>
<body>
	<?php include(RUTA. "includes/header.php"); ?>
	<?php include(RUTA. "includes/menu_aside.php"); ?>

	<div class="container">
        <div class="header">
            <h1>Modificar Sucursal</h1>
        </div>

        <!-- Formulario de modificación -->
        <div class="form-container">
            <h2>Modificar los detalles de la sucursal</h2>
            <form action="aplicar_agregar.php" method="POST" id="formulario">
                <input type="hidden" name="id_complejo" value="<?php echo $id_complejo; ?>">

                <!-- Descripción de la sucursal -->
                <div class="form-group">
                    <label for="descripcion_sucursal">Descripción de la Sucursal</label>
                    <input type="text" id="descripcion_sucursal" name="descripcion_sucursal"  required>
                </div>

                <!-- Dirección -->
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion"  required>
                </div>

                <!-- Barrio -->
                <div class="form-group">
                    <label for="barrio">Barrio</label>
                    <select id="barrio" name="barrio" required>
                       
                    </select>
                </div>

                <!-- Localidad -->
                <div class="form-group">
                    <label for="localidad">Localidad</label>
                    <select id="localidad" name="localidad" required>
                      
                    </select>
                </div>

                <!-- Provincia -->
                <div class="form-group">
                    <label for="provincia">Provincia</label>
                    <select id="provincia" name="provincia" required>

                    </select>
                </div>

                <!-- Fecha de creación -->
                <div class="form-group">
                    <label for="fecha_de_creacion">Fecha de Creación</label>
                    <input type="date" id="fecha_de_creacion" name="fecha_de_creacion">
                </div>

                <!-- Botón de submit -->
                <div class="form-btn-container">
                    <button type="submit" class="form-btn">Modificar Sucursal</button>
                </div>
            </form>
        </div>
    </div>

    <script> 
    	let provincia_seleccionada; 
    	let localidad_seleccionada;
    	let barrio_seleccionado;
    </script>
	<script src="<?php echo BASE_URL		. "libs/jquery-3.7.1.min.js" ?>"></script>
	<script src="<?php echo BASE_URL	 	. "js/header.js" ?>"></script>
	<script src="<?php echo BASE_URL 		. "js/aside.js" ?>"></script>
	<script src="../js/obtener_domicilio.js"></script>
</body>
</html>