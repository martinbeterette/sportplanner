<?php
require_once("../../../config/root_path.php");
require_once("../../../config/database/conexion.php");
session_start();

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/header.css">
    <link rel="stylesheet" href="../../../css/aside.css">
    <link rel="stylesheet" href="../../../css/footer.css">
    <link rel="stylesheet" href="../css/index.css">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        center {
            margin: 12px auto;
        }

        table {
            width: 90%;
            margin: 0 auto;
        }

        form {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php") ?>

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px;">Modulo Modificacion de tipo_documento</h1>

    <form action="<?php echo $_SERVER['PHP_SELF'] . '?id_tipo_documento=' . $id; ?>" method="post" onsubmit="return confirmModification();">

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>">

        <button type="submit" name="modificacion">Enviar</button>
    </form>

    <?php include(RUTA . "includes/footer.php") ?>

    <script src="../../../libs/jquery-3.7.1.min.js"></script>
    <script src="../../../libs/sweetalert2.all.min.js"></script>
    <script src="../../../js/header.js"></script>
    <script src="../../../js/aside.js"></script>
    <script src="../../../js/terminoscondiciones.js"></script>

    <script>
        function confirmModification() {
            var respuesta = confirm("¿Estás seguro de que deseas aplicar las modificaciones?");
            return respuesta;
        }
    </script>
</body>

</html>