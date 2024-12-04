<?php
session_start();
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once(RUTA . "config/database/db_functions/personas.php");

$idPersona = $_SESSION['id_persona'];
$idUsuario = $_SESSION['id_usuario'];
$registrosPersona = ObtenerPersonaPorUsuario($idUsuario);
$objetoPersona = $registrosPersona->fetch_assoc();
$relaPersonas = $objetoPersona['id_persona'];

// Parámetros de ordenación
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'fecha_reserva'; // Columna predeterminada
$order = isset($_GET['order']) ? $_GET['order'] : 'asc'; // Orden predeterminado

// Validar los valores de sort y order para evitar inyección de SQL
$validColumns = ['sucursal', 'zona', 'formato', 'fecha_reserva', 'horario_inicio', 'monto_base', 'estado'];
if (!in_array($sort, $validColumns)) {
    $sort = 'fecha_reserva';
}
$order = ($order === 'desc') ? 'desc' : 'asc';

// Parámetros de paginación
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Número de registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$offset = ($page - 1) * $limit; // Desplazamiento para la consulta

// Obtener el número total de registros
$countQuery = "SELECT COUNT(*) AS total FROM reserva r WHERE r.rela_persona = 13";
$totalResult = mysqli_query($conexion, $countQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit); // Calcular el número total de páginas

// Consulta con paginación
$query = "SELECT r.id_reserva, p.nombre, 
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
          WHERE r.rela_persona = 13
          ORDER BY $sort $order
          LIMIT $limit OFFSET $offset";

$misReservas = mysqli_query($conexion, $query);

if (!$misReservas) {
    die("Error en la consulta: " . mysqli_error($conexion));
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
                <button id="exportarpdf" onclick="window.location.href='exportar_pdf.php';">
                    <i class="fa-regular fa-file-pdf"></i>
                </button>

                <!-- Selector para limitar registros -->
                <form method="GET" action="">
                    <label for="limit">Mostrar</label>
                    <select id="limit" name="limit" onchange="this.form.submit()">
                        <option value="5" <?php if ($limit == 5) echo 'selected'; ?>>5</option>
                        <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                        <option value="20" <?php if ($limit == 20) echo 'selected'; ?>>20</option>
                    </select>
                    <label for="limit"> registros por página</label>
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
                    <strong>Sucursal:</strong> ${reserva.descripcion_sucursal}<br>
                    <strong>Zona:</strong> ${reserva.descripcion_zona}<br>
                    <strong>Formato:</strong> ${reserva.descripcion_formato_deporte}<br>
                    <strong>Fecha Reserva:</strong> ${reserva.fecha_reserva}<br>
                    <strong>Hora Inicio:</strong> ${reserva.horario_inicio}<br>
                    <strong>Monto Base:</strong> $${reserva.monto_base}<br>
                    <strong>Estado:</strong> ${reserva.estado}
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
</body>

</html>
<?php
// Liberar resultados y cerrar la conexión
mysqli_free_result($misReservas);
mysqli_close($conexion);
?>