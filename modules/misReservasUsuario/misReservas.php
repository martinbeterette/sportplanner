<?php
session_start();
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once(RUTA . "config/database/db_functions/personas.php");

$idUsuario = $_SESSION['id_usuario'];

$registrosPersona = ObtenerPersonaPorUsuario($idUsuario);

$objetoPersona = $registrosPersona->fetch_assoc();

$relaPersonas = $objetoPersona['id_persona'];

$query = "SELECT r.id_reserva, p.nombre, r.fecha_alta, r.fecha_reserva, h.horario_inicio, h.horario_fin,
            s.descripcion_sucursal AS sucursal, z.descripcion_zona AS zona, fd.descripcion_formato_deporte AS formato,
            tt.descripcion_tipo_terreno AS superficie, monto_pagado, er.descripcion_estado_reserva AS estado
          FROM reserva r
          JOIN persona p ON r.rela_persona = p.id_persona
          JOIN horario h ON r.rela_horario = h.id_horario
          JOIN zona z ON r.rela_zona = z.id_zona
          JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
          JOIN formato_deporte fd ON z.rela_formato_deporte = fd.id_formato_deporte
          JOIN tipo_terreno tt ON z.rela_tipo_terreno = tt.id_tipo_terreno
          JOIN estado_reserva er ON r.rela_estado_reserva = er.id_estado_reserva
          WHERE r.rela_persona = $relaPersonas AND er.id_estado_reserva = 1";

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/index.css' ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'modules/misReservasUsuario/misReservas.css' ?>">
</head>

<body>
    <div class="container">

        <main>

            <div class="containerRegistros">
                <h1>Mis Reservas</h1>
                <div class="tableRegistros">
                    <input type="text" placeholder="Buscar Registro...">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>fecha alta</th>
                                <th>fecha reserva</th>
                                <th>hora inicio</th>
                                <th>hora fin</th>
                                <th>sucursal</th>
                                <th>zona</th>
                                <th>formato</th>
                                <th>superficie</th>
                                <th>monto pagado</th>
                                <th>estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($misReservas as $row) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fecha_alta']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fecha_reserva']); ?></td>
                                    <td><?php echo htmlspecialchars($row['horario_inicio']); ?></td>
                                    <td><?php echo htmlspecialchars($row['horario_fin']); ?></td>
                                    <td><?php echo htmlspecialchars($row['sucursal']); ?></td>
                                    <td><?php echo htmlspecialchars($row['zona']); ?></td>
                                    <td><?php echo htmlspecialchars($row['formato']); ?></td>
                                    <td><?php echo htmlspecialchars($row['superficie']); ?></td>
                                    <td><?php echo htmlspecialchars($row['monto_pagado']); ?></td>
                                    <td><?php echo htmlspecialchars($row['estado']); ?></td>
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

</body>

</html>
<?php
// Liberar resultados y cerrar la conexión
mysqli_free_result($misReservas);
mysqli_close($conexion);
?>