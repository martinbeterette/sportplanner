<?php 
    require_once("../../config/root_path.php");
    $conexion = new mysqli("localhost","root","","proyecto_pp2");


    // Procesar formulario
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idPerfil = $_POST['perfil'];
        $modulosSeleccionados = isset($_POST['modulos']) ? $_POST['modulos'] : [];

        // Borrar las profesiones anteriores de la persona seleccionada
        $sqlDelete = "DELETE FROM asignacion_perfil_modulo WHERE rela_perfil = ?";
        $stmtDelete = $conexion->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $idPerfil);
        $stmtDelete->execute();

        // Insertar las nuevas profesiones seleccionadas
        $sqlInsert = "INSERT INTO asignacion_perfil_modulo (rela_perfil, rela_modulo) VALUES (?, ?)";
        $stmtInsert = $conexion->prepare($sqlInsert);
        $stmtInsert->bind_param("ii", $idPerfil, $idModulo);

        foreach ($modulosSeleccionados as $idModulo) {
            $stmtInsert->execute();
        }

    }



    $sql = "SELECT id_modulo,descripcion_modulo FROM modulo";
    $resultado = $conexion->query($sql);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHECKBOX</title>
    <style type="text/css">
        body {
            /*display: flex;*/
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f0f9;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        form {
            margin: auto;
            margin-top: 15vh;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            color: #5e4a8f;
        }
        label {
            display: block;
            margin-top: 15px;
            color: #5e4a8f;
        }
        .perfilXerror {
            margin-top: 5px;
        }
        .inputs {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .inputs:focus {
            border-color: #5e4a8f;
        }
        #modulos div {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        #modulos input[type="checkbox"] {
            margin-right: 10px;
            accent-color: pink;
        }

        .boton-submit {
            margin-top: 20px;
        }
        button {
            background-color: #5e4a8f;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4a3773;
        }
    </style>
</head>

<body>
    <a href="<?php echo BASE_URL. 'index_tincho.php'; ?>" class="volver">Volver al inicio</a>
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <h2>Formulario de Inserci&oacute;n</h2>
        <!-- Otros campos del formulario -->

        <label for="perfil">perfil:</label>
        <div class="perfilXerror">
            <select name="perfil" id="perfil" class="inputs">
            </select>
        </div>

        <label for="modulos">modulos:</label>
        <div id="modulos">
            <!-- Aquí se agregarán los checkboxes de modulos -->
            <?php foreach ($resultado as $reg) :?>
                <div>
                    <input type="checkbox" name="modulos[]" value="<?php echo $reg['id_modulo']; ?>">
                    <?php echo $reg['descripcion_modulo'];?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="boton-submit">
            <button type="submit" id="botonSubmit" name="boton-submit">Submit</button>
        </div>
    </form>

    <div id="resultado-ajax"></div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="validaciones.js"></script>
</body>
</html>
<?php $conexion->close(); ?>
