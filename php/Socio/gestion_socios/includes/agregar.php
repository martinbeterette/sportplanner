<?php
session_start();
require_once("../../../../config/root_path.php");
require_once("functions.php");
require_once(RUTA . "config/database/conexion.php");

$id_persona = $_SESSION['id_persona'] ?? 0;
$id_usuario = $_SESSION['id_usuario'] ?? 0;

$id_complejo = obtenerIdComplejoDelEmpleado($id_persona,$id_usuario);
$membresias_resultado = $conexion->query("SELECT * FROM membresia WHERE rela_complejo = $id_complejo");

$tipo_documento_resultado = $conexion->query("SELECT * FROM tipo_documento");

$sexo_resultado = $conexion->query("SELECT * FROM sexo");

$sexos = [];
foreach($sexo_resultado as $reg) {
    $sexos[] = $reg;
}

$tipos_documento = [];
foreach ($tipo_documento_resultado as $reg) {
    $tipos_documento[] = $reg;
}

$membresias = [];
foreach ($membresias_resultado as $reg) {
	$membresias[] = $reg;
}

if(!$id_complejo) {
    header("Location: " . BASE_URL . "errors/error403.php?no_tiene_acceso");
}
echo $id_complejo;

?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css' ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside.css' ?>">
	<link rel="stylesheet" href="../css/agregar.css">
</head>
<body>
<?php include(RUTA . "includes/header.php"); ?>
<?php include(RUTA . "includes/menu_aside.php"); ?>

<div class="container">
	<div class="title"><h2>Alta de socio</h2></div>
    <div class="flex-container">
        <!-- Formulario de Inserción de Socio -->
        <div class="form-container">
            <h2>Insertar nueva persona</h2>
            <form id="form-insercion-socio">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" >

                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" >

                <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" >

                <label for="sexo">Sexo:</label>
                <select id="sexo" name="sexo" >
                    <option value="" disabled selected>Seleccione un sexo</option>
                    <!-- Opciones dinámicas de membresías -->
                </select>

                <label for="correo">Correo:</label>
                <input type="text" name="correo" id="correo">

                <label for="documento">Documento:</label>
                <input type="text" id="documento" name="documento" >

                <label for="tipo_documento">Tipo Documento:</label>
                <select name="tipo_documento" id="tipo_documento">
                    <option value="" disables selected>Seleccione un tipo de documento</option>
                    <!-- opciones dinamicas de tipo de documento -->
                </select>

                <label for="cantidad-membresia">Cantidad de Mensualidades</label>
                <input type="number" name="cantidad_meses" id="cantidad_meses">

                <label for="membresia">Membresía:</label>
                <select id="membresia" name="membresia" >
                    <option value="" disabled selected>Seleccione una membresía</option>
                    <!-- Opciones dinámicas de membresías -->
                </select>

                <button type="submit" class="btn btn-agregar">Guardar Socio</button>
            </form>
        </div>

        <!-- Contenedor de Búsqueda de Personas -->
        <div class="busqueda-container">
            <h2>Elegir persona existente</h2>
            <input type="text" id="buscar-persona" placeholder="Ingrese nombre o documento">
            <button id="btn-buscar" class="btn btn-ver-detalles">Buscar</button>
            
            <div class="resultados-personas">
                <p class="no-results">Sin resultados...</p>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>
<script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js"; ?>"></script>
<script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
<script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
<script>
    let sexos = <?php echo json_encode($sexos); ?>;
	let membresias = <?php echo json_encode($membresias); ?>;
    let tipos_documento = <?php echo json_encode($tipos_documento); ?>;
	let id_complejo = <?php echo json_encode($id_complejo); ?>;
</script>
<script src="../js/obtenerPersonas.js"></script>
<script src="../js/convertirPersonaEnSocio.js"></script>
<script src="../js/rellenar_select.js"></script>
<script src="../js/insertarPersonaYSocio.js"></script>
</body>
</html>