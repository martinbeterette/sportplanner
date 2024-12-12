<?php 
require_once("../../../config/root_path.php");
require_once(RUTA."config/database/conexion.php");
session_start();
$id_complejo = $_GET['id_complejo'] ?? null;
if(!$id_complejo) {
    header("Location: ../index.php");
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once("insertar_membresia.php");
}
   
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

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; ">Modulo Alta de Membresia</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?id_complejo=$id_complejo" ?>" method="post">

        <label for="beneficio">Descuento: (%)</label>
        <input type="number" id="beneficio" name="descuento">

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion">

        <label for="descripcion">Precio:</label>
        <input type="number" id="precio" name="precio">

        <input type="hidden" name="id_complejo" value="<?php echo $id_complejo; ?>">


        <button type="submit">Enviar</button>
    </form>

</body>
</html>