<?php 
require_once("../../../config/database/conexion.php");
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
