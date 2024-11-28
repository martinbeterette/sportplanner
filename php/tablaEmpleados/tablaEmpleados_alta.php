<?php
session_start();
$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("falta GET de sucursal");
require_once("../../config/database/conexion.php");
require_once('../../config/root_path.php');

$sqlSucursal = "SELECT 
                    id_sucursal,
                    descripcion_sucursal
                FROM 
                    sucursal";

$sqlTipoDocumento = "SELECT
                        id_tipo_documento,
                        descripcion_tipo_documento
                    FROM
                        tipo_documento
                    WHERE 
                        estado IN(1)";

$registrosSucursal = $conexion->query($sqlSucursal);

$registrosTipoDocumento = $conexion->query($sqlTipoDocumento);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Empleado</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside/menu_aside_beterette.css'; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css' ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url(../../../css/header.css);
        @import url(../../../css/aside.css);

        body {
            font-family: Arial, sans-serif;
            background-color: #161616;
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
            color: white;
            text-align: center;
        }

        /* Estilos para las etiquetas de los campos */
        .containerEmpleado label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
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

        /* MODAL */
        .notification-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none; /* Oculto por defecto */
            justify-content: center;
            align-items: center;
        }
        .notification-box {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 90%; /* Limitar el ancho máximo */
            width: 300px; /* Ajustar según el tamaño del formulario */
            margin:auto;
            margin-top:30vh;
        }

        .notification-box p {
            margin: 0 0 15px;
        }

        .notification-box .close-btn {
            background: #dc3545;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        /* MODAL */
    </style>
</head>

<body>
    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>

    
    <div class="containerEmpleado">

        <!-- MODAL  -->
        <div class="notification-overlay" id="notificationOverlay">
            <div class="notification-box">
                <p>Persona repetida.</p>
                <small>Esta persona ya fue insertada:</small>
                <p>nombre:<?php echo " " . $_GET['nombre']; ?></p>
                <p>apellido:<?php echo " " . $_GET['apellido']; ?></p>
                <p>documento:<?php echo " " . $_GET['documento']; ?></p>
                <p>fecha de nacimiento:<?php echo " " . $_GET['fecha_nacimiento']; ?></p>


                <button class="close-btn" id="closeNotification">Cerrar</button>
            </div>
        </div>


        <h1>Modulo Alta de Empleados</h1>
        <form action="tablaEmpleados_aplicar_alta.php?<?php echo "id_sucursal=$id_sucursal"; ?>" method="post">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="" required>


            <label for="documento">Documento:</label>
            <input type="text" id="documento" name="documento" value="" required>

            <label for="tipo_documento">Tipo de documento:</label>
            <select name="tipo_documento" required>
                <option value="" disabled selected>Seleccione una tipo de documento...</option>
                <?php foreach ($registrosTipoDocumento as $reg) { ?>
                    <option value="<?php echo $reg['id_tipo_documento']; ?>"><?php echo $reg['descripcion_tipo_documento']; ?></option>
                <?php } ?>
            </select>

            <label for="cargo">Cargo:</label>
            <select name="cargo" required>
                <option value="" disabled selected>Seleccione una Cargo...</option>
                <option value="Personal Administrativo">Personal Administrativo</option>
                <option value="Portero">Portero</option>
            </select>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="" required>
            <p id="error_message" style="color: red; display: none;">Solo se permite mayores de 18 años</p>

            </select>

            <button type="submit">Enviar</button>
        </form>
    </div>

    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>
    <script src="../../js/validacionForm.js"></script>
    <script src="../../js/validarEdad.js"></script>
    <script>
        $(document).ready(function() {
            //funcion cerrar el modal 
            $('#closeNotification').on('click', function() {
                $('#notificationOverlay').hide();
            });


            // Mostrar el modal solo si se detecta la variable 'persona_repetida' en la URL
            <?php if (isset($_GET['persona_repetida'])): ?>
                $('#notificationOverlay').show();
            <?php endif; ?>
        }); //FIN DOCUMENT READY
    </script>

    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>


</body>

</html>