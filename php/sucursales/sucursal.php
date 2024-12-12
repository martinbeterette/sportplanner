<?php

// ESTE ES PARA EL PROPIETARIO


require_once("../../config/root_path.php");
require_once("includes/functions.php");
require_once(RUTA . "config/database/conexion.php");
session_start();


    $id_sucursal = (int)$_GET['id_sucursal'] ?? header("Location: ". BASE_URL . "index2.php");
    if(!$id_sucursal) {
        header("Location: includes/seleccion_sucursal.php");
    }

    $id_usuario = $_SESSION['id_usuario'];
    $sucursales = obtenerSucursalesDelPropietario($id_usuario);
    if($sucursales->num_rows > 0) {
        foreach ($sucursales as $reg) {
            $array_sucursales[] = $reg['id_sucursal'];
        }
        if(!in_array($id_sucursal, $array_sucursales)) {
            header("Location: includes/seleccion_sucursal.php");
        }
    } else {
        header("Location: ". BASE_URL . "index2.php");
    }
// Consulta para obtener la información de la sucursal
$sql_sucursal = "
    SELECT 
        s.id_sucursal,
        s.descripcion_sucursal,
        s.fecha_de_creacion,
        asd.direccion,
        b.descripcion_barrio
    FROM sucursal s
    JOIN asignacion_sucursal_domicilio asd ON s.id_sucursal = asd.rela_sucursal
    JOIN barrio b ON asd.rela_barrio = b.id_barrio
    WHERE s.id_sucursal = ?";
$stmt = $conexion->prepare($sql_sucursal);
$stmt->bind_param("i", $id_sucursal);
$stmt->execute();
$sucursal = $stmt->get_result()->fetch_assoc();


// Consulta para listar las canchas asociadas a la sucursal
$sql_canchas = "
    SELECT 
        z.id_zona,
        z.descripcion_zona,
        estado_zona.descripcion_estado_zona,
        fd.descripcion_formato_deporte	
    FROM zona z
    JOIN formato_deporte fd ON z.rela_formato_deporte = fd.id_formato_deporte
    JOIN estado_zona ON rela_estado_zona = id_estado_zona
    WHERE z.rela_sucursal = ? AND z.estado = 1";
$stmt_canchas = $conexion->prepare($sql_canchas);
$stmt_canchas->bind_param("i", $id_sucursal);
$stmt_canchas->execute();
$canchas = $stmt_canchas->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucursal: <?php echo htmlspecialchars($sucursal['descripcion_sucursal']); ?></title>
    <link rel="stylesheet" href="css/sucursal.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
</head>
<body>
    <?php include(RUTA."includes/header.php"); ?>
    <?php include(RUTA."includes/menu_aside.php"); ?>

    <div class="header">
	    <h1>Sucursal: <?php echo htmlspecialchars($sucursal['descripcion_sucursal']); ?></h1>
	</div>

    

    <div class="sucursal-info">
	    <div class="info-left">
	        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($sucursal['direccion']); ?></p>
	        <p><strong>Barrio:</strong> <?php echo htmlspecialchars($sucursal['descripcion_barrio']); ?></p>
	        <p><strong>Fecha de Creación:</strong> <?php echo htmlspecialchars($sucursal['fecha_de_creacion']); ?></p>
	    </div>
	    <div class="gestion-buttons">
	        <a href="http://localhost/sportplanner/php/Tarifas/includes/seleccionar_sucursal.php" class="btn btn-gestion">Gestión de tarifas</a>
	        <a href="http://localhost/sportplanner/php/Socio/gestion_socios/index.php" class="btn btn-gestion">Gestión de socios</a>
	        <a href="http://localhost/sportplanner/php/tablaEmpleados/seleccionar_sucursal.php" class="btn btn-gestion">Gestión de empleados</a>
	    </div>
	</div>

    <div class="canchas-container">
        <h2>Listado de canchas</h2>
        <?php if ($canchas->num_rows > 0): ?>
            <div class="canchas-grid">
                <?php foreach ($canchas as $cancha): ?>

                    <div class="cancha-card ">
                        <h3><?php echo htmlspecialchars($cancha['descripcion_zona']); ?></h3>
                        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($cancha['descripcion_formato_deporte']); ?></p>
                        <p><strong>Estado:</strong> <?php echo $cancha['descripcion_estado_zona']; ?></p>
                        <button 
                            class="btn btn-reservar" 
                            onclick="location.href='modulo_reservas.php?id_zona=<?php echo $cancha['id_zona']; ?>&fecha=<?php echo date('Y-m-d'); ?>'">
                            Reservar
                        </button>
                        <button 
                            class="btn btn-modificar" 
                            onclick="location.href='modificar_cancha.php?id_zona=<?php echo $cancha['id_zona']; ?>'">
                            Modificar
                        </button>
                        <button 
                            class="btn btn-eliminar" 
                            onclick="if(confirm('¿Desea eliminar esta cancha?')) location.href='includes/eliminar.php?id_zona=<?php echo $cancha['id_zona']; ?>&id_sucursal=<?php echo $id_sucursal ?>';">
                            Eliminar
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No hay canchas registradas en esta sucursal.</p>
        <?php endif; ?>

        <!-- Botón para agregar nueva cancha -->
        <div class="add-cancha">
            <button class="btn btn-agregar" onclick="location.href='../tablaZonasCanchas/tablaZonas.php?id_sucursal=<?php echo $id_sucursal; ?>'">Agregar Cancha</button>
        </div>

            <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Botón Eliminar
            document.querySelectorAll(".btn-eliminar").forEach(btn => {
                btn.addEventListener("click", function() {
                    const idZona = this.getAttribute("data-id");
                    const idSucursal = this.getAttribute("data-sucursal");

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = `includes/eliminar.php?id_zona=${idZona}&id_sucursal=${idSucursal}`;
                        }
                    });
                });
            });

            // Botón Modificar
            document.querySelectorAll(".btn-modificar").forEach(btn => {
                btn.addEventListener("click", function() {
                    const idZona = this.getAttribute("data-id");
                    const descripcion = this.getAttribute("data-descripcion");

                    // Cargar datos dinámicos para los selects
                    $.ajax({
                        url: './includes/obtener_datos_cancha.php',
                        method: 'GET',
                        success: function(data) {
                            const {
                                terrenos,
                                formatos,
                                estados
                            } = data;

                            // Construir las opciones de los selects
                            const terrenoOptions = terrenos.map(terreno => `<option value="${terreno.id_tipo_terreno}">${terreno.descripcion_tipo_terreno}</option>`).join('');
                            const formatoOptions = formatos.map(formato => `<option value="${formato.id_formato_deporte}">${formato.descripcion_formato_deporte}</option>`).join('');
                            const estadoOptions = estados.map(estado => `<option value="${estado.id_estado_zona}">${estado.descripcion_estado_zona}</option>`).join('');

                            Swal.fire({
                                title: 'Modificar Cancha',
                                html: `
                        <form id="form-modificar" style="text-align: left;">
                            <div style="margin-bottom: 1rem;">
                                <label for="descripcion" style="display: block; margin-bottom: 0.5rem;">Descripción</label>
                                <input type="text" id="descripcion" class="swal2-input" style="padding: 0.5rem;">
                            </div>
                            
                            <div style="margin-bottom: 1rem;">
                                <label for="tipo-terreno" style="display: block; margin-bottom: 0.5rem;">Tipo de Terreno</label>
                                <select id="tipo-terreno" class="swal2-select" style="padding: 0.5rem;">
                                    ${terrenoOptions}
                                </select>
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <label for="formato-deporte" style="display: block; margin-bottom: 0.5rem;">Formato de Deporte</label>
                                <select id="formato-deporte" class="swal2-select" style="padding: 0.5rem;">
                                    ${formatoOptions}
                                </select>
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <label for="estado" style="display: block; margin-bottom: 0.5rem;">Estado</label>
                                <select id="estado" class="swal2-select" style="padding: 0.5rem;">
                                    ${estadoOptions}
                                </select>
                            </div>
                        </form>
                    `,
                                focusConfirm: false,
                                showCancelButton: true,
                                confirmButtonText: 'Guardar',
                                cancelButtonText: 'Cancelar',
                                preConfirm: () => {
                                    const descripcion = Swal.getPopup().querySelector('#descripcion').value;
                                    const tipoTerreno = Swal.getPopup().querySelector('#tipo-terreno').value;
                                    const formatoDeporte = Swal.getPopup().querySelector('#formato-deporte').value;
                                    const estado = Swal.getPopup().querySelector('#estado').value;

                                    if (!descripcion || !tipoTerreno || !formatoDeporte || !estado) {
                                        Swal.showValidationMessage(`Todos los campos son obligatorios`);
                                    }

                                    return {
                                        idZona,
                                        descripcion,
                                        tipoTerreno,
                                        formatoDeporte,
                                        estado
                                    };
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const {
                                        idZona,
                                        descripcion,
                                        tipoTerreno,
                                        formatoDeporte,
                                        estado
                                    } = result.value;

                                    // Realizar la petición AJAX para actualizar los datos
                                    $.ajax({
                                        url: './includes/modificar.php',
                                        method: 'POST',
                                        data: {
                                            id_zona: idZona,
                                            descripcion,
                                            tipo_terreno: tipoTerreno,
                                            formato_deporte: formatoDeporte,
                                            estado
                                        },
                                        success: function(response) {
                                            Swal.fire('¡Modificado!', 'Los datos se han actualizado correctamente.', 'success')
                                                .then(() => location.reload());
                                        },
                                        error: function() {
                                            Swal.fire('Error', 'Hubo un problema al modificar la cancha.', 'error');
                                        }
                                    });
                                }
                            });
                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudieron cargar los datos dinámicos.', 'error');
                        }
                    });
                });
            });
        });
    </script>
    </div>
</body>
</html>

