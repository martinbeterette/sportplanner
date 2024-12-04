<?php 
require_once("../../../config/database/conexion.php");
    session_start();

   

$id = $_GET['id_estado_control'];

$sql = "SELECT  
                        *                
                    FROM
                        estado_control
                    WHERE
                        id_estado_control = $id";

$registros = $conexion->query($sql);

foreach ($registros as $reg) {
    $id             = $reg['id_estado_control'];
    $descripcion    = $reg['descripcion_estado_control'];
}

if (isset($_POST['modificacion'])) {
                $descripcion = $_POST['descripcion'];

    $sql = "UPDATE
                estado_control
            SET 
                descripcion_estado_control = '$descripcion'
            WHERE
                id_estado_control = $id";

    if ($conexion->query($sql)) {
        header("Location: tablaestadocontrol.php");
    }




}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICACION estado_control</title>
    <link rel="stylesheet" href="../css/index.css">

</head>
<body>

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Modificacion de estado_control</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']. '?id_estado_control='. $id;?>" method="post" onsubmit="return confirmModification();">

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
