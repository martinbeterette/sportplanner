<?php
// ESTE ES PARA EL EMPLEADO
session_start();
require_once("includes/functions.php");
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

$id_sucursal = false;
if ($_SESSION['id_perfil'] == 3) {
    $id_persona = $_SESSION['id_persona'];
    $id_usuario = $_SESSION['id_usuario'];
    $id_sucursal = obtenerComplejoPorPersona($id_persona, $id_usuario);
}

if (!$id_sucursal) {
    header("Location: " . BASE_URL . "errors/error403.php?no_tiene_acceso");
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

if (!$sucursal) {
    echo "No se encontró la sucursal.";
    die;
}

// Consulta para listar las canchas asociadas a la sucursal
$sql_canchas = "
    SELECT 
        z.id_zona,
        z.descripcion_zona,
        estado_zona.descripcion_estado_zona,
        descripcion_tipo_terreno,
        fd.descripcion_formato_deporte
    FROM zona z
    JOIN formato_deporte fd ON z.rela_formato_deporte = fd.id_formato_deporte
    JOIN estado_zona ON rela_estado_zona = id_estado_zona
    JOIN tipo_terreno ON rela_tipo_terreno = id_tipo_terreno
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/footer.css" ?>">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php"); ?>

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
            <a href="<?php echo BASE_URL . "php/tarifas/tarifa_empleado/tabla_tarifa.php"; ?>" class="btn btn-gestion">Gestión de tarifas</a>
            <a href="<?php echo BASE_URL . "php/socio/gestion_socios/index.php" ?>" class="btn btn-gestion">Gestión de socios</a>
            <a href="<?php echo BASE_URL . "php/tablaEmpleados/gestion_empleado/tablaEmpleados.php" ?>" class="btn btn-gestion">Gestión de empleados</a>
        </div>
    </div>

    <div class="canchas-container">
        <h2>Listado de canchas</h2>
        <?php if ($canchas->num_rows > 0): ?>
            <div class="canchas-grid">
                <?php foreach ($canchas as $cancha): ?>
                    <div class="cancha-card">
                        <h3><?php echo htmlspecialchars($cancha['descripcion_zona']); ?></h3>
                        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($cancha['descripcion_formato_deporte']); ?></p>
                        <p><strong>Terreno:</strong> <?php echo htmlspecialchars($cancha['descripcion_tipo_terreno']); ?></p>
                        <p><strong>Estado:</strong> <?php echo $cancha['descripcion_estado_zona']; ?></p>
                        <button
                            class="btn btn-reservar"
                            onclick="location.href='modulo_reservas.php?id_zona=<?php echo $cancha['id_zona']; ?>&fecha=<?php echo date('Y-m-d'); ?>'">
                            Reservar
                        </button>
                        <button
                            class="btn btn-modificar"
                            data-id="<?php echo $cancha['id_zona']; ?>"
                            data-descripcion="<?php echo htmlspecialchars($cancha['descripcion_zona']); ?>"
                            data-tipo="<?php echo htmlspecialchars($cancha['descripcion_formato_deporte']); ?>"
                            data-terreno="<?php echo htmlspecialchars($cancha['descripcion_tipo_terreno']); ?>"
                            data-estado="<?php echo htmlspecialchars($cancha['descripcion_estado_zona']); ?>">
                            Modificar
                        </button>
                        <button
                            class="btn btn-eliminar"
                            data-id="<?php echo $cancha['id_zona']; ?>"
                            data-sucursal="<?php echo $id_sucursal; ?>">
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
            <button class="btn btn-agregar" onclick="location.href='agregar_cancha.php?id_sucursal=<?php echo $id_sucursal; ?>'">Agregar Cancha</button>
        </div>
    </div>

    <?php include(RUTA . "includes/footer.php"); ?>

    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/terminoscondiciones.js" ?>"></script>

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
            // document.querySelectorAll(".btn-modificar").forEach(btn => {
            //     btn.addEventListener("click", function() {
            //         const idZona = this.getAttribute("data-id");
            //         const descripcion = this.getAttribute("data-descripcion");
            //         const estado = this.getAttribute("data-estado");
            //         const tipo = this.getAttribute("data-tipo");
            //         const terreno = this.getAttribute("data-terreno");

            //         // Cargar datos dinámicos para los selects
            //         $.ajax({
            //             url: '../includes/obtener_datos_cancha.php',
            //             method: 'GET',
            //             success: function(data) {
            //                 const {
            //                     terrenos,
            //                     formatos,
            //                     estados
            //                 } = data;

            //                 // Construir las opciones de los selects
            //                 // const terrenoOptions = terrenos.map(terreno => `<option value="${terreno.id_tipo_terreno}" ${terreno.descripcion_tipo_terreno === terreno ? 'selected' : ''}>${terreno.descripcion_tipo_terreno}</option>`).join('');
            //                 // const formatoOptions = formatos.map(formato => `<option value="${formato.id_formato_deporte}" ${formato.descripcion_formato_deporte === tipo ? 'selected' : ''}>${formato.descripcion_formato_deporte}</option>`).join('');
            //                 // const estadoOptions = estados.map(estado => `<option value="${estado.id_estado_zona}" ${estado.descripcion_estado_zona === estado ? 'selected' : ''}>${estado.descripcion_estado_zona}</option>`).join('');
            //                 const terrenoOptions = terrenos.map(terrenoItem =>
            //                     `<option value="${terrenoItem.id_tipo_terreno}" ${terrenoItem.descripcion_tipo_terreno === terreno ? 'selected' : ''}>
            //                             ${terrenoItem.descripcion_tipo_terreno}
            //                         </option>`
            //                 ).join('');

            //                 const formatoOptions = formatos.map(formatoItem =>
            //                     `<option value="${formatoItem.id_formato_deporte}" ${formatoItem.descripcion_formato_deporte === tipo ? 'selected' : ''}>
            //                             ${formatoItem.descripcion_formato_deporte}
            //                         </option>`
            //                 ).join('');

            //                 const estadoOptions = estados.map(estadoItem =>
            //                     `<option value="${estadoItem.id_estado_zona}" ${estadoItem.descripcion_estado_zona === estado ? 'selected' : ''}>
            //                             ${estadoItem.descripcion_estado_zona}
            //                         </option>`
            //                 ).join('');


            //                 Swal.fire({
            //                     title: 'Modificar Cancha',
            //                     html: `
            //             <form id="form-modificar" style="text-align: left;">
            //                 <div style="margin-bottom: 1rem;">
            //                     <label for="descripcion" style="display: block; margin-bottom: 0.5rem;">Descripción</label>
            //                     <input type="text" id="descripcion" class="swal2-input" style="padding: 0.5rem;" value="${descripcion}">
            //                 </div>

            //                 <div style="margin-bottom: 1rem;">
            //                     <label for="tipo-terreno" style="display: block; margin-bottom: 0.5rem;">Tipo de Terreno</label>
            //                     <select id="tipo-terreno" class="swal2-select" style="padding: 0.5rem;">
            //                         ${terrenoOptions}
            //                     </select>
            //                 </div>

            //                 <div style="margin-bottom: 1rem;">
            //                     <label for="formato-deporte" style="display: block; margin-bottom: 0.5rem;">Formato de Deporte</label>
            //                     <select id="formato-deporte" class="swal2-select" style="padding: 0.5rem;">
            //                         ${formatoOptions}
            //                     </select>
            //                 </div>

            //                 <div style="margin-bottom: 1rem;">
            //                     <label for="estado" style="display: block; margin-bottom: 0.5rem;">Estado</label>
            //                     <select id="estado" class="swal2-select" style="padding: 0.5rem;">
            //                         ${estadoOptions}
            //                     </select>
            //                 </div>
            //             </form>
            //         `,
            //                     focusConfirm: false,
            //                     showCancelButton: true,
            //                     confirmButtonText: 'Guardar',
            //                     cancelButtonText: 'Cancelar',
            //                     preConfirm: () => {
            //                         const descripcion = document.getElementById('descripcion').value;
            //                         const tipoTerreno = document.getElementById('tipo-terreno').value;
            //                         const formatoDeporte = document.getElementById('formato-deporte').value;
            //                         const estado = document.getElementById('estado').value;

            //                         if (!descripcion.trim()) {
            //                             Swal.showValidationMessage('La descripción no puede estar vacía.');
            //                             return false;
            //                         }

            //                         // Devolver los datos recopilados
            //                         return {
            //                             idZona,
            //                             descripcion,
            //                             tipoTerreno,
            //                             formatoDeporte,
            //                             estado
            //                         };
            //                     }
            //                 }).then((result) => {
            //                     if (result.isConfirmed) {
            //                         const {
            //                             idZona,
            //                             descripcion,
            //                             tipoTerreno,
            //                             formatoDeporte,
            //                             estado
            //                         } = result.value;

            //                         // Realizar la petición AJAX para actualizar los datos
            //                         preConfirm: () => {
            //                             const formData = {
            //                                 idZona: idZona,
            //                                 descripcion: descripcion,
            //                                 tipoTerreno: tipoTerreno,
            //                                 formatoDeporte: formatoDeporte,
            //                                 estado: estado
            //                             };

            //                             console.log(formData);

            //                             return $.ajax({
            //                                 url: '../includes/modificar.php',
            //                                 method: 'POST',
            //                                 data: formData,
            //                                 success: function(response) {
            //                                     if (response.success) {
            //                                         Swal.fire('Éxito', 'Cancha modificada correctamente.', 'success')
            //                                             .then(() => location.reload());
            //                                     } else {
            //                                         Swal.showValidationMessage(response.message || 'Error al modificar la cancha.');
            //                                     }
            //                                 },
            //                                 error: function() {
            //                                     Swal.showValidationMessage('Error al comunicarse con el servidor.');
            //                                 }
            //                             });
            //                         }
            //                     }
            //                 });
            //             },
            //             error: function() {
            //                 Swal.fire('Error', 'No se pudieron cargar los datos dinámicos.', 'error');
            //             }
            //         });
            //     });
            // });

            document.querySelectorAll(".btn-modificar").forEach(btn => {
                btn.addEventListener("click", function() {
                    const idZona = this.getAttribute("data-id");
                    const descripcion = this.getAttribute("data-descripcion");
                    const estado = this.getAttribute("data-estado");
                    const tipo = this.getAttribute("data-tipo");
                    const terreno = this.getAttribute("data-terreno");

                    // Cargar datos dinámicos para los selects
                    $.ajax({
                        url: '../includes/obtener_datos_cancha.php',
                        method: 'GET',
                        dataType: 'json', // Asegurarse de que el servidor retorne un JSON válido
                        success: function(data) {
                            const {
                                terrenos,
                                formatos,
                                estados
                            } = data;

                            // Construir las opciones de los selects
                            const terrenoOptions = terrenos.map(terrenoItem =>
                                `<option value="${terrenoItem.id_tipo_terreno}" ${terrenoItem.descripcion_tipo_terreno === terreno ? 'selected' : ''}>
                        ${terrenoItem.descripcion_tipo_terreno}
                    </option>`
                            ).join('');

                            const formatoOptions = formatos.map(formatoItem =>
                                `<option value="${formatoItem.id_formato_deporte}" ${formatoItem.descripcion_formato_deporte === tipo ? 'selected' : ''}>
                        ${formatoItem.descripcion_formato_deporte}
                    </option>`
                            ).join('');

                            const estadoOptions = estados.map(estadoItem =>
                                `<option value="${estadoItem.id_estado_zona}" ${estadoItem.descripcion_estado_zona === estado ? 'selected' : ''}>
                        ${estadoItem.descripcion_estado_zona}
                    </option>`
                            ).join('');

                            // Mostrar el modal de SweetAlert
                            Swal.fire({
                                title: 'Modificar Cancha',
                                html: `
                        <form id="form-modificar" style="text-align: left;">
                            <div style="margin-bottom: 1rem;">
                                <label for="descripcion" style="display: block; margin-bottom: 0.5rem;">Descripción</label>
                                <input type="text" id="descripcion" class="swal2-input" style="padding: 0.5rem;" value="${descripcion}">
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
                                    const descripcion = document.getElementById('descripcion').value;
                                    const tipoTerreno = document.getElementById('tipo-terreno').value;
                                    const formatoDeporte = document.getElementById('formato-deporte').value;
                                    const estado = document.getElementById('estado').value;

                                    // Validar datos
                                    if (!descripcion.trim()) {
                                        Swal.showValidationMessage('La descripción no puede estar vacía.');
                                        return false;
                                    }

                                    // Devolver los datos recopilados
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
                                        url: '../includes/modificar.php',
                                        method: 'POST',
                                        data: {
                                            idZona: idZona,
                                            descripcion: descripcion,
                                            tipoTerreno: tipoTerreno,
                                            formatoDeporte: formatoDeporte,
                                            estado: estado
                                        },
                                        dataType: 'json', // Asegúrate de que la respuesta sea JSON
                                        success: function(response) {
                                            console.log(response); // Para depuración
                                            if (response.success) {
                                                Swal.fire('Éxito', response.message || 'Cancha modificada correctamente.', 'success')
                                                    .then(() => location.reload());
                                            } else {
                                                Swal.fire('Error', response.message || 'No se pudo modificar la cancha.', 'error');
                                            }
                                        },
                                        error: function() {
                                            Swal.fire('Error', 'No se pudo comunicar con el servidor.', 'error');
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
</body>

</html>