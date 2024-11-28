<?php
session_start();
require_once("../../config/database/conexion.php");
require_once("../../config/root_path.php");
$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("falta GET de sucursal");
$id = $_GET['id_empleado'];

$sqlSucursal = "SELECT 
                    id_sucursal,
                    descripcion_sucursal
                FROM 
                    sucursal";
$registrosSucursal = $conexion->query($sqlSucursal);

$sql = "SELECT  
                        empleado.id_empleado,
                        persona.nombre,
                        persona.apellido,
                        documento.id_documento,
                        persona.fecha_nacimiento,
                        empleado.rela_persona,
                        empleado.fecha_alta,
                        empleado.rela_sucursal
                    FROM
                        empleado
                    JOIN
                        persona
                    ON
                        empleado.rela_persona = persona.id_persona
                    JOIN
                        sucursal
                    ON
                        empleado.rela_sucursal = sucursal.id_sucursal
                    JOIN 
                        documento
                    ON
                        documento.rela_persona = persona.id_persona
                    WHERE
                        empleado.estado IN(1)
                    AND 
                        empleado.id_empleado = $id";

$registros = $conexion->query($sql);
if (!$registros) {
    header("Location: tablaEmpleados.php?id_sucursal=$id_sucursal");
}

foreach ($registros as $reg) {
    echo $id             = $reg['id_empleado'];
    echo $nombre         = $reg['nombre'];
    echo $apellido       = $reg['apellido'];
    echo $documento      = $reg['descripcion_documento'];
    echo $fechaNacimiento = $reg['fecha_nacimiento'];
    echo $fechaAlta      = $reg['fecha_alta'];
    echo $relaPersona    = $reg['rela_persona'];
}

if (isset($_POST['modificacion'])) {
    $nombre         = $_POST['nombre'];
    $apellido       = $_POST['apellido'];
    $documento      = $_POST['documento'];
    $cargo          = $_POST['cargo'];
    $fechaNacimiento = $_POST['fecha_nacimiento'];
    $sucursal       = $id_sucursal;

    // Iniciamos la transacción
    $conexion->begin_transaction();

    try {
        // Actualizar documento
        $sqlDocumento = "UPDATE documento 
                            SET descripcion_documento = ? 
                            WHERE id_documento = ?";
        $stmtDocumento = $conexion->prepare($sqlDocumento);
        $stmtDocumento->bind_param("si", $documento, $relaDocumento);
        $stmtDocumento->execute();

        // Actualizar persona
        $sqlPersona = "UPDATE persona 
                        SET nombre = ?, apellido = ?, fecha_nacimiento = ?
                        WHERE id_persona = ?";
        $stmtPersona = $conexion->prepare($sqlPersona);
        $stmtPersona->bind_param("sssi", $nombre, $apellido, $fechaNacimiento, $relaPersona);
        $stmtPersona->execute();

        // Actualizar empleado
        $sqlEmpleado = "UPDATE empleado 
                            SET empleado_cargo = ?, rela_sucursal = ?
                            WHERE id_empleado = ?";
        $stmtEmpleado = $conexion->prepare($sqlEmpleado);
        $stmtEmpleado->bind_param("sii", $cargo, $sucursal, $id);
        $stmtEmpleado->execute();

        // Si todo salió bien, hacemos el commit
        $conexion->commit();

        // Redireccionar si todo fue exitoso
        header("Location: tablaEmpleados.php?id_sucursal=$id_sucursal");
    } catch (Exception $e) {
        // Si ocurre algún error, hacemos un rollback
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Cerramos las conexiones
    $stmtDocumento->close();
    $stmtPersona->close();
    $stmtEmpleado->close();
    $conexion->close();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Empleado</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside/aside.css'; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css' ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: #161616;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Formulario Empleado/////////////////////////////////////77 */
        /* Estilos generales para el contenedor del formulario */
        .containerEmpleado {
            width: 60%;
            margin: auto;
            margin-top: 10px;
            padding: 20px;
            background-color: #212121;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgb(128, 128, 128, 0.7);
        }

        .containerEmpleado h1 {
            color: #fff;
            text-align: center;
        }

        .containerEmpleado form {
            margin-top: 10px;
            text-align: center;
        }

        /* Estilos para las etiquetas de los campos */
        .containerEmpleado label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #fff;
        }

        /* Estilos para los campos de entrada de texto */
        .containerEmpleado input[type="text"],
        .containerEmpleado input[type="date"],
        .containerEmpleado select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #2c2c2c;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        /* Estilos para cambiar el color del borde cuando el campo está enfocado */
        .containerEmpleado input[type="text"]:focus,
        .containerEmpleado input[type="date"]:focus,
        .containerEmpleado select:focus {
            border-color: grey;
            box-shadow: 1px 0px 3px grey;
            outline: none;
        }

        /* Estilos para el botón de enviar */
        .containerEmpleado button {
            width: 40%;
            padding: 12px;
            background-color: #2c2c2c;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Cambio de color al pasar el cursor sobre el botón */
        .containerEmpleado button:hover {
            background-color: #0b0b0b;
            border: 1px solid grey;
            box-shadow: 1px 0px 3px gray;
        }

        /* Ajustes para pantallas pequeñas */
        @media (max-width: 480px) {
            .containerEmpleado {
                padding: 10px;
            }

            .containerEmpleado label {
                font-size: 14px;
            }

            .containerEmpleado input[type="text"],
            .containerEmpleado input[type="date"],
            .containerEmpleado select {
                font-size: 14px;
            }

            .containerEmpleado button {
                font-size: 14px;
                padding: 10px;
            }
        }

        .error {
            color: #ff6448;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>

    <script src="js/jquery-3.7.1.min.js"></script>
    <div class="containerEmpleado">
        <h1>Modulo Modificacion de Empleado</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?id_empleado=' . $id . '&id_sucursal=' . $id_sucursal; ?>" method="post" onsubmit="return confirmModification();">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?php echo $apellido; ?>" required>

            <label for="dni">Documento:</label>
            <input type="text" id="documento" name="documento" value="<?php echo $documento; ?>" required>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $fechaNacimiento ?>" required>
            <p id="error_message" style="color: red; display: none;">Solo se permite mayores de 18 años</p>

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