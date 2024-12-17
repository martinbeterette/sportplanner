<?php
session_start();
require_once("../../../config/root_path.php");
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
// $relaPersonas = $objetoPersona['id_persona'];
$relaPersonas = 24;

// Validar y sanitizar parámetros de entrada
$validColumns = ['descripcion_complejo', 'descripcion_membresia', 'descuento', 'precio_membresia', 'fecha_expiracion'];
$sort = isset($_GET['sort']) && in_array($_GET['sort'], $validColumns) ? $_GET['sort'] : 'fecha_expiracion';
$order = (isset($_GET['order']) && strtolower($_GET['order']) === 'desc') ? 'desc' : 'asc';
$limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($conexion, $_GET['start_date']) : null;
$end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($conexion, $_GET['end_date']) : null;

// Construir cláusula WHERE
$whereClause = "WHERE s.rela_persona = $relaPersonas";
if ($start_date) {
    $whereClause .= " AND fecha_expiracion >= '$start_date'";
}
if ($end_date) {
    $whereClause .= " AND fecha_expiracion <= '$end_date'";
}

// Obtener el número total de registros (con filtros aplicados)
$countQuery = "SELECT COUNT(*) AS total
                FROM membresia m 
                JOIN socio s ON m.id_membresia = s.rela_membresia
                JOIN persona p ON s.rela_persona = p.id_persona
                JOIN documento d ON p.id_persona = d.rela_persona
                JOIN contacto con ON con.rela_persona = p.id_persona
                JOIN complejo c ON s.rela_complejo = c.id_complejo
                $whereClause";

$totalResult = mysqli_query($conexion, $countQuery);

if (!$totalResult) {
    die("Error en la consulta de conteo: " . mysqli_error($conexion));
}
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

// Consulta principal (con paginación y filtros)
$query = "SELECT c.descripcion_complejo, 
                    m.descripcion_membresia, 
                    m.descuento, 
                    m.precio_membresia, 
                    s.fecha_alta, 
                    s.fecha_afiliacion, 
                    s.fecha_expiracion, 
                    p.nombre, 
                    p.apellido, 
                    d.descripcion_documento, 
                    con.descripcion_contacto, 
                    s.id_socio,
                    s.estado AS estado_socio
        FROM membresia m 
        JOIN socio s ON m.id_membresia = s.rela_membresia
        JOIN persona p ON s.rela_persona = p.id_persona
        JOIN documento d ON p.id_persona = d.rela_persona
        JOIN contacto con ON con.rela_persona = p.id_persona
        JOIN complejo c ON s.rela_complejo = c.id_complejo
        $whereClause
        ORDER BY $sort $order
        LIMIT $limit OFFSET $offset";

$misMembresias = mysqli_query($conexion, $query);
if (!$misMembresias) {
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
    <link rel="stylesheet" href="../../../css/header.css">
    <link rel="stylesheet" href="../../../css/aside.css">
    <link rel="stylesheet" href="../../../css/footer.css">
    <link rel="stylesheet" href="../../../css/tablas.css">
</head>

<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php") ?>

    <div class="containerTable">
        <div class="containerRegistros">
            <h1>Membresias</h1>

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
                                <a href="?sort=descripcion_complejo&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Complejo <?php echo $sort === 'descripcion_complejo' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=descripcion_membresia&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Membresia <?php echo $sort === 'descripcion_membresia' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=descuento&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Descuento %<?php echo $sort === 'descuento' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=precio_membresia&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Precio $<?php echo $sort === 'precio_membresia' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=fecha_expiracion&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Expiracion <?php echo $sort === 'fecha_expiracion' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=estado_socio&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
                                    Estado <?php echo $sort === 'estado_socio' ? ($order === 'asc' ? '▲' : '▼') : ''; ?>
                                </a>
                            </th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($misMembresias as $row) { ?>
                            <tr id="row-<?php echo $row['id_socio']; ?>">
                                <td><?php echo htmlspecialchars($row['descripcion_complejo']); ?></td>
                                <td><?php echo htmlspecialchars($row['descripcion_membresia']); ?></td>
                                <td><?php echo htmlspecialchars($row['descuento'] . "%"); ?></td>
                                <td><?php echo htmlspecialchars($row['precio_membresia'] . "$"); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_expiracion']); ?></td>
                                <td id="estado-<?php echo $row['id_socio']; ?>">
                                    <?php echo htmlspecialchars($row['estado_socio']); ?>
                                </td>
                                <td>
                                    <button class="btn-vermas" onclick="vermas(<?php echo $relaPersonas ?>)">
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

    <script src="../../../libs/jquery-3.7.1.min.js"></script>
    <script src="../../../libs/sweetalert2.all.min.js"></script>
    <script src="../../../js/header.js"></script>
    <script src="../../../js/aside.js"></script>
    <script src="../../../js/terminoscondiciones.js"></script>

    <script>
        function vermas(rela_persona) {
            // Realizar la solicitud AJAX para obtener los datos
            $.ajax({
                url: 'verMas.php',
                type: 'POST',
                data: {
                    rela_persona: rela_persona
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
                        <strong>Nombre:</strong> ${reserva.nombre}<br>
                        <strong>Apellido:</strong> ${reserva.apellido}<br>
                        <strong>Documento:</strong> ${reserva.descripcion_documento}<br>
                        <strong>Complejo:</strong> ${reserva.descripcion_complejo}<br>
                        <strong>Membresia:</strong> ${reserva.descripcion_membresia}<br>
                        <strong>Descuento:</strong> ${reserva.descuento}%<br>
                        <strong>precio:</strong> ${reserva.precio_membresia}$<br>
                        <strong>Estado:</strong> ${reserva.estado_socio}<br>
                        <strong>Fecha Insercion:</strong> ${reserva.fecha_alta}<br>
                        <strong>Renovacion:</strong> ${reserva.fecha_afiliacion}<br>
                        <strong>Expiracion:</strong> ${reserva.fecha_expiracion}<br>
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
</body>

</html>
<?php
// Liberar resultados y cerrar la conexión
mysqli_free_result($misMembresias);
mysqli_close($conexion);
?>