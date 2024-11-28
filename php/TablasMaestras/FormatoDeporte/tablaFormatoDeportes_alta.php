<?php 
    require_once("../../../config/database/conexion.php");
    session_start();

    

    require_once("../../../config/database/db_functions.php");
    $registrosDeporte = obtenerDeportes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Formato Deportes</title>
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

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Alta de Formato Deportes</h1>
    <form action="tablaFormatoDeportes_aplicar_alta.php" method="post">

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="">

        <label for="deporte">deporte:</label>
        <select id="deporte" name="deporte" required>
            <option value="" disabled selected>Seleccione una deporte...</option>
            <?php foreach ($registrosDeporte as $reg) : ?>
                <option value="<?php echo $reg['id_deporte']; ?>">
                    <?php echo $reg['descripcion_deporte'];?>
                </option>
            <?php endforeach; ?>
        </select>


        <button type="submit">Enviar</button>
    </form>

</body>
</html>
