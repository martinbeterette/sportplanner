<?php 
require_once("../../../config/database/conexion.php");
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #96E072;
            margin: 0;
            padding: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #96E072;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
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
