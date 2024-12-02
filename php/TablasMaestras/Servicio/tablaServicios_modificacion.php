<?php 
    require_once("../../../config/database/conexion.php");
    require_once("../../../config/root_path.php");
    session_start();

    

$id = $_GET['id_servicio'];

$sql = "SELECT  
                        *                
                    FROM
                        servicio
                    WHERE
                        id_servicio = $id";

$registros = $conexion->query($sql);

foreach ($registros as $reg) {
    $id             = $reg['id_servicio'];
    $descripcion    = $reg['descripcion_servicio'];
}

if (isset($_POST['modificacion'])) {
                $descripcion = $_POST['descripcion'];

    $sql = "UPDATE
                servicio
            SET 
                descripcion_servicio = '$descripcion'
            WHERE
                id_servicio = $id";

    if ($conexion->query($sql)) {
        header("Location: tablaservicios.php");
    }




}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICACION servicio</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Modificacion de servicios</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']. '?id_servicio='. $id;?>" method="post" onsubmit="return confirmModification();">

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
