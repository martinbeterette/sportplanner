<?php 
require_once("../../../config/database/conexion.php");
    session_start();

   

$id = $_GET['id_deporte'];

$sql = "SELECT  
                        *                
                    FROM
                        deporte
                    WHERE
                        id_deporte = $id";

$registros = $conexion->query($sql);

foreach ($registros as $reg) {
    $id             = $reg['id_deporte'];
    $descripcion    = $reg['descripcion_deporte'];
}

if (isset($_POST['modificacion'])) {
                $descripcion = $_POST['descripcion'];

    $sql = "UPDATE
                deporte
            SET 
                descripcion_deporte = '$descripcion'
            WHERE
                id_deporte = $id";

    if ($conexion->query($sql)) {
        header("Location: tablaDeportes.php");
    }




}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICACION deporte</title>
    <link rel="stylesheet" href="css/index.php">
</head>
<body>

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Modificacion de deporte</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']. '?id_deporte='. $id;?>" method="post" onsubmit="return confirmModification();">

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>">

        <button type="submit" name="modificacion">Enviar</button>
    </form>

    <script>
        function confirmModification() {
            var respuesta = confirm("¿Estás seguro de que deseas aplicar las modificaciones?");
            return respuesta;
        }
    </script>
</body>
</html>
