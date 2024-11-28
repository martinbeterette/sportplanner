<?php 
require_once("../../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");


$sqlEstado = "SELECT
                    id_estado_zona,
                    descripcion_estado_zona
                FROM
                    estado_zona
                WHERE estado IN (1)";

$sqlFormatoDeporte = "SELECT 
                            id_formato_deporte,
                            descripcion_formato_deporte
                        FROM 
                            formato_deporte
                        WHERE estado IN(1)";


$registrosEstado    = $conexion->query($sqlEstado); 
$registrosFormatoDeporte    = $conexion->query($sqlFormatoDeporte); 
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALTA DE ZONA</title>
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
    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px;">Modulo de Alta de Zonas</h1>
    <form action="tablaZonas_aplicar_alta.php" method="post">
        <label for="descripcion">Nombre de la Cancha:</label>
        <input type="text" id="descripcion" name="descripcion" value="" required placeholder="ej: cancha 1...">

        <label for="terreno">Terreno:</label>
        <select name="tipo_terreno">
            
        </select>

        <label for="tipo_futbol">Tipo de Deporte:</label>
        <select id="tipo_futbol" name="formato_deporte" required>
            <option value="" disabled selected>Seleccione una categoria...</option>
            <?php foreach ($registrosFormatoDeporte as $reg) : ?>
                <option value="<?php echo $reg['id_formato_deporte']; ?>">  <?php echo $reg['descripcion_formato_deporte']; ?>       </option>
                <option value="<?php echo $reg['id_formato_deporte']; ?>">  <?php echo $reg['descripcion_formato_deporte']; ?>       </option>
                <option value="<?php echo $reg['id_formato_deporte']; ?>">  <?php echo $reg['descripcion_formato_deporte']; ?>       </option>

            <?php endforeach; ?>
        </select>

        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
            <option value="" disabled selected>Seleccione un estado...</option>
            <?php foreach ($registrosEstado as $reg) : ?>
                <option value="<?php echo $reg['id_estado_zona']; ?>">
                    <?php echo $reg['descripcion_estado_zona'];?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="hidden" name="id_sucursal" value="<?php echo $id_sucursal; ?>">

        <button type="submit">Enviar</button>
    </form>

</body>
</html>
