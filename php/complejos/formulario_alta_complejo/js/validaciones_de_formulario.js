$(document).ready(function() {
	//PARAMETROS DE VALIDACIONES PASO 1
	let regex_complejo = /^[a-zA-Z0-9\s]{3,}$/;
	let regex_fecha_fundacion_complejo = /^\d{4}-\d{2}-\d{2}$/;

	let descripcion_complejo_error 		= true;
	let fecha_fundacion_complejo_error 	= true;

	//PARAMETROS DE VALIDACIONES PASO 2
	let regex_sucursal = /^[a-zA-Z0-9\s]{3,}$/;
	let regex_direccion = /^[a-zA-Z0-9\s.,/-]{5,}$/;
	let regex_fecha_fundacion_sucursal = /^\d{4}-\d{2}-\d{2}$/;

	let descripcion_sucursal_error 		= true;
	let direccion_error			 		= true;
	let fecha_fundacion_sucursal_error 	= true;
	let select_provincia_error 			= true;
	let select_localidad_error 			= true;
	let select_barrio_error 			= true;

	//PARAMETROS DE VALIDACIONES PASO 3
	let email_error 					= true;
	let username_error 					= true;
	let password_error 					= true;
	let confirm_password_error 			= true;


	//VALIDACIONES PASO 1
		//validacion nombre complejo 
		$("#descripcion_complejo").on("blur", function() {
			let descripcion_complejo = $(this).val();

			if(!regex_complejo.test(descripcion_complejo)) {
				$("#error_descripcion_complejo").html('El nombre debe tener al menos 3 carácteres, no se permiten ". - _ /"').css({
					"display":"block",
					"color":"#e65715",
				});
				descripcion_complejo_error = true;
				console.log("error en la descripcion complejo");
			} else {
				$("#error_descripcion_complejo").hide();
				descripcion_complejo_error = false;
				console.log("complejo: " + descripcion_complejo_error);
			}

		});

		//validacion fecha de fundacion complejo
		$("#fecha_fundacion_complejo").on("blur", function() {
			//obtenemos la fecha del input a un objeto javascript
			let fecha_ingresada_complejo = $(this).val();
			let partes = fecha_ingresada_complejo.split('-');
			let fecha = new Date(partes[0], partes[1] - 1, partes[2])
			// console.log(fecha);

			//seteamos la hora actual
			let hoy_complejo = new Date();
			hoy_complejo.setHours(0, 0, 0, 0);

			if(!regex_fecha_fundacion_complejo.test(fecha_ingresada_complejo) || (fecha >= hoy_complejo)) {
				$("#error_fecha_fundacion_complejo").html('La fecha ingresada no puede ser igual o superior a hoy').css({
					"display":"flex",
					"color":"#e65715",
				});
				fecha_fundacion_complejo_error = true;
				console.log("error en la fecha fundacion complejo");
				// console.log("fecha complejo: " + fecha_fundacion_complejo_error);
			} else {
				$("#error_fecha_fundacion_complejo").hide();
				fecha_fundacion_complejo_error = false;
				console.log("fecha complejo: " + fecha_fundacion_complejo_error);
			}

		});

	//VALIDACIONES PASO 2
		//validacion de nombre sucursal
		$("#descripcion_sucursal").on("blur", function() {

			let descripcion_sucursal = $(this).val();

			if (!regex_sucursal.test(descripcion_sucursal)) {
				$("#error_descripcion_sucursal").html(
					'El nombre debe tener al menos 3 carácteres, no se permiten caracteres como " . - _ / "'
				).css({
					"display":"block",
					"color":"#e65715",
				});
				descripcion_sucursal_error = true;
				console.log("error la descripcion sucursal");
			} else {
				$("#error_descripcion_sucursal").hide();
				descripcion_sucursal_error = false;
				console.log("sucursal: " + descripcion_sucursal_error);
			}

		}); 

		//validacion de direccion sucursal
		$("#direccion").on("blur", function() {

			let direccion = $(this).val();

			if(!regex_direccion.test(direccion)) {
				$("#direccion_error").html(
					'La direccion debe tener al menos 5 carácteres, no se permiten simbolos salgo ". / - ,"'
				).css({
					"display":"block",
					"color":"#e65715",
				});
				direccion_error = true;
				console.log("Error en la direccion");
			} else {
				$("#direccion_error").hide();
				direccion_error = false;
				console.log("direccion: " + direccion_error);
			}

		});

		$("#fecha_fundacion_sucursal").on("blur", function() {
			//SETEAMOS EL VALOR RECIBIDO A UN OBJETO JAVASCRIPT
			let fecha_ingresada_sucursal = $(this).val();
			let partes = fecha_ingresada_sucursal.split('-');
			let fecha = new Date(partes[0], partes[1] - 1, partes[2])

			//SEATEAMOS LA FECHA ACTUAL
			let hoy_sucursal = new Date();
			hoy_sucursal.setHours(0, 0, 0, 0); 
			// console.log(fecha);
			if(!regex_fecha_fundacion_sucursal.test(fecha_ingresada_sucursal) || (fecha >= hoy_sucursal)) {
				$("#error_fecha_fundacion_sucursal").html('La fecha ingresada no puede ser nula, igual o superior a hoy').css({
					"display":"block",
					"color":"#e65715",
				});
				fecha_fundacion_sucursal_error = true;
				console.log("Error en fecha fundacion sucursal");
			} else {
				$("#error_fecha_fundacion_sucursal").hide();
				fecha_fundacion_sucursal_error = false;
				console.log("fecha sucursal: " + fecha_fundacion_sucursal_error);

			}

		});

		//provincia
		$("#provincia").on("change", function() {

			let id = $(this).val();

			if (id !== null && id !== '' ) {
				select_provincia_error = false;
			} else {
				select_tipo_terreno_error = true;
				console.log("Error provincia: " + select_provincia_error);
			}

		});

		//localidad
		$("#localidad").on("change", function() {

			let id = $(this).val();

			if (id !== null && id !== '' ) {
				select_localidad_error = false;
			} else {
				select_localidad_error = true;
				console.log("Error localidad: " + select_localidad_error);
			}

		});

		//barrio
		$("#barrio").on("change", function() {

			let id = $(this).val();

			if (id !== null && id !== '' ) {
				select_barrio_error = false;
			} else {
				select_barrio_error = true;
				console.log("Error barrio: " + select_barrio_error);
			}

		});










		//al finalizar el formulario
		$("#btn-finalizar").on("click", function () {
			// paso 1
			let descripcion_complejo = $('#descripcion_complejo').val();
			let fecha_ingresada_complejo = $('#fecha_fundacion_complejo').val();
			//paso 2
			let descripcion_sucursal = $('#descripcion_sucursal').val();
			let	direccion = $('#direccion').val();
			let	fecha_ingresada_sucursal = $('#fecha_fundacion_sucursal').val();

			if (
					//paso 1
					regex_complejo.test(descripcion_complejo) &&
					regex_fecha_fundacion_complejo.test(fecha_ingresada_complejo) &&
					//paso 2
					regex_sucursal.test(descripcion_sucursal) &&
					regex_direccion.test(direccion) && 
					regex_fecha_fundacion_sucursal.test(fecha_ingresada_sucursal)
				)
			{

				$("#formulario").submit();
				
			} else {
				alert("hay errores, verifique el formulario");
			}

		});

});//document ready