<?php   
    require_once("../../config/database/conexion.php");
    session_start();

    

    $sql = "SELECT
    			persona.nombre,
    			persona.apellido,
    			documento.descripcion_documento,
    			sexo.descripcion_sexo
    		FROM
    			persona 
    		JOIN 
    			contacto
    		ON
    			contacto.rela_persona = persona.id_persona
            JOIN 
    			documento
    		ON
    			persona.id_persona = documento.rela_persona
    		JOIN
    			sexo
    		ON
    			persona.rela_sexo = sexo.id_sexo
    		WHERE
				contacto.descripcion_contacto LIKE ?
    		";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('s', $_SESSION['email']);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $datos_personales = $resultado->fetch_assoc();

    /*if($datos_personales) {

    	$nombre 	= $datos_personales['nombre'];
    	$apellido 	= $datos_personales['apellido'];
    	$documento 	= $datos_personales['descripcion_documento'];
    	$sexo 		= $datos_personales['descripcion_sexo'];

    }*/

    $nombre = $datos_personales['nombre'];
    $apellido = $datos_personales['apellido'];
    $documento = $datos_personales['descripcion_documento'];
    $sexo = $datos_personales['descripcion_sexo'];
    unset($_SESSION['datos_personales']);
    $_SESSION['datos_personales'] = $datos_personales;


 ?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Datos de Usuario</title>
	<style type="text/css">
		
		body {
			background-color: #F1F1F1;
			padding:0;
			margin:0;
			font-family: arial;
		}

		form {
			background-color: #f7f7f7;
		  	border: 1px solid #ccc;
		  	border-radius: 10px;
		  	box-shadow: 2px 2px 20px #AFAFAF;
		  	margin: 0px auto;
		  	margin-top: 15vh;
		  	margin-bottom: 20px;
		  	padding: 20px;
		  	width: 400px;
		}

		form h1 {
			text-align: center;
		}	

		form .modificar {
			text-align: right;
		}

		form img {
			width: 40px;
		}

		form h3 {
			display: inline-block;
		}

		.back-button {
            background-color: #a8e6cf;
            color: #3d9970;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: inline-block;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background-color: #79b8a0;
            color: white;
        }
	</style>
</head>
<body>
	<a class="back-button" href="../../index_tincho.php">Volver</a>


	<form>
		<h1>Datos de Usuario</h1>

		

		<fieldset>
			<legend align="center">Informacion Personal</legend>

			<div class="modificar">
				<a href="modificar_mis_datos.php?datos_personales"><img src="../../assets/icons/editar_azul.png"></a>
				<br>
			</div>

			<label for="nombre">nombre:</label>
			<h3 name="nombre"><?php echo $nombre; ?></h3>
			<br>

			<label for="apellido">apellido:</label>
			<h3 name="apellido"><?php echo $apellido; ?></h3>
			<br>

			<label for="dni">dni:</label>
			<h3 name="dni"><?php echo $documento; ?></h3>
			<br>

			<label for="sexo">sexo:</label>
			<h3 name="sexo"><?php echo $sexo; ?></h3>
			<br>

		</fieldset>

		<fieldset>
			<legend align="center">Datos del Usuario</legend>

			<div class="modificar">
				<a href="modificar_mis_datos.php?datos_de_usuario"><img src="../../assets/icons/editar_azul.png"></a>
				<br>
			</div>

			<label for="email">Email:</label>
			<h3 name="email"><?php echo $_SESSION['email']; ?></h3>
			<br>

			<label for="usuario">Usuario:</label>
			<h3 name="usuario"><?php echo $_SESSION['usuario']; ?></h3>
			<br>

			<label for="perfil">Perfil:</label>
			<h3 name="perfil"><?php echo $_SESSION['perfil']; ?></h3>
			<br>

		</fieldset>

		<div class="modificar" style="text-align: center; margin: 10px">
			<a href="cambiar_contrasena.php" >Cambiar contrase&ntilde;a</a>
			<br>
		</div>


	</form>


</body>
</html>