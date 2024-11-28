$(document).ready(function(

	$('btn-llegada').on('click', function() {
		let id_reserva = $(this).val();
		let estado = 'entrada';
		$.ajax({
			url: 'ajax/insertar_control_asistencia.php',
			method: 'POST',
			data: {id_reserva: id_reserva, estado:estado},
			success: function () {
				
			}
		});
	}); // btn llegada on clicl

	$('btn-salida').on('click', function() {


	}); //btn salida on click
	
	$('btn-inasistencia').on('click', function() {


	}); // btn inasistencia on click

)); //document ready