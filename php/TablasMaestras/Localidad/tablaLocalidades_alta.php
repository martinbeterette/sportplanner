<?php 
require_once("../../../config/database/conexion.php");
require_once("../../../config/root_path.php");
    session_start();

    
    
    require_once("../../../config/database/db_functions.php");
    $registrosProvincia = obtenerProvincias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Localidade</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Alta de Localidades</h1>
    <form action="tablaLocalidades_aplicar_alta.php" method="post">

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="">

        <label for="provincia">Provincia:</label>
        <select id="provincia" name="provincia" required>
            <option value="" disabled selected>Seleccione una provincia...</option>
            <?php foreach ($registrosProvincia as $reg) : ?>
                <option value="<?php echo $reg['id_provincia']; ?>">
                    <?php echo $reg['descripcion_provincia'];?>
                </option>
            <?php endforeach; ?>
        </select>


        <button type="submit">Enviar</button>
    </form>

</body>
</html>
