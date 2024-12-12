<?php
session_start();
require_once("../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");
require_once("includes/functions.php");

if(!isset($_SESSION['id_usuario'])) {
    header("Location: ". BASE_URL);
}

echo $id_persona = $_SESSION['id_persona'];
echo $id_usuario = $_SESSION['id_usuario'];

if ($_SESSION['id_perfil'] == 3) {
    $id_sucursal = obtenerSucursalDelEmpleado($id_persona, $id_usuario);
    if(!$id_sucursal) {header("Location: " . BASE_URL ."index,php");}
}


if($_SESSION['id_perfil'] == 23) {

    $id_sucursal = isset($_GET['id_sucursal']) ? filter_input(INPUT_GET, 'id_sucursal',FILTER_SANITIZE_NUMBER_INT) : header("Location: includes/seleccionar_sucursal.php");
    $sucursales = obtenerSucursalesDelPropietario($id_usuario);
    if($sucursales) {
        foreach ($sucursales as $reg) {
            $array_sucursales[] = $reg['id_sucursal'];
        }
        if(!in_array($id_sucursal, $array_sucursales)) {
            header("Location: includes/seleccionar_sucursal.php");
        }
    } else {
        header("Location: includes/seleccionar_sucursal.php");
    }

}




$consulta_reservas = 
    "SELECT 
            r.id_reserva, 
            r.rela_persona, 
            r.rela_zona, 
            z.descripcion_zona,
            r.rela_estado_reserva,
            p.nombre,
            p.apellido,
            c.fecha_alta, 
            c.metodo_pago, 
            ec.descripcion_estado_control,
            c.monto_base, 
            c.monto_final, 
            c.rela_tarifa, 
            c.entrada  , 
            c.salida  
        FROM 
            reserva r
        JOIN persona p ON r.rela_persona = p.id_persona
        JOIN 
            zona z ON z.id_zona = r.rela_zona
        JOIN 
            sucursal s ON z.rela_sucursal = s.id_sucursal
        LEFT JOIN 
            control c ON c.rela_reserva = r.id_reserva
        LEFT JOIN estado_control ec ON ec.id_estado_control = c.rela_estado_control
        WHERE 
            s.id_sucursal = {$id_sucursal}
";
echo $id_sucursal;
$reservas_hechas = $conexion->query($consulta_reservas);
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Reservas</title>
    <link rel="stylesheet" href="css/listado_reservas.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL. "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL. "css/aside.css" ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>

<?php include(RUTA."includes/header.php"); ?>
<?php include(RUTA."includes/menu_aside.php"); ?>

<div class="contenedor-listado">
    <h1 class="titulo-listado">Listado de Reservas</h1>

    <!-- Contenedor del buscador -->
    <input type="text" id="buscador" placeholder="Buscar nombre, apellido..." />

    <!-- Contenedor del filtro por fecha -->
    <input type="text" id="filtro-fecha" placeholder="Selecciona una fecha" />
    <div id="tabla-container">
        <!-- Aquí se carga dinámicamente la tabla -->
    </div>

    <div id="paginacion-container">
        <!-- Aquí se carga dinámicamente la tabla -->
    </div>
</div>

<script src="<?php echo BASE_URL. "libs/sweetalert2.all.min.js" ?>"></script>
<script src="<?php echo BASE_URL. "libs/jquery-3.7.1.min.js" ?>"></script>
<script src="<?php echo BASE_URL. "js/header.js" ?>"></script>
<script src="<?php echo BASE_URL. "js/aside.js" ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<?php if(isset($_GET['no_hay_entrada'])) { ?>
    <script>Swal.fire("Error","Primero debe marcar una llegada", "warning");</script>
<?php } ?>
<script>
    let respuesta = false;
    if (respuesta) {
        Swal.fire({
            icon: 'success',
            title: `Llegada marcada para la reserva`,
            timer: 1500,
            showConfirmButton: false
        });
    }
</script>
<script>
    var pagina_actual = <?php echo $_GET['pagina_actual'] ?? 1; ?>;
    var id_sucursal = <?php echo $id_sucursal; ?>;
</script>
<script src="js/habilitaciones_botones.js"></script>
<script src="js/tablaYPaginado.js"></script>
<script>
    let monto;
    $(document).ready(function() {
        $(document).on("click",".acciones", function(){
            let id_reserva      = $(this).data('id-reserva');
            let pagina_actual   = $(this).data('pagina');
            let id_sucursal     = $(this).data('id_sucursal');
            monto           = $(this).data('monto');
            abrirModal(id_reserva);
        });

    });


    function abrirModal(idReserva) {
        Swal.fire({
            title: '¿Qué acción deseas registrar?',
            html: `
                <h3>Selecciona una opción para continuar:</h3>
                <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 10px;">
                    <button onclick="marcarLlegada(${idReserva})" style="margin: 5px; padding: 10px; background: #4CAF50; color: white; border: none; cursor: pointer;" title="El cliente llegó a jugar">Marcar Llegada</button>
                    <button onclick="marcarSalida(${idReserva})" style="margin: 5px; padding: 10px; background: #2196F3; color: white; border: none; cursor: pointer;" title="El cliente se retiró de forma satisfactoria">Marcar Salida</button>
                    <button onclick="marcarInasistencia(${idReserva})" style="margin: 5px; padding: 10px; background: #f44336; color: white; border: none; cursor: pointer;" title="El cliente no se presentó a jugar">Marcar Inasistencia</button>
                    <button onclick="salidaAnticipada(${idReserva})" style="margin: 5px; padding: 10px; background: #FFC107; color: black; border: none; cursor: pointer;" title="El cliente no concluyó su reserva esperadamente">Salida Anticipada</button>
                </div>
                <button onclick="Swal.close()" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
            `,
            showConfirmButton: false
        });
    }



    function marcarLlegada(id) {
        console.log("Marcar llegada para ID: " + id);

        Swal.fire({
            title: 'Elegir un metodo de pago',
            html: `
                <p><strong>Precio: </strong>${monto}</p>
                <select name="metodo_pago" id="metodo_pago">
                    <option value="" disabled selected>Seleccione un metodo de pago</option>
                    <option value="efectivo">efectivo</option>
                    <option value="trasferencia">transferencia</option>
                    <option value="tarjeta">tarjeta</option>
                </select>
            `,

            preConfirm:() => {
                const pago = document.getElementById('metodo_pago').value;
                if (!pago) {
                    Swal.showValidationMessage('Por favor, selecciona un método de pago.');
                    return false; // Evita que cierre el modal
                }
                return pago; // Retorna el valor seleccionado
            }

        }).then(function(result){
            if(result.isConfirmed) {
                const metodoPagoSeleccionado = result.value;
                window.location.href = `includes/marcar_llegada.php?id_reserva=${id}&id_sucursal=${id_sucursal}&pagina_actual=${pagina_actual}&metodo_pago=${metodoPagoSeleccionado}`;

            }
        });

    }

    function marcarSalida(id) {
        console.log("Marcar salida para ID: " + id);
        window.location.href = `includes/marcar_salida.php?id_reserva=${id}&id_sucursal=
        ${id_sucursal}&pagina_actual=${pagina_actual}`;
    }

    function marcarInasistencia(id) {
        console.log("Marcar inasistencia para ID: " + id);
        window.location.href = `includes/marcar_inasistencia.php?id_reserva=${id}&id_sucursal=
        ${id_sucursal}&pagina_actual=${pagina_actual}`;
    }

    function salidaAnticipada(id) {
        console.log("Salida anticipada para ID: " + id);
        Swal.fire({
            title: "salida anticipada",
            html: `
                <button onclick="Swal.close()" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
                <input type='text' placeholder='motivo de salida'>`,
            showConfirmButton: false,

        }).then(function(input) {
            if (input.isConfirmed) {
                window.location.href = `includes/marcar_salida_anticipada.php?id_reserva=${id}&id_sucursal=
            ${id_sucursal}&pagina_actual=${pagina_actual}`;
            }

        });
    }
</script>
</body>
</html>
