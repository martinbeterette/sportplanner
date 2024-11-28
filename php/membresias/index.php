<?php  
    session_start();
    require_once("../../config/root_path.php");
    require_once(RUTA."config/database/conexion.php");
    $id_complejo = $_GET['id_complejo'];

    //AUMENTO MASIVO (SI EXISTE ENVIO DEL FORM)
    require_once("includes/aumento_masivo.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Membresías</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="<?php echo BASE_URL. "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL. "css/aside.css" ?>">
</head>
<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php"); ?>
    <div class="container">

        <form id="form-actualizar-precios" method="post">
            <label for="porcentaje">Aumento en porcentaje:(%)</label>
            <input type="number" id="porcentaje" name="porcentaje" min="1" placeholder="Ingrese el porcentaje" required>
            <button 
                type="submit" 
                id="btn-actualizar-precios" 
                name="btn-actualizar-precios"
            >Aplicar Aumento Masivo</button>
        </form>
        
        <h1 align="center">Listado de Membresías</h1>

        <!-- Filtro de búsqueda -->
        <input type="text" id="buscador" placeholder="Buscar por descripción o descuento" />

        <!-- Contenedor de la tabla -->
        <div id="tabla-container"></div>

        <!-- Contenedor de la paginación -->
        <div id="paginacion-container"></div>
        <button id="btn-agregar" onclick="agregarMembresia()">Agregar Membresía</button>
    </div>

    <script>
        var id_complejo = <?php echo $id_complejo ?>;
    </script>
    <script src="js/tablaYPaginado.js"></script>

</body>
</html>
