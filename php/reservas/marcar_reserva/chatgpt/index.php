<head>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="horarios-contenedor">
    <?php
    $horarios = [];
    
    for($i = 1; $i < 24; $i++) {
        $horarios[$i] = [
            'reservado' => 'hola',
            'id_reserva' => $i,
            'hora_inicio' => '19:00',
            'hora_fin' => '20:00'
        ];
    }
    // Supongamos que $horarios es una lista de horarios traídos de la base de datos
    foreach ($horarios as $horario) {
        // Comprobar si el horario está reservado o no
        $clase = $horario['reservado'] ? 'ocupado' : 'disponible';
        $texto = $horario['reservado'] ? 'Reservado' : 'Disponible';
        ?>
        <div class="horario <?= $clase ?>" data-id="<?= $horario['id_reserva'] ?>">
            <?= $horario['hora_inicio'] ?> - <?= $horario['hora_fin'] ?> (<?= $texto ?>)
        </div>
    <?php } ?>
</div>

<div id="detalleReservaModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Detalles de la Reserva</h2>
        <ul>
            <li>Hora de inicio: 19:00<span id="modalHoraInicio"></span></li>
            <li>Hora de Fin: 20:00<span id="modalHoraFin"></span></li>
            <li>Titular: Martin Coppa<span id="modalTitular"></span></li>
            <li>Precio: 10.000<span id="modalPrecio"></span></li>
            <li>Pago: 5.000<span id="modalPago"></span></li>
        </ul>
        <div class="btn-contenedor">
            <button id="btn-llegada" class="btn-accion">Marcar Llegada</button>
            <button id="btn-salida" class="btn-accion" disabled>Marcar Salida</button>
            <button id="btn-inasistencia" class="btn-accion">Inasistencia</button>
        </div>
    </div>
</div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $(".horario.ocupado").on("click", function() {
        // Obtener el ID de la reserva desde el data-id
        let idReserva = $(this).data("id");

        // Hacer una llamada AJAX para obtener los detalles de la reserva
        $.get(`detalles_reserva.php?id_reserva=${idReserva}`, function(data) {
            // Llenar el modal con la información recibida
            $("#modalHoraInicio").text(data.horario_inicio);
            $("#modalHoraFin").text(data.horario_fin);
            $("#modalTitular").text(data.titular);
            $("#modalPrecio").text(data.precio);
            $("#modalPago").text(data.monto_pagado);
            $("#btn-llegada").data("id", data.id_reserva);
            $("#btn-salida").data("id", data.id_reserva);
            $("#btn-inasistencia").data("id", data.id_reserva);

            // Mostrar el modal
            $("#detalleReservaModal").show();
        });
    });

    // Cerrar modal
    $(".close-modal").on("click", function() {
        $("#detalleReservaModal").hide();
    });
});
</script>
