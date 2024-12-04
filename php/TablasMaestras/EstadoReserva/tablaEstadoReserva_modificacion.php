<?php 
require_once("../../../config/database/conexion.php");
require_once("../../../config/root_path.php");
    session_start();


    $id = $_GET['id_estado_reserva'];

$sql = "SELECT  
                        *                
                    FROM
                        estado_reserva
                    WHERE
                        id_estado_reserva = $id";

$registros = $conexion->query($sql);

foreach ($registros as $reg) {
    $id             = $reg['id_estado_reserva'];
    $descripcion    = $reg['descripcion_estado_reserva'];
}

if (isset($_POST['modificacion'])) {
                $descripcion = $_POST['descripcion'];

    $sql = "UPDATE
                estado_reserva
            SET 
                descripcion_estado_reserva = '$descripcion'
            WHERE
                id_estado_reserva = $id";

    if ($conexion->query($sql)) {
        header("Location: tablaestadoreserva.php");
    }




}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICACION estado reserva</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Modificacion de estado_reserva</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']. '?id_estado_reserva='. $id;?>" method="post" onsubmit="return confirmModification();">

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
