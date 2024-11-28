<?php  

    session_start();
    require_once("functions.php");
    require_once("../../../../config/root_path.php");
    require_once(RUTA . "config/database/conexion.php");
    require_once(RUTA . "php/functions/consulta_reutilizable_mysql.php");

    $id_sucursal = false;
    if ($_SESSION['id_perfil'] == 3) {
        $id_persona = $_SESSION['id_persona'];
        $id_usuario = $_SESSION['id_usuario'];
        $id_sucursal = obtenerComplejoPorPersona($id_persona, $id_usuario);
    } 

    if (!$id_sucursal) {
        header("Location: " . BASE_URL . "errors/error403.php?no_tiene_acceso");
    }

    if (isset($_POST['btn-enviar'])) {
        $id_tarifa              = $_POST['id_tarifa'];
        $hora_inicio            = $_POST['hora_inicio'];
        $hora_fin               = $_POST['hora_fin'];
        $descripcion_tarifa     = $_POST['descripcion_tarifa'];
        $precio                 = $_POST['precio'];

        // Consulta para obtener todas las tarifas de la sucursal
        $sql = "SELECT hora_inicio, hora_fin FROM tarifa WHERE rela_sucursal = ? AND id_tarifa != ? AND estado IN(1)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $id_sucursal,$id_tarifa);
        $stmt->execute();
        $result = $stmt->get_result();

        $solapamiento = false;

        // Verificar la superposición de horarios
        while ($row = $result->fetch_assoc()) {
            $bd_hora_inicio = $row['hora_inicio'];
            $bd_hora_fin = $row['hora_fin'];

            // Verificar si los rangos cruzan la medianoche
            $cruza_medianoche_bd = ($bd_hora_inicio > $bd_hora_fin);
            $cruza_medianoche_form = ($hora_inicio > $hora_fin);

            // Lógica para comparar los horarios
            if ($cruza_medianoche_bd || $cruza_medianoche_form) {
                // Caso donde uno de los rangos cruza la medianoche
                if (
                    ($hora_inicio <= $bd_hora_fin || $hora_fin >= $bd_hora_inicio) || 
                    ($bd_hora_inicio <= $hora_fin || $bd_hora_fin >= $hora_inicio) 
                ) {
                    $solapamiento = true;
                    break;
                }
            } else {
                
                // Caso normal (sin cruzar medianoche)
                if (
                    ($hora_inicio < $bd_hora_fin && $hora_fin > $bd_hora_inicio) // Verificar solapamiento simple
                ) {
                    $solapamiento = true;
                    break;
                }
            }
        }

        // Resultado de la validación
        if ($solapamiento) {
            $stmt->close();
            $conexion->close();
            error_log("Comparando: $hora_inicio-$hora_fin con $bd_hora_inicio-$bd_hora_fin");
            // header("Location: ". $_SERVER['PHP_SELF'] ."?id_sucursal={$id_sucursal}&id={$id_tarifa}&tarifa_solapada");
            exit();
        } else {
            // Aquí modificamos la tarifa
            
            $sql = "UPDATE tarifa SET
                    hora_inicio = ?,
                    hora_fin = ?,
                    descripcion_tarifa = ?,
                    precio = ?
                    WHERE id_tarifa = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssii", $hora_inicio, $hora_fin, $descripcion_tarifa, $precio, $id_tarifa);


            if($stmt->execute()) {
                $stmt->close();
                $conexion->close();
                header("Location: ../tabla_tarifa.php");
                exit();
            }
            

        }



    }

    $titulo_pagina = "Tarifas";
    $modulo = "Gestion de Tarifas";

    if (isset($_GET['id'])) {
        $id_tarifa = $_GET['id'];
    } else {
        echo "ha ocurrido un error :( Falta GET de Tarifa" . "<br>";
        echo "<a href='" . BASE_URL . "index_tincho.php" . "'>Volver</a>";
        die;
    }

    //falta la consulta para la persistencia de datos
    $consulta  = "SELECT * FROM tarifa WHERE id_tarifa = {$id_tarifa}";
    $registros = $conexion->query($consulta);
    foreach ($registros as $reg) :
        $descripcion_tarifa = $reg['descripcion_tarifa'];
        $hora_inicio        = $reg['hora_inicio'];
        $hora_fin           = $reg['hora_fin'];
        $precio             = $reg['precio'];
    endforeach;

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_pagina; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside.css'; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css'; ?>">
</head>
<body>
    <?php include(RUTA . "includes/header.php") ?>
    <?php include(RUTA . "includes/menu_aside.php") ?>

    <div class="formulario">
        <h1><?php echo $modulo; ?></h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-input">
                <label for="descripcion_tarifa">Turno:</label>
                <input type="text" name="descripcion_tarifa" id="" placeholder="Ej: día o noche" value="<?= $descripcion_tarifa ?>" required>   
            </div>

            <div class="form-input">
                <label for="precio">Precio:</label>
                <input type="text" name="precio" id="" value="<?= $precio ?>" required>
            </div>

            <div class="form-input">
                <label for="hora_desde">Horario desde:</label>
                <input type="text" class="time-picker" name="hora_inicio" id="hora_desde" value="<?= $hora_inicio ?>" required>
            </div>

            <div class="form-input">
                <label for="hora_fin">Horario hasta:</label>
                <input type="text" class="time-picker" name="hora_fin" id="hora_fin" value="<?= $hora_fin ?>" required>
            </div>

            <input type="hidden" name="id_sucursal" value="<?php echo $id_sucursal; ?>">
            <input type="hidden" name="id_tarifa" value="<?php echo $id_tarifa; ?>">

            <button type="submit" class="btn-enviar" name="btn-enviar">Enviar</button>
        </form>
    </div>

    <!-- librerias -->
    <script src="<?php echo BASE_URL. 'libs/jquery-3.7.1.min.js'; ?>"></script>
    <script src="<?php echo BASE_URL. 'libs/sweetalert2.all.min.js'; ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function () {

            flatpickr('.time-picker', {
                dateFormat: "H:i",
                time_24hr: true,
                enableTime: true,
                noCalendar: true,
                // onClose: function(selectedDates, dateStr) {
                //     // Puedes realizar otras acciones aquí si lo necesitas
                //     console.log("Hora seleccionada: " + dateStr);
                // }
            });

            <?php if (isset($_GET['tarifa_solapada'])): ?>
                swal.fire({
                    title: "Tarifa Pisada",
                    text: "verifique que la tarifa Modificada no superponga a las demas",
                    html: "<h1><?= $_GET['tarifa_solapada'] ?></h1>",
                    icon: "warning",
                    confirmButtonColor: '#d33',
                });
            <?php endif; ?>

        }); //DOCUMENT READY
    </script>
</body>
</html>
