<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    
    require_once("../../../config/database/db_functions.php");
    $registrosPerfil = obtenerPerfiles();
    $registrosModulo = obtenerModulos();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta barrio</title>
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

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Alta de Perfil Modulo</h1>
    <form action="tablaasignacionperfilmodulo_aplicar_alta.php" method="post">

        <label for="modulo">Modulo:</label>
        <select id="modulo" name="modulo" required>
            <option value="" disabled selected>Seleccione una modulo...</option>
            <?php foreach ($registrosModulo as $reg) : ?>
                <option value="<?php echo $reg['id_modulo']; ?>">
                    <?php echo $reg['descripcion_modulo'];?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="perfil">Perfil:</label>
        <select id="perfil" name="perfil" required>
            <option value="" disabled selected>Seleccione una perfil...</option>
            <?php foreach ($registrosPerfil as $reg) : ?>
                <option value="<?php echo $reg['id_perfil']; ?>">
                    <?php echo $reg['descripcion_perfil'];?>
                </option>
            <?php endforeach; ?>
        </select>


        <button type="submit">Enviar</button>
    </form>

</body>
</html>
