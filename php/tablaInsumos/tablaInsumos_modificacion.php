<?php 
require_once("../../config/database/conexion.php");
$id = $_GET['id_insumo'];

$sqlEstado = "SELECT
                    id_estado_insumo,
                    descripcion_estado_insumo
                FROM
                    estado_insumo
                WHERE 
                    estado IN (1)";

$registrosEstado    = $conexion->query($sqlEstado); 

$sql = "SELECT  
                        *                
                    FROM
                        insumo
                    WHERE
                        id_insumo = $id";

$registros = $conexion->query($sql);
foreach ($registros as $reg) {
    $id             = $reg['id_insumo'];
    $descripcion    = $reg['descripcion_insumo'];
    $cantidad       = $reg['cantidad'];
    $fechaAlta      = $reg['fecha_alta'];
    $estado         = $reg['rela_estado_insumo'];
    $categoria      = $reg['categoria'];
}

if (isset($_POST['modificacion'])) {
                $descripcion = $_POST['descripcion'];
                $cantidad = $_POST['cantidad'];
                $estado = $_POST['estado'];
                $categoria = $_POST['categoria'];
    $sql = "UPDATE
                insumo
            SET 
                descripcion_insumo = '$descripcion',
                cantidad = $cantidad,
                rela_estado_insumo = $estado,
                categoria = '$categoria'
            WHERE
                id_insumo = $id";

    if ($conexion->query($sql)) {
        header("Location: tablaInsumos.php");
    }




}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICACION INSUMO</title>
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

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Modificacion de Insumo</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']. '?id_insumo='. $id;?>" method="post" onsubmit="return confirmModification();">

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>">

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" value="<?php echo $cantidad; ?>" required>

        <label for="categoria">Categoria:</label>
        <select name="categoria">
            <option value=""disabled selected>Seleccione una Categoria...</option>
            <option value="Articulos Deportivos">Articulos Deportivos</option>
            <option value="Articulos Parrilla o Barbacoa">Articulos Parrilla o Barbacoa</option>
            <option value="Electronicos">Electronicos</option>
            <option value="Electrodomesticos">Electrodomesticos</option>
        </select>

        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
            <option value="" disabled selected>Seleccione un estado...</option>

            <?php foreach ($registrosEstado as $reg) : ?>

                <option value="<?php echo $reg['id_estado_insumo']; ?>" <?php if ($estado == $reg['id_estado_insumo']) { echo 'selected';} ?>>
                    <?php echo $reg['descripcion_estado_insumo'];?>
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
