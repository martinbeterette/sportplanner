<?php 
require_once('../../config/database/conexion.php');
$id = $_GET['id_usuario'];

$sqlUsuario = "SELECT * FROM usuarios WHERE id_usuario = $id";

$sqlPerfil = "SELECT
					id_perfil,
					descripcion_perfil
				FROM
					perfil
				WHERE estado IN (1)";

$registrosUsuario	= $conexion->query($sqlUsuario); 
$registrosPerfil	= $conexion->query($sqlPerfil);
$datosUsuario = $registrosUsuario->fetch_assoc();

if (isset($_POST['modificacion'])) {
				$perfil = $_POST['perfil'];
	$sql = "UPDATE perfil";
	if ($conexion->query($sql)) {
		header("Location: tablaUsuarios.php");
	}


}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICACION ZONA</title>
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
	<h1 style="text-align: center; margin-top: 25px; margin-bottom: 20px; color: white;">Modulo de Modificacion de Zona</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']. '?id_usuario='. $id;?>" method="post" onsubmit="return confirmModification();">
        



        <label for="perfil">Perfil:</label>
        <select id="perfil" name="perfil" required>
        	<option value="" disabled selected>Seleccione un perfil...</option>
        	<?php foreach ($registrosPerfil as $reg) : ?>
        		<option value="<?php echo $reg['id_perfil']; ?>" <?php if ($datosUsuario['rela_perfil'] == $reg['id_perfil']) {echo 'selected';} ?>>
        			<?php echo $reg['descripcion_perfil'];?>
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
