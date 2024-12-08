<?php 
require_once("../../../config/database/conexion.php");
require_once("../../../config/root_path.php");
    session_start();

   

require_once("../../../config/database/db_functions.php");
$registrosProvincia = obtenerProvincias();
$id = $_GET['id_localidad'];

$sql = "SELECT  
                        *                
                    FROM
                        localidad
                    WHERE
                        id_localidad = $id";

$registros = $conexion->query($sql);

foreach ($registros as $reg) {
    $id             = $reg['id_localidad'];
    $descripcion    = $reg['descripcion_localidad'];
    $provincia      = $reg['rela_provincia'];
}

if (isset($_POST['modificacion'])) {
                $descripcion = $_POST['descripcion'];
                $provincia   = $_POST['provincia'];

    $sql = "UPDATE
                localidad
            SET 
                descripcion_localidad = '$descripcion',
                rela_provincia        = $provincia
            WHERE
                id_localidad = $id";

    if ($conexion->query($sql)) {
        header("Location: tablalocalidades.php");
    }




}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICACION localidad</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Modificacion de localidad</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']. '?id_localidad='. $id;?>" method="post" onsubmit="return confirmModification();">

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>">

        <label for ="provincia">Provincia:</label>
        <select id="provincia" name="provincia" required>
            <option value="" disabled selected>Seleccione una Provincia...</option>
            <?php foreach ($registrosProvincia as $reg) : ?>
                <option value="<?php echo $reg['id_provincia']; ?>" <?php if ($provincia == $reg['id_provincia']) {echo 'selected';} ?>>
                    <?php echo $reg['descripcion_provincia'];?>
                </option>
            <?php endforeach; ?>
        </select>


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
