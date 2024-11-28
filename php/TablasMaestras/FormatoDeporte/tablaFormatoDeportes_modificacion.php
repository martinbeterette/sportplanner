<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
require_once("../../../config/database/db_functions.php");
$registrosDeporte = obtenerDeportes();
$id = $_GET['id_formato_deporte'];

$sql = "SELECT  
                        *                
                    FROM
                        formato_deporte
                    WHERE
                        id_formato_deporte = $id";

$registros = $conexion->query($sql);

foreach ($registros as $reg) {
    $id             = $reg['id_formato_deporte'];
    $descripcion    = $reg['descripcion_formato_deporte'];
    $deporte      = $reg['rela_deporte'];
}

if (isset($_POST['modificacion'])) {
                $descripcion = $_POST['descripcion'];
                $deporte   = $_POST['deporte'];

    $sql = "UPDATE
                formato_deporte
            SET 
                descripcion_formato_deporte = '$descripcion',
                rela_deporte        = $deporte
            WHERE
                id_formato_deporte = $id";

    if ($conexion->query($sql)) {
        header("Location: tablaformatodeportes.php");
    }




}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICACION formato deporte</title>
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

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Modificacion de formato deporte</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']. '?id_formato_deporte='. $id;?>" method="post" onsubmit="return confirmModification();">

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>">

        <label for ="deporte">deporte:</label>
        <select id="deporte" name="deporte" required>
            <option value="" disabled selected>Seleccione una deporte...</option>
            <?php foreach ($registrosDeporte as $reg) : ?>
                <option value="<?php echo $reg['id_deporte']; ?>" <?php if ($deporte == $reg['id_deporte']) {echo 'selected';} ?>>
                    <?php echo $reg['descripcion_deporte'];?>
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
