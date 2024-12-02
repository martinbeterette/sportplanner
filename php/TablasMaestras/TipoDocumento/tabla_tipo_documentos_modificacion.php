<?php 
require_once("../../../config/database/conexion.php");
require_once("../../../config/root_path.php");
$id = $_GET['id_tipo_documento'];

$sql = "SELECT  
                        *                
                    FROM
                        tipo_documento
                    WHERE
                        id_tipo_documento = $id";

$registros = $conexion->query($sql);

foreach ($registros as $reg) {
    $id             = $reg['id_tipo_documento'];
    $descripcion    = $reg['descripcion_tipo_documento'];
}

if (isset($_POST['modificacion'])) {
                $descripcion = $_POST['descripcion'];

    $sql = "UPDATE
                tipo_documento
            SET 
                descripcion_tipo_documento = '$descripcion'
            WHERE
                id_tipo_documento = $id";

    if ($conexion->query($sql)) {
        header("Location: tabla_tipo_documentos.php");
    }




}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICACION tipo_documento</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Modificacion de tipo_documento</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']. '?id_tipo_documento='. $id;?>" method="post" onsubmit="return confirmModification();">

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
