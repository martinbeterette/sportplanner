<?php
// Conexión a la base de datos
session_start();
print_r($_SESSION);
require_once('includes/functions.php');
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once("includes/obtener_notificaciones.php");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/header.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/aside.css">
    <link rel="stylesheet" href="css/notificacion.css">

</head>

<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/menu_aside.php'; ?>

    <div class="containerNotificacion">
        <h1 style="text-align: center;">Notificaciones</h1>
        <p>Notificaciones no leídas: <?php echo $unread_count; ?></p>
        <div class="container">
            <div class="sidebar">
                <?php if(!is_null($Notificaciones)): ?>
                    <?php if ($Notificaciones->num_rows > 0): ?>
                        <?php while ($row = $Notificaciones->fetch_assoc()): ?>
                            <div class="notification-item <?php echo $row['leido'] == 'no leido' ? 'unread' : ''; ?>" row="<?php echo htmlspecialchars(json_encode($row)); ?>"
                                onclick=" showDetails('<?php echo htmlspecialchars($row['mensaje']); ?>', '<?php echo $row['titulo']; ?>' , <?php echo $row['id_notificacion']; ?>)"
                                id-notificacion="<?php echo $row['id_notificacion'] ?>"
                            >
                                <div class="notification-title">
                                    <div class="imgNotificacion">
                                        <?php if ($row['leido'] == 'no leido') { ?>
                                            <img src="../../assets/icons/CiNotification.svg">
                                        <?php } ?>
                                    </div>
                                    <?php echo htmlspecialchars($row['titulo']); ?>
                                </div>
                                <div class="time-ago">
                                    hace <?php echo $row['dias']; ?> días <?php echo $row['horas']; ?> horas
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No hay notificaciones.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>No hay notificaciones.</p>
                <?php endif; ?>
            </div>
            <div class="details" id="details">
                Seleccione desde la lista lateral de notificaciones para ver más detalles
            </div>
        </div>
    </div>

    <!-- Modal overlay -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>

    <!-- Modal -->
    <div class="modal" id="modal">
        <div class="modal-header" id="modalHeader">Detalle de la notificación</div>
        <div class="modal-body" id="modalBody"></div>
        <div class="modal-footer">
            <button class="accept-btn" id="aceptar">Aceptar</button>
            <button class="close-btn" onclick="closeModal()">Cerrar</button>
        </div>
    </div>

    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . 'libs/sweetalert2.all.min.js' ?>"></script>

    <script >
        const notificacion_seleccionada = <?php echo $notificacion_seleccionada ?? 'null'?>;
        let reserva = '';
    </script>

    <script src="js/notificacion_seleccionada.js"></script> <!-- este es el nuevo script -->
    <script src="js/cambiar_notificacion.js"></script>
    <script src="js/rechazar_reserva.js"></script>
    <script src="js/aceptar_reserva.js"></script>
    <script src="js/ver_detalle.js"></script>
    <script src="js/show_details.js"></script>
</body>

</html>

<?php
$conexion->close();
?>