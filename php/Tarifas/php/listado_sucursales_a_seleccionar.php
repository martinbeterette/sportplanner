<?php 
	session_start();
	require_once("../../../config/root_path.php");
	require_once(RUTA . "config/database/conexion.php");

	if (!isset($_SESSION['sucursales'])) {

		$complejos_imploded = implode(",", $_SESSION['complejos']);
		$sql = "SELECT id_sucursal FROM sucursal WHERE rela_complejo IN(?) AND estado IN(1)";
		$stmt = $conexion->prepare($sql);
		$stmt->bind_param("s",$complejos_imploded);
		if($stmt->execute()) { 
			$registros = $stmt->get_result();
			$sucursales = [];
			foreach($registros as $reg) {
				$sucursales[] = $reg['id_sucursal'];
			}
			$stmt->close();
		}
	}

	$sucursales_imploded = implode(",", $sucursales);
	$sql = "SELECT id_sucursal, descripcion_sucursal, descripcion_complejo, asd.direccion, 
				sucursal.fecha_de_creacion
			FROM sucursal JOIN complejo ON sucursal.rela_complejo = complejo.id_complejo
			JOIN asignacion_sucursal_domicilio asd ON asd.rela_sucursal = sucursal.id_sucursal
			WHERE id_sucursal IN(?) and sucursal.estado IN(1)";
	$stmt = $conexion->prepare($sql);
	$stmt->bind_param("s",$sucursales_imploded);
	if($stmt->execute()) {
		$registros = $stmt->get_result();
		$stmt->close();
	}
	
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href=
        "<?php echo BASE_URL . 'php\Tarifas\css\listado_sucursales_a_seleccionar.css';?>"
    >
    <title>Seleccionar sucursal</title>
    <script src="https://kit.fontawesome.com/03cc0c0d2a.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>

    <div class="contenido">

        <div class="container">

            <h>Seleccione una sucursal</h>


            <?php foreach ($registros as $reg) :?>

                <div class="sucursal" valor="<?php echo $reg['id_sucursal']; ?>">

                    <picture>
                        <img src="" alt="icono">
                    </picture>

                    <h2>    <?php echo $reg['descripcion_sucursal']; ?></h2>
                    <small>Creacion: <?php echo $reg['fecha_de_creacion']; ?>           </small>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>
    <script>
        $(document).ready(function () {

            $(".sucursal").on("click", function () {
                let id_sucursal = $(this).attr("valor");
                window.location.href = "<?php echo BASE_URL;?>php/tarifas/tabla_tarifa.php?id_sucursal=" + id_sucursal;
            });

        }); //document ready
    </script>

</body>
</html>