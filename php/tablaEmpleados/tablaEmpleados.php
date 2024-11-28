<?php

// esta es para el propietario, esta si necestia GET de suscursal
//si no tiene debe forzar la seleccion de sucursal
session_start();
require_once("../../config/root_path.php");
require_once('../../config/database/db_functions.php');
require_once("includes/functions.php");
if (isset($_GET['id_sucursal'])) {
	$id_sucursal = $_GET['id_sucursal'];
    $id_usuario = $_SESSION['id_usuario'];

    //obtenemos las sucursales del propietario y las validamos por la seleccionada
    //es decir, si puede gestionar la que esta en la url
    $sucursales = obtenerSucursalesDelPropietario($id_usuario);
    if($sucursales) {
        $array_sucursales = [];
        foreach ($sucursales as $reg) {
            $array_sucursales[] = $reg['id_sucursal'];
        }

    }

    if (!in_array($id_sucursal, $array_sucursales)) {
        header("Location: seleccionar_sucursal.php");
        exit();
    }

} else {
	header("Location: seleccionar_sucursal.php");
	exit();
}

$datos_sucursal = $conexion->query("SELECT * FROM sucursal WHERE id_sucursal = $id_sucursal")->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>TABLA EMPLEADOS</title>
	<link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside.css'; ?>">
	<link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css' ?>">
    <link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
	<?php include(RUTA. "includes/header.php"); ?>

	<?php include(RUTA."includes/menu_aside.php") ?>

	<div class="container">
        <center><h1>Modulo de Empleados de Sucursal <?php echo $datos_sucursal['descripcion_sucursal'] ?></h1></center>

		<p>buscar</p>
		<input type="text" id="buscador" placeholder="Buscar...">
	    <div id="tabla-container"></div>
	    <div id="paginacion-container"></div>
        <button class="btn-agregar" onclick="window.location.href='formulario_creacion_empleado/?id_sucursal=<?php echo $id_sucursal; ?>'">Agregar Empleado</button>
	</div>



	<script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js"; ?>"></script>

	<script>
		let id_sucursal= <?php echo json_encode($id_sucursal); ?>;
        $(document).on('click', '.eliminar', function() {
            let valor = $(this).attr('valor');
            let id_sucursal = $(this).attr('id_sucursal');
            // Mostrar SweetAlert con botones personalizados
            Swal.fire({
                title: '¿Seguro que desea eliminar este registro?',
                text: "No podrás deshacer esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Botón rojo
                cancelButtonColor: '#aaa', // Botón gris
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'custom-swal-popup' // Añadir una clase personalizada al modal
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    eliminar(valor, id_sucursal);
                }
            });

        }); // #ELIMINAR ON CLICK

        function eliminar(id_empleado, id_sucursal) {
            window.location.href = "tablaEmpleados_baja.php?id_empleado=" + id_empleado + "&id_sucursal=" + id_sucursal;
        }
	</script>
	<script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
	<script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>
    <script src="js/tablaYPaginado.js"></script>


</body>

</html>