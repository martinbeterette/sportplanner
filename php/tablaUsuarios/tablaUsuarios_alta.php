<?php 
require_once("../../config/database/conexion.php");

$sqlEstado = "SELECT
                    id_estado_zona,
                    descripcion_estado_zona
                FROM
                    estado_zona
                WHERE estado IN (1)";

$sqlComplejo = "SELECT
                    id_complejo,
                    descripcion_complejo
                FROM
                    complejo
                WHERE estado IN (1)";

$sqlServicio = "SELECT
                    id_servicio,
                    descripcion_servicio
                FROM
                    servicio
                WHERE estado IN (1)";

$registrosEstado    = $conexion->query($sqlEstado); 
$registrosComplejo  = $conexion->query($sqlComplejo);
$registrosServicio  = $conexion->query($sqlServicio);
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
        <label for="descripcion">C&oacute;digo:</label>
        <input type="text" id="descripcion" name="descripcion" value="" required>

        <label for="dimension">Dimensión:</label>
        <input type="text" id="dimension" name="dimension" value="">

        <label for="terreno">Terreno:</label>
        <input type="text" id="terreno" name="terreno" value="">

        <label for="tipo_futbol">Tipo de Fútbol:</label>
        <select id="tipo_futbol" name="tipo_futbol" required>
            <option value="" disabled selected>Seleccione una categoria...</option>
            <option value="Futbol 5">Futbol 5</option>
            <option value="Futbol 7">Futbol 7</option>
            <option value="Futbol 11">Futbol 11</option>
        </select>

        <label for="valor">Valor:</label>
        <input type="number" id="valor" name="valor" value="" required>

        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
            <option value="" disabled selected>Seleccione un estado...</option>
            <?php foreach ($registrosEstado as $reg) : ?>
                <option value="<?php echo $reg['id_estado_zona']; ?>">
                    <?php echo $reg['descripcion_estado_zona'];?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="complejo">Complejo:</label>
        <select id="complejo" name="complejo" required>
            <option value="" disabled selected>Seleccione un complejo...</option>
            <?php foreach ($registrosComplejo as $reg) : ?>
                <option value="<?php echo $reg['id_complejo']; ?>">
                    <?php echo $reg['descripcion_complejo'];?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="servicio">Servicio:</label>
        <select id="servicio" name="servicio" required>
            <option value="" disabled selected>Seleccione un servicio...</option>
            <?php foreach ($registrosServicio as $reg) : ?>
                <option value="<?php echo $reg['id_servicio']; ?>">
                    <?php echo $reg['descripcion_servicio'];?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Enviar</button>
    </form>

</body>
</html>
