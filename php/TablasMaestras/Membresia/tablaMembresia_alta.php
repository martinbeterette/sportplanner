<?php 
require_once("../../../config/database/conexion.php");
require_once("../../../config/root_path.php");
    session_start();

   
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Membresia</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Alta de Membresia</h1>
    <form action="tablaMembresia_aplicar_alta.php" method="post">

        <label for="beneficio">Beneficio:</label>
        <input type="text" id="beneficio" name="beneficio">

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion">


        <button type="submit">Enviar</button>
    </form>

</body>
</html>
