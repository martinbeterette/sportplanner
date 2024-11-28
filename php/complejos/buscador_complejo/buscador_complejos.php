<?php 
    session_start();
    require_once("../../../config/root_path.php"); 
    require_once(RUTA . "config/database/conexion.php");
    $sql = "SELECT id_complejo, descripcion_complejo, fecha_alta, fecha_fundacion
                  FROM complejo
                  WHERE estado IN(1) AND verificado LIKE 'verificado'";


        $filtro = $_GET['filtro'] ?? '';

        $condicional = " AND descripcion_complejo LIKE ?";
        $consulta = $sql . $condicional;

        $parametro = "%" . $filtro . "%";

        $stmt = $conexion->prepare($consulta);

        $stmt->bind_param("s", $parametro);

        $stmt->execute();

        $registros = $stmt->get_result();

        $registros = $conexion->query($sql);



?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href=
        "<?php echo BASE_URL . 'php/complejos/css/plantilla.css';?>"
    >
    <title>Buscador Complejos</title>
    <script src="https://kit.fontawesome.com/03cc0c0d2a.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>

    <div class="contenido">

        <div class="container">

            <h>Complejos</h>
            <br>
            <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="text" placeholder="Buscar Complejo..." name="filtro">
                <button type="submit" >
                    <i class="fa-solid fa-magnifying-glass" style="color: #FFD43B;"></i>
                </button>
            </form>


            <?php foreach ($registros as $reg) :?>

                <div class="complejos_encontrados" valor="<?php echo $reg['id_complejo']; ?>">

                    <picture>
                        <img src="" alt="icono">
                    </picture>

                    <h2>    <?php echo $reg['descripcion_complejo']; ?></h2>
                    <small>Creacion: <?php echo $reg['fecha_alta']; ?>           </small>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>
    <script>
        $(document).ready(function () {

            $(".complejos_encontrados").on("click", function () {
                let id_complejo = $(this).attr("valor");
                window.location.href = "<?php echo BASE_URL;?>php/complejos/complejo.php?id_complejo=" + id_complejo;
            });

        }); //document ready
    </script>

</body>
</html>

