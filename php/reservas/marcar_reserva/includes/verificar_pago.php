<?php
// Incluye tu conexión a la base de datos
require_once("../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");

if (isset($_GET['id_reserva'])) {
    $id_reserva = $_GET['id_reserva'];

    // Consulta para obtener la reserva
    $consulta_reserva = "SELECT monto_pagado, monto_total FROM RESERVA WHERE id_reserva = ?";
    $stmt = $conexion->prepare($consulta_reserva);
    $stmt->bind_param("i", $id_reserva);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $reserva = $resultado->fetch_assoc();
    // Verificar si el monto pagado es menor al monto total
    $puede_jugar = ($reserva['monto_pagado'] >= $reserva['monto_total']);

}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Pago</title>
    <!-- Incluimos SweetAlert2 -->

<script src="<?php echo BASE_URL; ?>libs/jquery-3.7.1.min.js"></script>
<script src="<?php echo BASE_URL; ?>libs/sweetalert2.all.min.js"></script>
<style>
    @import url(/../../css/header.css);
    @import url(/../../css/aside.css);

</style>
</head>
<body>
    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if ($puede_jugar): ?>
                // Si puede jugar (monto pagado es igual o mayor al monto total)
                Swal.fire({
                    title: 'Pago Completo',
                    text: "La persona puede jugar",
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar Juego',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si confirma, redirigir a actualizar_reserva.php con pago completo
                        window.location.href = 'actualizar_reserva.php?pago_completo=<?php echo $id_reserva; ?>';
                    }
                });
            <?php else: ?>
                // Si no puede jugar (monto pagado es menor al monto total)
                Swal.fire({
                    title: 'Pago Incompleto',
                    text: "El pago no está completo. ¿Desea completar el pago?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Completar Pago',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si confirma, redirigir a actualizar_reserva.php con completar pago
                        window.location.href = 'actualizar_reserva.php?completar_pago=<?php echo $id_reserva; ?>';
                    }
                });
            <?php endif; ?>
        });
    </script>
    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>


</body>
</html>
