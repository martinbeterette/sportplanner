$(document).ready(function () {
	$('#reservar').click(function () {
		let fecha_reserva = $(this).attr('fecha_reserva');
		let id_persona = $(this).attr('id_persona');
		let id_zona = $(this).attr('id_zona');

		window.location.href = `reserva2.php?id_persona${id_persona}&cancha=${id_zona}&fecha_reserva=${fecha_reserva}`;
	});
});