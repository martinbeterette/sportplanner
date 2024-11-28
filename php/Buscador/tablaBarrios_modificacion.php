<?php 
require_once("../../../config/database/conexion.php");
    session_start();

    if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_perfil'])) {
        header("Location: ../../../error403.php");
        exit();
    }

    $modulo = "Domicilios";

    $sql_acceso = "SELECT COUNT(*) AS tiene_acceso
                    FROM 
                        asignacion_perfil_modulo asp
                    JOIN 
                        perfil p 
                    ON 
                        asp.rela_perfil = p.id_perfil
                    JOIN 
                        modulo m ON asp.rela_modulo = m.id_modulo
                    WHERE 
                        p.descripcion_perfil 
                    LIKE 
                        '{$_SESSION['perfil']}' 
                    AND 
                        m.descripcion_modulo 
                    LIKE 
                        '{$modulo}'";

    $resultado = $conexion->query($sql_acceso);

    if ($reg = $resultado->fetch_assoc()) {
        if ($reg['tiene_acceso'] == 0) {
            header("Location: ../../../error403.php");
            exit();
        }
    }
    
    require_once("../../../config/database/db_functions.php");
$registrosLocalidad = obtenerLocalidades();
$id = $_GET['id_barrio'];

$sql = "SELECT  
                        *                
                    FROM
                        barrio
                    WHERE
                        id_barrio = $id";

$registros = $conexion->query($sql);

foreach ($registros as $reg) {
    $id             = $reg['id_barrio'];
    $descripcion    = $reg['descripcion_barrio'];
    $localidad      = $reg['rela_localidad'];
}

if (isset($_POST['modificacion'])) {
                $descripcion = $_POST['descripcion'];
                $localidad   = $_POST['localidad'];

    $sql = "UPDATE
                barrio
            SET 
                descripcion_barrio = '$descripcion',
                rela_localidad        = $localidad
            WHERE
                id_barrio = $id";

    if ($conexion->query($sql)) {
        header("Location: tablaBarrios.php");
    }




}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICACION formato localidad</title>
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

    <h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo Modificacion de formato localidad</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']. '?id_barrio='. $id;?>" method="post" onsubmit="return confirmModification();">

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>">

        <label for ="localidad">localidad:</label>
        <select id="localidad" name="localidad" required>
            <option value="" disabled selected>Seleccione una localidad...</option>
            <?php foreach ($registrosLocalidad as $reg) : ?>
                <option value="<?php echo $reg['id_localidad']; ?>" <?php if ($localidad == $reg['id_localidad']) {echo 'selected';} ?>>
                    <?php echo $reg['descripcion_localidad'];?>
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
