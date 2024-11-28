$(document).ready(function () {

	$(document).on('click', '.ver_mas', function () {
		let contenido = $(this).data('ver_mas');
		verMas(contenido);
	})

	function verMas(data) {
		const contenido = `
		<div class="datos_adicionales">
		
			<p><strong>ID Reserva:</strong> 			${data.id_reserva}</p>
			<p><strong>Horario Desde:</strong> 			${data.horario_inicio}</p>
			<p><strong>Horario Hasta:</strong> 			${data.horario_fin}</p>
			<p><strong>Pago Parcial:</strong> 			${data.monto_pagado}</p>
			<p><strong>Total a Pagar:</strong> 			${data.monto_total}</p>
			<p><strong>Cancha:</strong> 				${data.descripcion_zona}</p>
			<p><strong>Sucursal:</strong> 				${data.descripcion_sucursal}</p>
			<p><strong>Fecha de Reserva:</strong> 		${data.fecha_reserva}</p>
			<p><strong>Fecha de la solicitud:</strong> 	${data.fecha_alta}</p>

		</div>
		`;

		Swal.fire({
			title: 'Información Adicional',
			html: contenido,
			icon: 'info',
	        confirmButtonText: 'Cerrar',
	        width: '600px', // Se ajusta para ser similar al CSS de tu modal
	        padding: '2rem',
	        background: '#fff',
	        confirmButtonColor: '#47a386',
		});
	}



});//document ready