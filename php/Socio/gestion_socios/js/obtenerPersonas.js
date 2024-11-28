$(document).ready(function () {

	$('#btn-buscar').click(function () {
		let filtro = $('#buscar-persona').val();
		obtenerPersonas(filtro);
	});

	function obtenerPersonas(filtro) {
		if(!filtro) {
			$(".resultados-personas").empty();
			$(".resultados-personas").append('<p class="no-results">Sin resultados...</p>');
			return;
		}
		$.ajax({
			url: "../ajax/obtenerPersonas.php",
			method: 'POST',
			data: {filtro: filtro},
			dataType: "json",
			success: function (data) {

				$(".resultados-personas").empty();
				data.forEach(function (persona) {
	                $(".resultados-personas").append(
	                    `<p class='persona' onclick='mostrarFormularioSocio(${persona.id_persona},${id_complejo})'>${persona.nombre} ${persona.apellido} - ${persona.descripcion_tipo_documento}: ${persona.descripcion_documento}</p>`
	                );
            	});
			},
			error: function (xhr, status, error) {
		        console.error("Error en la petición AJAX:");
		        console.error("Estado:", status);
		        console.error("Error:", error);
		        console.error("Respuesta del servidor:", xhr.responseText);
		        // Si querés mostrar un mensaje al usuario:
		    }
		});
	}





});