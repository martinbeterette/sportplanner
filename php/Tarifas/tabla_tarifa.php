<?php 
session_start();
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once(RUTA . "php/functions/consulta_reutilizable_mysql.php");

if (isset($_GET['id_sucursal'])) {
    $id_sucursal = $_GET['id_sucursal'];
} else {
    echo "ha ocurrido un error :( Falta GET de sucursal" . "<br>";
    echo "<a href='" . BASE_URL . "index_tincho.php" . "'>Volver</a>";
    die;
}

// Define las variables reutilizables
$titulo_pagina = "Tarifas";
$modulo = "Gestion de Tarifas";

// definimos los campos del encabezado
$thead = ['ID', 'Descripcion', 'Precio'];

// Define los campos a seleccionar
$campos = ['id_tarifa as id', 'descripcion_tarifa', 'hora_inicio', 'hora_fin', 'precio'];
$tabla = 'tarifa'; // La tabla principal

// Define el JOIN con la tabla ciudades
$join = '';

// Define la condición WHERE para buscar 
$condicion = "rela_sucursal = $id_sucursal AND estado IN(1)";

//orden de la consulta
$orden = '';

// Obtén los registros de la base de datos con JOIN y WHERE
$registros = obtenerRegistros($tabla, $campos, $join, $condicion);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_pagina; ?></title>
    <!-- <link rel="stylesheet" href="<?php/* echo BASE_URL . 'css/aside/menu_aside_beterette.css';*/ ?>"> -->
    <!-- <link rel="stylesheet" href="<?php /*echo BASE_URL . 'css/header.css'*/ ?>"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">



</head>

<body>
    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>

    <div id="contenido">
        
        <!-- Formulario -->
        <div class="formulario">
            <form action="<?php echo "agregar.php?id_sucursal={$id_sucursal}" ?>" method="POST">
                <div class="form-input">
                    <label for="descripcion_tarifa">Turno:</label>
                    <input type="text" name="descripcion_tarifa" id="" placeholder="Ej: día o noche" required>   
                </div>

                <div class="form-input">
                    <label for="precio">Precio:</label>
                    <input type="text" name="precio" id="" required>
                </div>

                <div class="form-input">
                    <label for="hora_desde">Horario desde:</label>
                    <input type="text" class="time-picker" name="hora_inicio" id="hora_inicio" required>
                </div>

                <div class="form-input">
                    <label for="hora_hasta">Horario hasta:</label>
                    <input type="text" class="time-picker" name="hora_fin" id="hora_fin" required>
                </div>

                <button type="submit" class="btn-enviar">Enviar</button>
            </form>
        </div>

        <div class="tabla">
            <h1 align="center"><?php echo $modulo; ?></h1>
            <table>
                <thead>
                    <tr>

                        <?php foreach ($thead as $th) : ?>
                            <th><?php echo $th; ?></th>
                        <?php endforeach; ?>
                        <th colspan="2">Rango de Horario</th>
                        <th></th>
                        <th></th>
                        <th></th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($registros as $registro) {
                        $modificar = "<a href='modificar.php?id={$registro['id']}&id_sucursal=$id_sucursal' class='btn-modificar'>
                                    Modificar
                                </a>";

                        $eliminar = "<a valor='{$registro['id']}' id_sucursal='{$id_sucursal}' class='btn-eliminar'>
                                                Eliminar
                                            </a>";
                    ?>

                        <tr>
                            <td><?php echo $registro['id']; ?></td>
                            <td><?php echo $registro['descripcion_tarifa']; ?></td>
                            <td><?php echo $registro['precio']; ?></td>
                            <td><?php echo $registro['hora_inicio']; ?></td>
                            <td><?php echo $registro['hora_fin']; ?></td>
                            <td class="actions"><?php echo $modificar; ?></td>
                            <td class="actions"><?php echo $eliminar; ?></td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div> <!-- tabla -->

    </div> <!-- Contenido -->

    <script src="<?php echo BASE_URL. 'libs/jquery-3.7.1.min.js'; ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>
    <script src="<?php echo BASE_URL. 'libs/sweetalert2.all.min.js'; ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function () {

            <?php if (isset($_GET['tarifa_solapada'])) : ?>
                swal.fire({
                    title: "Tarifa Pisada",
                    text: "verifique que la tarifa insertada no superponga a las demas",
                    icon: "warning",
                    confirmButtonColor: '#d33',
                });
            <?php endif; ?>

            // Eliminar con SweetAlert
            $('.btn-eliminar').on('click', function() {
                let valor = $(this).attr('valor');
                let id_sucursal = $(this).attr('id_sucursal');
                Swal.fire({
                    title: '¿Seguro que desea eliminar este registro?',
                    text: "No podrás deshacer esta acción",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        eliminar(valor, id_sucursal);
                    }
                });
            });

            // Flatpickr para los inputs de tiempo
            flatpickr('.time-picker', {
                dateFormat: "H:i",
                time_24hr: true,
                enableTime: true,
                noCalendar: true,
            });

            // Función para eliminar el registro
            function eliminar(id, id_sucursal) {
                window.location.href = "eliminar.php?id=" + id + "&id_sucursal=" + id_sucursal;
            }
        });
    </script>
</body>

</html>