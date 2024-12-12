<?php 
require_once("../../../config/root_path.php");
require_once(RUTA."config/database/conexion.php");
session_start();
$id_membresia = $_GET['id_membresia'] ?? null;
if(!$id_membresia) {
    header("Location: ../index.php");
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once("aplicar_modificacion.php");
}

$sql = "SELECT * FROM membresia WHERE id_membresia = $id_membresia";
$registros = $conexion->query($sql)->fetch_assoc();

$descuento = $registros['descuento'];
$descripcion =  $registros['descripcion_membresia'];
$precio = $registros['precio_membresia'];
$id_complejo = $registros['rela_complejo'];
   
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
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?id_membresia=$id_membresia" ?>" method="post">

        <label for="beneficio">Descuento: (%)</label>
        <input type="number" id="beneficio" name="descuento"  value="<?php echo isset($descuento) ?  $descuento : '' ?>">>

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo isset($descripcion) ?  $descripcion : '' ?>">

        <label for="descripcion">Precio:</label>
        <input type="number" id="precio" name="precio" value="<?php echo isset($precio) ?  $precio : '' ?>">

        <input type="hidden" name="id_complejo" value="<?php echo $id_complejo; ?>">
        <input type="hidden" name="id_membresia" value="<?php echo $id_membresia; ?>">



        <button type="submit">Enviar</button>
    </form>

</body>
</html>