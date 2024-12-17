<?php
session_start();
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once(RUTA . "config/database/db_functions/personas.php");

// Verificar sesión
if (!isset($_SESSION['id_persona']) || !isset($_SESSION['id_usuario'])) {
    die("Acceso denegado. Por favor inicie sesión.");
}

$idPersona = $_SESSION['id_persona'];
$idUsuario = $_SESSION['id_usuario'];

// Obtener persona asociada al usuario
$registrosPersona = ObtenerPersonaPorUsuario($idUsuario);
if (!$registrosPersona) {
    die("Error al obtener datos de la persona.");
}
$objetoPersona = $registrosPersona->fetch_assoc();
$relaPersonas = $objetoPersona['id_persona'];

// Validar y sanitizar parámetros de entrada
$validColumns = ['sucursal', 'zona', 'formato', 'fecha_reserva', 'horario_inicio', 'monto_base', 'estado'];
$sort = isset($_GET['sort']) && in_array($_GET['sort'], $validColumns) ? $_GET['sort'] : 'fecha_reserva';
$order = (isset($_GET['order']) && strtolower($_GET['order']) === 'desc') ? 'desc' : 'asc';
$limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($conexion, $_GET['start_date']) : null;
$end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($conexion, $_GET['end_date']) : null;

// Construir cláusula WHERE
$whereClause = "WHERE r.rela_persona = $relaPersonas";
if ($start_date) {
    $whereClause .= " AND r.fecha_reserva >= '$start_date'";
}
if ($end_date) {
    $whereClause .= " AND r.fecha_reserva <= '$end_date'";
}

// Obtener el número total de registros (con filtros aplicados)
$countQuery = "SELECT COUNT(*) AS total
               FROM reserva r
               JOIN persona p ON r.rela_persona = p.id_persona
               JOIN horario h ON r.rela_horario = h.id_horario
               JOIN zona z ON r.rela_zona = z.id_zona
               JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
               JOIN formato_deporte fd ON z.rela_formato_deporte = fd.id_formato_deporte
               JOIN tipo_terreno tt ON z.rela_tipo_terreno = tt.id_tipo_terreno
               JOIN control co ON co.rela_reserva = r.id_reserva
               JOIN estado_reserva er ON r.rela_estado_reserva = er.id_estado_reserva
               $whereClause";

$totalResult = mysqli_query($conexion, $countQuery);
if (!$totalResult) {
    die("Error en la consulta de conteo: " . mysqli_error($conexion));
}
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

// Consulta principal (con paginación y filtros)
$query = "SELECT r.id_reserva, 
                    p.nombre, 
                    r.comprobante,
                    r.fecha_reserva, 
                    h.horario_inicio,
                    s.descripcion_sucursal AS sucursal, 
                    z.descripcion_zona AS zona, 
                    fd.descripcion_formato_deporte AS formato,
                    er.descripcion_estado_reserva AS estado,
                    co.monto_base
          FROM reserva r
          JOIN persona p ON r.rela_persona = p.id_persona
          JOIN horario h ON r.rela_horario = h.id_horario
          JOIN zona z ON r.rela_zona = z.id_zona
          JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
          JOIN formato_deporte fd ON z.rela_formato_deporte = fd.id_formato_deporte
          JOIN tipo_terreno tt ON z.rela_tipo_terreno = tt.id_tipo_terreno
          JOIN control co ON co.rela_reserva = r.id_reserva
          JOIN estado_reserva er ON r.rela_estado_reserva = er.id_estado_reserva
          $whereClause
          ORDER BY $sort $order
          LIMIT $limit OFFSET $offset";

$misReservas = mysqli_query($conexion, $query);
if (!$misReservas) {
    die("Error en la consulta principal: " . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/aside.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="../../css/tablas.css">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
    </style>
</head>

<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php") ?>

    <div class="containerTable">
        <div class="containerRegistros">
            <h1>Mis Reservas</h1>

            <div class="accionencabezado">
                <div class="export">
                    <button id="exportarpdf" onclick="window.location.href='exportar_pdf.php';">
                        <i class="fa-regular fa-file-pdf"></i>
                    </button>
                </div>

                <!-- Selector para limitar registros -->
                <form method="GET" action="">
                    <div class="parte1">
                        <label for="limit">Mostrar</label>
                        <select id="limit" name="limit" onchange="this.form.submit()">
                            <option value="5" <?php if ($limit == 5) echo 'selected'; ?>>5</option>
                            <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                            <option value="20" <?php if ($limit == 20) echo 'selected'; ?>>20</option>
                        </select>
                        <label for="limit"> registros por página.</label>
                    </div>

                    <!-- Selector de rango de fechas -->
                    <div class="parte2">
                        <label for="start_date">Desde:</label>
                        <input type="date" id="start_date" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
                        <label for="end_date">Hasta:</label>
                        <input type="date" id="end_date" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">

                        <button type="submit">Filtrar por fecha</button>
                    </div>
                </form>
            </div>

            <div class="tableRegistros">
                <table>
                    <thead>
                        <tr>
                            <th>
                                <a href="?sort=sucursal&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Sucursal <?php echo $sort === 'sucursal' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=zona&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Zona <?php echo $sort === 'zona' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=formato&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Formato <?php echo $sort === 'formato' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=fecha_reserva&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Fecha Reserva <?php echo $sort === 'fecha_reserva' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=horario_inicio&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Hora Inicio <?php echo $sort === 'horario_inicio' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=monto_base&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Monto Base <?php echo $sort === 'monto_base' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=estado&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Estado <?php echo $sort === 'estado' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($misReservas as $row) { ?>
                            <tr id="row-<?php echo $row['id_reserva']; ?>">
                                <td><?php echo htmlspecialchars($row['sucursal']); ?></td>
                                <td><?php echo htmlspecialchars($row['zona']); ?></td>
                                <td><?php echo htmlspecialchars($row['formato']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_reserva']); ?></td>
                                <td><?php echo htmlspecialchars($row['horario_inicio']); ?></td>
                                <td>$<?php echo htmlspecialchars($row['monto_base']); ?></td>
                                <td id="estado-<?php echo $row['id_reserva']; ?>">
                                    <?php echo htmlspecialchars($row['estado']); ?>
                                </td>
                                <td>
                                    <button class="btn-cancelar" onclick="cancelarReserva(<?php echo $row['id_reserva']; ?>)">
                                        Cancelar
                                    </button>
                                    <button class="btn-vermas" onclick="vermas(<?php echo $row['id_reserva']; ?>)">
                                        Ver Mas
                                    </button>
                                    <button
                                        class="ver-comprobante
                                        <?php echo !empty($row['comprobante']) ? 'con-comprobante' : 'sin-comprobante' ?>
                                    "
                                        <?php
                                        if (!empty($row['comprobante'])) {
                                            echo "data-ruta=" . htmlspecialchars($row['comprobante']);
                                        }
                                        ?>
                                        data-id>Ver Comprobante</button>
                                    <button class="btn-comprobante" data-id="<?php echo $row['id_reserva'] ?>">Adjuntar comprobante</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginador -->
            <div class="paginador">
                <p>Total de registros encontrado: <?php echo $totalRecords; ?></p>

                <div class="paginas">
                    <?php if ($page > 1) { ?>
                        <a href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>">&laquo; Anterior</a>
                    <?php } ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                        <a href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>"
                            class="<?php echo $i == $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php } ?>

                    <?php if ($page < $totalPages) { ?>
                        <a href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>">Siguiente &raquo;</a>
                    <?php } ?>
                </div>

                <p>Total de paginas: <?php echo $totalPages; ?></p>
            </div>
        </div>
    </div>

    <?php include(RUTA . "includes/footer.php") ?>

    <script src="../../libs/jquery-3.7.1.min.js"></script>
    <script src="../../libs/sweetalert2.all.min.js"></script>
    <script src="../../js/aside.js"></script>
    <script src="../../js/header.js"></script>
    <script src="../../js/terminoscondiciones.js"></script>

    <script>
        function vermas(idReserva) {
            // Realizar la solicitud AJAX para obtener los datos
            $.ajax({
                url: 'verMas.php',
                type: 'POST',
                data: {
                    id_reserva: idReserva
                },
                success: function(response) {
                    const reserva = JSON.parse(response);

                    // Si no hay error, abrir el modal con SweetAlert2
                    if (reserva.error) {
                        Swal.fire({
                            title: 'Error',
                            text: reserva.error,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        // Crear el contenido del modal
                        const contenido = `
                    <strong>Complejo:</strong> ${reserva.descripcion_complejo}<br>
                    <strong>Sucursal:</strong> ${reserva.descripcion_sucursal}<br>
                    <strong>Direccion:</strong> ${reserva.direccion}<br>
                    <strong>Zona:</strong> ${reserva.descripcion_zona}<br>
                    <strong>Formato:</strong> ${reserva.descripcion_formato_deporte}<br>
                    <strong>Fecha Reserva:</strong> ${reserva.fecha_reserva}<br>
                    <strong>Horario:</strong> ${reserva.horario_inicio} - ${reserva.horario_fin}<br>
                    <strong>Monto Reserva:</strong> $${reserva.monto_final}<br>
                    <strong>Estado:</strong> ${reserva.descripcion_estado_reserva}
                `;

                        // Abrir el modal con los datos obtenidos
                        Swal.fire({
                            title: 'Detalles de la Reserva',
                            html: contenido,
                            icon: 'info',
                            confirmButtonText: 'Cerrar'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al obtener los datos de la reserva.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    </script>

    <script>
        function cancelarReserva(idReserva) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cancelar reserva',
                cancelButtonText: 'No, mantener reserva'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar cargando
                    Swal.fire({
                        title: 'Cancelando reserva...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Realizar la solicitud AJAX
                    $.ajax({
                        url: 'cancelar.php',
                        type: 'POST',
                        data: {
                            id_reserva: idReserva
                        },
                        success: function(response) {
                            Swal.close(); // Cerrar el modal de carga

                            // Mostrar confirmación
                            Swal.fire({
                                title: 'Reserva cancelada',
                                text: 'Tu reserva ha sido cancelada exitosamente.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Actualizar la fila correspondiente
                                $(`#estado-${idReserva}`).text('Cancelado');
                                $(`#row-${idReserva}`).fadeOut(500, function() {
                                    $(this).remove();
                                });
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo cancelar la reserva. Intenta nuevamente.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }
    </script>

    <script>
        $(() => {
            //AÑADIR COMPROBANTE
            $(document).on("click", ".btn-comprobante", function() {
                let idReserva = $(this).data('id');
                adjuntarComprobante(idReserva);
            });

            // NO TIENE COMPROBANTE (CLICK VER COMPROBANTE)
            $(document).on("click", ".sin-comprobante", function() {
                alert();
            });

            // SI TIENE COMPROBANTE (CLICK VER COMPROBANTE)
            $(document).on("click", ".con-comprobante", function() {
                rutaComprobante = $(this).data('ruta');
                verComprobante(rutaComprobante);
            });
        });

        function adjuntarComprobante(idReserva) {
            Swal.fire({
                title: 'Subir Comprobante',
                html: `
                    <h3>Subir comprobante para la reserva ${idReserva}</h3>
                    <form id="form-comprobante" enctype="multipart/form-data">
                        <input type="hidden" name="id_reserva" value="${idReserva}">
                        <input type="file" name="comprobante" accept=".pdf" class="swal2-input">
                    </form>
                `,
                confirmButtonText: 'Subir',
                showCancelButton: true,
                preConfirm: () => {
                    const form = document.getElementById('form-comprobante');
                    const formData = new FormData(form);
                    return fetch('subir_comprobante.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .catch(error => {
                            console.error('Error en la respuesta:', error);
                            Swal.showValidationMessage(`Error: No se pudo procesar la respuesta del servidor.`);
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Comprobante Subido',
                        text: 'El comprobante fue subido con éxito.'
                    });
                }
            });
        }

        function verComprobante(rutaComprobante) {
            Swal.fire({
                title: 'Comprobante',
                html: `
                    <embed src="${rutaComprobante}" type="application/pdf" width="100%" height="500px">
                `,
                showCloseButton: true,
                showConfirmButton: false,
                width: '800px'
            });
        }
    </script>
</body>

</html>
<?php
// Liberar resultados y cerrar la conexión
mysqli_free_result($misReservas);
mysqli_close($conexion);
?>