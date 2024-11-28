$(document).ready(function () {
	obtenerProvincias();

	$('#provincia').change(function(){
		let id_provincia = $(this).val();
		obtenerLocalidades(id_provincia);
	});

	$('#localidad').change(function(){
		let id_localidad = $(this).val();
		obtenerBarrios(id_localidad);
	});

	



	function obtenerProvincias() {
		$.ajax({
			url: '../ajax/obtenerProvincias.php',
			type: 'GET',
			dataType: 'json',
			success: function (data) {
				$('#provincia').empty();
				$('#provincia').append('<option value="">Seleccione un Provincia</option>');
				data.forEach(function (provincia) {
					let option = new Option(provincia.descripcion_provincia, provincia.id_provincia);
					if(provincia.id_provincia == provincia_seleccionada) {
						option.selected = true;
					}
					$('#provincia').append(option);
				});
				let id_provincia = $('#provincia').val();
				if(id_provincia) {
					obtenerLocalidades(id_provincia);

				}
			},
			error :function () {console.log("error en la peticion AJAX")}

		});
	}

	function obtenerLocalidades(id_provincia=null) {
		if(!id_provincia) {
			$('#localidad').empty();
			$('#localidad').append('<option value="">Seleccione una localidad</option>');
			return;
		} 
		$.ajax({
			url: '../ajax/obtenerLocalidades.php',
			type: 'GET',
			data: {id_provincia: id_provincia},
			dataType: 'json',
			success: function (data) {
				$('#localidad').empty();
				$('#localidad').append('<option value="">Seleccione una localidad</option>');
				data.forEach(function (localidad) {
					let option = new Option(localidad.descripcion_localidad, localidad.id_localidad);
					if (localidad.id_localidad == localidad_seleccionada) {
						option.selected = true;
					}
					$('#localidad').append(option);
				});
				let id_localidad = $('#localidad').val();
				if(id_localidad) {
					obtenerBarrios(id_localidad);

				}
			},
			error :function () {console.log("error en la peticion AJAX")}

		});
	}

	function obtenerBarrios(id_localidad=null) {
		if(!id_localidad) {
			$('#barrio').empty();
			$('#barrio').append('<option value="">Seleccione un Barrio</option>');
			return;
		}
		$.ajax({
			url: '../ajax/obtenerBarrios.php',
			type: 'GET',
			data: {id_localidad: id_localidad},
			dataType: 'json',
			success: function (data) {
				$('#barrio').empty();
				$('#barrio').append('<option value="">Seleccione un Barrio</option>');
				data.forEach(function (barrio) {
					let option = new Option(barrio.descripcion_barrio, barrio.id_barrio);
					if(barrio.id_barrio == barrio_seleccionado) {
						option.selected = true;
					}
					$('#barrio').append(option);
				});
				
			},
			error :function () {console.log("error en la peticion AJAX")}
		});
	}

});