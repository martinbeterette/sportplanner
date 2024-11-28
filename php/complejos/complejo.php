<?php 
    require_once("../../config/root_path.php");
    require_once(RUTA . "config/database/conexion.php");
    require_once(RUTA. "php/functions/consulta_es_propietario_del_complejo.php");
    session_start();

    if (isset($_GET['id_complejo'])) {
        $id_complejo = $_GET['id_complejo'];
    } else {
        echo "falta GET de complejo";
        die;
    }

    //verificamos si es propietario
    $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : die("falta id persona");
    $esPropietario = esPropietarioDelComplejo($id_usuario, $id_complejo, $conexion);
    if ($esPropietario) {
        // die("usted es propietario");
    } else {
        // die("no es propietario");
    }



    $query_complejo = "SELECT * FROM complejo WHERE id_complejo = ? AND estado IN(1)";
    $stmt = $conexion->prepare($query_complejo);
    $stmt->bind_param("i",$id_complejo);
    $stmt->execute();
    $registros = $stmt->get_result();

    foreach ($registros as $reg) {
        $descripcion_complejo = $reg['descripcion_complejo'];
        $fecha_alta = $reg['fecha_alta'];
    }

    $query_sucursal = "SELECT * FROM sucursal WHERE rela_complejo = ? AND estado IN(1)";
    $stmt = $conexion->prepare($query_sucursal);
    $stmt->bind_param("i",$id_complejo);
    $stmt->execute();
    $registros_sucursal = $stmt->get_result();


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sportplanner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/complejo.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside/menu_aside_beterette.css'; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css' ?>">

</head>

<body>

    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>


    <div class="conteiner_index">
        <div class="background_index">
          
            <main>
                <div class="main_conteiner">
             
                    <div class="indexComplejo">
                        <div class="complejocontainer">

                            <div class="complejoImg" style="background-image: url('alquiler-cancha-futbol-la-calle-proximamente-4 (1).jpg');"></div>

                            <div class="complejoDescripcion">
                                <h3><?php echo $descripcion_complejo; ?></h3>
                                <h3>Fecha de Creacion: <?php echo $fecha_alta; ?></h3>
                                <a href="hacerse_socio.php?id_persona=<?php echo $id_persona; ?>&id_complejo=<?php echo $id_complejo; ?>">Hazte Socios!</a>
                            </div>

                            <div class="complejoSucursales">

                                <?php foreach ($registros_sucursal as $reg) :?>
                                
                                    <div class="complejoSucursal" id="<?php echo $reg['id_sucursal'];?>">
                                        <h3><?php echo $reg['descripcion_sucursal']; ?></h3>
                                        <h5><?php echo $reg['direccion']; ?></h5>
                                    </div>

                                <?php endforeach; ?>

                            </div>

                            <div class="altas" align="center" style="">
                                <a href="<?php echo BASE_URL . "php/socio/tabla_socios.php?id_complejo=$id_complejo"; ?>">Gestionar Socios</a>
                            </div>

                        </div>
                    </div>

                    <footer style="background-color: antiquewhite;">
                        <h2>footer</h2>
                    </footer>
                </div>
            </main>
        </div>
    </div>
    <script src="<?php echo BASE_URL. "libs/jquery-3.7.1.min.js" ?>"></script>
    <script>
        $(document).ready(function () {
            $('.complejoSucursal').on('click', function () {

                let idSucursal = $(this).attr("id");
                alert(idSucursal);
                window.location.href = "<?php echo BASE_URL; ?>php/sucursales/sucursal.php" + "?id_sucursal=" + idSucursal;
            });

          
        });///document ready
    </script>
    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>


</body>

</html>