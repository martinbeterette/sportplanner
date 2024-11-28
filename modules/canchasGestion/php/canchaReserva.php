<?php
session_start();
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once(RUTA . "config/database/db_functions/personas.php");

$idUsuario = $_SESSION['id_usuario'];

$id_sucursal = $_GET['id_sucursal'];

$registrosPersona = ObtenerPersonaPorUsuario($idUsuario);

$objetoPersona = $registrosPersona->fetch_assoc();

$relaPersonas = $objetoPersona['id_persona'];

// Consulta SQL
$sql = "SELECT s.id_sucursal, c.id_complejo, z.rela_sucursal, p.nombre, z.descripcion_zona, r.fecha_reserva, 
        h.horario_inicio, h.horario_fin, r.monto_pagado, er.descripcion_estado_reserva
        FROM sucursal s
        JOIN complejo c ON s.rela_complejo = c.id_complejo
        JOIN zona z ON z.rela_sucursal = s.id_sucursal
        JOIN reserva r ON z.id_zona = r.rela_zona
        JOIN horario h ON r.rela_horario = h.id_horario
        JOIN persona p ON r.rela_persona = p.id_persona
        JOIN estado_reserva er ON r.rela_estado_reserva = er.id_estado_reserva
        WHERE z.rela_sucursal = $id_sucursal";

// Ejecutar la consulta
$gestionReserva = $conexion->query($sql);

if (!$gestionReserva) {
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/index.css' ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'modules/misReservasUsuario/misReservas.css' ?>">
</head>

<body>
    <div class="container">
        <header>
            <?php include(RUTA . 'modules/header/tituloWeb/php/tituloWeb.php'); ?>
            <?php include(RUTA . 'modules/header/filtroBusqueda/php/filtroBusqueda.php'); ?>
            <?php include(RUTA . 'modules/header/btnPerfil/php/btnPerfil.php'); ?>
        </header>
        <main>
            <?php include(RUTA . 'modules/asideMenu/php/asideMenu.php'); ?>
            <div class="containerRegistros">
                <h1>Mis Reservas</h1>
                <div class="tableRegistros">
                    <input type="text" placeholder="Buscar Registro...">
                    <table>
                        <thead>
                            <tr>
                                <th>id sucursal</th>
                                <th>rela sucursal</th>
                                <th>nombre persona</th>
                                <th>descripcion zona</th>
                                <th>fecha reserva</th>
                                <th>hora inicio</th>
                                <th>hora fin</th>
                                <th>monto pagado</th>
                                <th>estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gestionReserva as $row) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id_sucursal']); ?></td>
                                    <td><?php echo htmlspecialchars($row['rela_sucursal']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['descripcion_zona']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fecha_reserva']); ?></td>
                                    <td><?php echo htmlspecialchars($row['horario_inicio']); ?></td>
                                    <td><?php echo htmlspecialchars($row['horario_fin']); ?></td>
                                    <td><?php echo htmlspecialchars($row['monto_pagado']); ?></td>
                                    <td><?php echo htmlspecialchars($row['descripcion_estado_reserva']); ?></td>
                                    <td>Cancelar</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <footer>
            footer
        </footer>
    </div>
    <script src="<?php echo BASE_URL . '/js/jquery-3.7.1.min.js'; ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="<?php echo BASE_URL . 'modules/header/filtroBusqueda/js/ajaxDeportes.js'; ?>"></script>
    <script src="<?php echo BASE_URL . 'modules/header/filtroBusqueda/js/fechaFlatpickr.js'; ?>"></script>
    <script src="<?php echo BASE_URL . 'modules/header/btnPerfil/js/btnPerfil.js'; ?>"></script>
    <script src="<?php echo BASE_URL . 'modules/asideMenu/js/menuAside.js'; ?>"></script>
</body>

</html>
<?php
// Liberar resultados y cerrar la conexión
mysqli_free_result($gestionReserva);
mysqli_close($conexion);
?>