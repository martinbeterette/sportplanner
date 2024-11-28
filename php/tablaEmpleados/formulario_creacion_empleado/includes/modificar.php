<?php
session_start();
require_once("../../../../config/root_path.php");
require_once(RUTA . "/config/database/conexion.php");
require_once("functions.php");
$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("falta GET de sucursal");
$id = $_GET['id_empleado'] ?? die("falta ID empleado");


$tiposDeDocumento = $conexion->query("SELECT * FROM tipo_documento");
$sexos = $conexion->query("SELECT * FROM sexo");
$registros = obtenerEmpleado($id);

if ($registros->num_rows == 0) {
    header("Location: ../../tablaEmpleados.php?id_sucursal=$id_sucursal");
}

foreach ($registros as $reg) {
    $id             = $reg['id_empleado'];
    $nombre         = $reg['nombre'];
    $apellido       = $reg['apellido'];
    $documento      = $reg['descripcion_documento'];
    $fechaNacimiento = $reg['fecha_nacimiento'];
    $fechaAlta      = $reg['fecha_alta'];
    $sucursal       = $reg['rela_sucursal'];
    $idDocumento    = $reg['id_documento'];
    $idTipoDocumento= $reg['rela_tipo_documento'];
    $idPersona    = $reg['id_persona'];
    $relaSexo       = $reg['rela_sexo'];
}

if (isset($_POST['modificacion'])) {
    $nombre             = $_POST['nombre'];
    $apellido           = $_POST['apellido'];
    $documento          = $_POST['documento'];
    $tipoDocumento      = $_POST['tipo_documento'];
    $relaSexo           = $_POST['sexo'];
    $fechaNacimiento    = $_POST['fecha_nacimiento'];

    $existe = validarPersona($nombre,$apellido,$documento,$fechaNacimiento,$id_sucursal); // a esta funcion se le pasa datos solo para mostrarlos en pantalla en caso de persona repetida

    if ($existe->num_rows > 0) {
        die("Documento repetido");
    }

    if(actualizarEmpleado($conexion,$documento,$idDocumento,$tipoDocumento,$nombre,$apellido,$fechaNacimiento,$relaSexo,$idPersona, $id)){
        header("Location: ../../tablaEmpleados.php?id_sucursal=$id_sucursal");
    }

}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Empleado</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside.css'; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css' ?>">
    <link rel="stylesheet" href="../css/modificar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>

    <div class="containerEmpleado">
        <h1>Modulo Modificacion de Empleado</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?id_empleado=' . $id . '&id_sucursal=' . $id_sucursal; ?>" method="post" onsubmit="return confirmModification();">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?php echo $apellido; ?>" required>

            <label for="dni">Documento:</label>
            <input type="text" id="documento" name="documento" value="<?php echo $documento; ?>" required>

            <label for="tipo_documento">Tipo documento:</label>
            <select name="tipo_documento" id="tipo_documento">
                <option value="">seleccione tipo de documento</option>
                <?php foreach ($tiposDeDocumento as $reg): ?>
                    <option 
                        value="<?php echo $reg['id_tipo_documento']; ?>"
                        <?php 
                            if ($idTipoDocumento == $reg['id_tipo_documento']){
                                echo 'selected';
                            }                        
                        ?>
                    >
                        <?php echo $reg['descripcion_tipo_documento']; ?>
                            
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $fechaNacimiento ?>" required>
            <p id="error_message" style="color: red; display: none;">Solo se permite mayores de 18 años</p>

            <label for="sexo">Sexo:</label>
            <select name="sexo" id="sexo">
                <option value="">Selecciones un sexo</option>
                <?php foreach ($sexos as $sexo) { ?>
                    <option 
                        value="<?php echo $sexo['id_sexo']; ?>"
                        <?php echo $relaSexo == $sexo['id_sexo'] ? 'selected':'';?>
                    ><?php echo $sexo['descripcion_sexo']; ?></option>
                <?php } ?>
            </select>

            <button type="submit" name="modificacion">Enviar</button>
        </form>
    </div>
    <script src="../../js/validacionForm.js"></script>
    <script src="../../js/validarEdad.js"></script>
    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>

    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>

</body>

</html>