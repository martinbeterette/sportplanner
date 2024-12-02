<?php  
$id_sucursal = $_GET['id_sucursal'] ?? die("falta get de sucursal");
require_once("../../../config/root_path.php");
require_once(RUTA."config/database/conexion.php");
session_start();

function obtenerSexos() {
	global $conexion;
	$sql = "SELECT * FROM sexo";
	$registros = $conexion->query($sql);
	return $registros;
}

function obtenerTipoDocumentos() {
	global $conexion;

	$sql = "SELECT * FROM tipo_documento";
	$registros = $conexion->query($sql);
	return $registros;
}


$sexos = obtenerSexos();
$tipoDocumentos = obtenerTipoDocumentos();
$errores = isset($_GET['errores']) ? $_GET['errores'] : [];

if(!empty($errores)){
	echo "se han detectado errores MOSTAR MENSAJITO";
}
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="css/formulario_creacion_empleado.css">
	<link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css"; ?>">
	<link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css"; ?>">
</head>
<body>
	<div>
	<?php include(RUTA."includes/header.php"); ?>
	<?php include(RUTA."includes/menu_aside.php"); ?>
		<div>
			<form id="altaEmpleadoForm" action="includes/crear_empleado.php" method="POST">
			    <input type="hidden" name="id_sucursal" value="<?php echo $id_sucursal; ?>">
			    <!-- Paso 1: Datos de la cuenta -->
			    <div id="paso-1">
			   		<h3>Alta de Empleado - Paso 1</h3>

			        <label for="correo">Correo:</label>
			        <input type="email" id="correo" name="correo" value="<?php echo $_GET['correo'] ?? '' ?>">
			        <span id="correoStatus"></span>

			        <label for="username">Usuario:</label>
			        <input type="text" id="username" name="username" value="<?php echo $_GET['username'] ?? '' ?>">
			        <span id="usuarioStatus"></span>

			        
			        <label for="password">Contraseña:</label>
			        <input type="password" id="password" name="password" value="<?php echo $_GET['password'] ?? '' ?>">
			        <span id="passwordStatus"></span>

			        
			        <label for="confirm-password">Confirmar Contraseña:</label>
			        <input type="password" id="confirm-password" name="confirm-password" value="<?php echo $_GET['confirm-password'] ?? '' ?>">
			        <span id="confirm-passwordStatus"></span>
			        
			        <div class="botones">
			            <button type="button" id="btn-siguiente" class="siguiente" name="paso1">Siguiente</button>
			        </div>
			    </div>
			    
			    <!-- Paso 2: Datos personales -->
			    <div id="paso-2" style="display: none;">
			        <h3>Alta de Empleado - Paso 2</h3>
			        
			        <label for="nombre">Nombre:</label>
			        <input type="text" id="nombre" name="nombre" >
			        <span id="nombreStatus"></span>

			        <label for="apellido">Apellido:</label>
			        <input type="text" id="apellido" name="apellido" >
			        <span id="apellidoStatus"></span>

			        <label for="documento">Documento:</label>
			        <input type="text" id="documento" name="documento" >
			        <span id="documentoStatus"></span>

			        <label for="tipo_documento">Tipo de Documento:</label>
			        <select id="tipo_documento" name="tipo_documento" >
			            <?php foreach ($tipoDocumentos as $reg) : ?>
			            	<option value="<?php echo $reg['id_tipo_documento']; ?>">
			            		<?php echo $reg['descripcion_tipo_documento']; ?>
			            	</option>
			            <?php endforeach; ?>
			        </select>
			        <span id="tipo_documentoStatus"></span>
			        
			        <label for="sexo">Sexo:</label>
			        <select id="sexo" name="sexo" >
			            <?php foreach ($sexos as $reg) : ?>
			            	<option value="<?php echo $reg['id_sexo']; ?>">
			            		<?php echo $reg['descripcion_sexo']; ?>
			            	</option>
			            <?php endforeach; ?>
			        </select>
			        <span id="sexoStatus"></span>
			        
			        <label for="domicilio">Domicilio:</label>
			        <input type="text" id="domicilio" name="domicilio" >
			        <span id="domicilioStatus"></span>
			        
			        <div class="botones">
			            <button type="button" id="btn-volver" class="siguiente">Volver</button>
			            <button type="submit" id="btn-finalizar" class="siguiente" name="paso2">Finalizar</button>
			        </div>
			    </div>
			</form>
			
		</div>
	</div>
    <script src="<?php echo BASE_URL; ?>libs/sweetalert2.all.min.js"></script>
    <script src="<?php echo BASE_URL; ?>libs/jquery-3.7.1.min.js"></script>
    <script src="js/verificar_correo.js"></script>
    <script src="js/pasos_formulario_wizard.js"></script>
    <script src="js/validaciones.js"></script>
    <script src="<?php echo BASE_URL. "js/aside.js" ?>"></script>
    <script src="<?php echo BASE_URL. "js/header.js" ?>"></script>

</body>
</html>

