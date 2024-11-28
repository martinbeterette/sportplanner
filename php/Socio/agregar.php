<?php
session_start();
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once(RUTA . "php/functions/consulta_reutilizable_mysql.php");
require_once(RUTA . "config/database/db_functions.php");

$registrosSexo = obtenerSexos();
$registrosTipoDocumento = obtenerTipoDocumentos();
$registrosMembresia = obtenerMembresias(20, 0);

if (isset($_GET['id'])) {
    $id_complejo = $_GET['id'];
} else {
    echo "ha ocurrido un error :(" . "<br>";
    echo "<a href='" . BASE_URL . "index_tincho.php" . "'></a>";
}


$campos = ['id_complejo as id', 'descripcion_complejo'];
$tabla = 'complejo'; // La tabla principal

// Define el JOIN con la tabla ciudades
$join = '';

// Define la condición WHERE para buscar 
$condicion = "estado IN(1) AND id_complejo = {$id_complejo}";

// Obtén los registros de la base de datos con JOIN y WHERE
$registros = obtenerRegistros($tabla, $campos, $join, $condicion);
$reg = $registros->fetch_assoc();
//titulos y alta
$titulo_pagina = "Alta de Socio";
$modulo = "Alta de socio complejo {$reg['descripcion_complejo']}";
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $titulo_pagina; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url(../../css/header.css);
        @import url(../../css/aside.css);

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
        }

        /* Estilos para las etiquetas de los campos */
        .containerEmpleado label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #fff;
            text-align: center;
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

    <div class="containerEmpleado">
        <h1>Agregar Socio</h1>
        <form action="agregar_aplicar.php" method="post">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="">

            <label for="descripcion_documento">Documento:</label>
            <input type="text" id="documento" name="descripcion_documento" value="">

            <label for="tipo_documento">Tipo de Documento:</label>
            <select name="tipo_documento" required>
                <option value='' disabled selected>Seleccione un tipo de documento...</option>
                <?php foreach ($registrosTipoDocumento as $reg) : ?>
                    <option value="<?php echo $reg['id_tipo_documento']; ?>"><?php echo $reg['descripcion_tipo_documento']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="fecha_nacimiento">Fecha de nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="" required>
            <p id="error_message" style="color: red; display: none;">Solo se permite mayores de 18 años</p>

            <label for="descripcion_sexo">Sexo:</label>
            <select name="descripcion_sexo" required>
                <option value='' disabled selected>Seleccione un sexo...</option>
                <?php foreach ($registrosSexo as $reg) : ?>
                    <option value="<?php echo $reg['id_sexo']; ?>"><?php echo $reg['descripcion_sexo']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="" placeholder="ej: 1239874321" required>
            <p id="error_contacto" style="color: red; display: none;">El teléfono debe tener 10 dígitos numéricos.</p>

            <label for="rela_membresia">Membresia:</label>
            <select name="rela_membresia" required>
                <option value='' disabled selected>Seleccione una membresia...</option>
                <?php foreach ($registrosMembresia as $reg) : ?>
                    <option value="<?php echo $reg['id_membresia']; ?>"><?php echo $reg['descripcion_membresia'] . ' - %' . $reg['beneficio_membresia']; ?></option>
                <?php endforeach; ?>
            </select>

            <input type="hidden" name="id_complejo" value="<?php echo $id_complejo; ?>">

            <button type="submit">Enviar</button>
        </form>
    </div>

    <script src="<?php echo BASE_URL . 'libs/sweetalert2.all.min.js' ?>"></script>
    <script src="../../js/validacionForm.js"></script>
    <script src="../../js/validarEdad.js"></script>
    <script src="../../js/validartelefono.js"></script>
    <?php if (isset($_GET['persona_repetida'])) { ?>
        <script>
            swal.fire({
                icon: 'warning',
                text: 'Esta persona ya existe en la base de datos'
            })
        </script>
    <?php } ?>
    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>

    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>

</body>

</html>