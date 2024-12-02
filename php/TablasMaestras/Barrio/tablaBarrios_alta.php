<?php 
    require_once("../../../config/database/conexion.php");
    session_start();

    
    require_once("../../../config/database/db_functions.php");
    $registrosLocalidad = obtenerLocalidades();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta barrio</title>
    <style>
       body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F0F4F8;
            margin: 0;
            padding: 20px;
        }

        form {
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            margin: 0 auto;
            font-size: 16px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #4A90E2;
            box-shadow: 0 0 8px rgba(74, 144, 226, 0.2);
            outline: none;
        }

        button {
            background-color: #4A90E2;
            color: #FFFFFF;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #357ABD;
            transform: scale(1.05);
        }

        button:active {
            transform: scale(0.95);
        }

    </style>
</head>
<body>

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Alta de barrios</h1>
    <form action="tablabarrios_aplicar_alta.php" method="post">

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="">

        <label for="localidad">localidad:</label>
        <select id="localidad" name="localidad" required>
            <option value="" disabled selected>Seleccione una localidad...</option>
            <?php foreach ($registrosLocalidad as $reg) : ?>
                <option value="<?php echo $reg['id_localidad']; ?>">
                    <?php echo $reg['descripcion_localidad'];?>
                </option>
            <?php endforeach; ?>
        </select>


        <button type="submit">Enviar</button>
    </form>

</body>
</html>
