<?php 
require_once("../../../config/database/conexion.php");
require_once("../../../config/root_path.php");
$id = $_GET['id_tipo_contacto'];

$sql = "SELECT  
                        *                
                    FROM
                        tipo_contacto
                    WHERE
                        id_tipo_contacto = $id";

$registros = $conexion->query($sql);

foreach ($registros as $reg) {
    $id             = $reg['id_tipo_contacto'];
    $descripcion    = $reg['descripcion_tipo_contacto'];
}

if (isset($_POST['modificacion'])) {
                $descripcion = $_POST['descripcion'];

    $sql = "UPDATE
                tipo_contacto
            SET 
                descripcion_tipo_contacto = '$descripcion'
            WHERE
                id_tipo_contacto = $id";

    if ($conexion->query($sql)) {
        header("Location: tablatipocontactos.php");
    }




}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICACION tipo_contacto</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Modificacion de tipo_contacto</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']. '?id_tipo_contacto='. $id;?>" method="post" onsubmit="return confirmModification();">

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
