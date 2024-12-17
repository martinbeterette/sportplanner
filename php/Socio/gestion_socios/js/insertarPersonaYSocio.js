$(document).ready(function(e) {
	$('#form-insercion-socio').submit(function (e){
		e.preventDefault();
		let nombre = $('#nombre').val();
		let apellido = $('#apellido').val();
		let documento = $('#documento').val();
		let id_tipo_documento = $('#tipo_documento').val();
		let fecha_nacimiento = $('#fecha_nacimiento').val();
		let cantidad_meses = $('#cantidad_meses').val();
		let id_membresia = $('#membresia').val();
		let id_sexo = $('#sexo').val();
		let correo = $('#correo').val();
		// alert(`nombre:${nombre}, apellido:${apellido}, documento:${documento}, tipo documento:${id_tipo_documento}, fechanac:${fecha_nacimiento}, cantidad meses: ${cantidad_meses},id membresia:${id_membresia}, idsexo:${id_sexo}, idcomplejo:${id_complejo}, correo: ${correo}`)
		insertarPersonaYsocio(nombre,apellido,documento,id_tipo_documento, id_sexo, fecha_nacimiento, cantidad_meses,id_membresia, id_complejo, correo);
	});
});
function insertarPersonaYsocio
(
	nombre,
	apellido,
	documento,
	id_tipo_documento,
	id_sexo,
	fecha_nacimiento,
	cantidad_meses,
	id_membresia,
	id_complejo,
	correo
) 
{
	$.ajax({
		url: '../ajax/insertarPersonaYSocio.php',
		method: 'POST',
		data: {
			nombre: 			nombre,
			apellido: 			apellido,
			documento: 			documento,
			id_tipo_documento: 	id_tipo_documento,
			id_sexo: 			id_sexo,
			fecha_nacimiento: 	fecha_nacimiento,
			cantidad_meses:     cantidad_meses,
			id_membresia: 		id_membresia,
			id_complejo: 		id_complejo,
			correo: 			correo
		},
		success: function(data) {
			if(data == 'success'){
				$('#form-insercion-socio')[0].reset();
				Swal.fire('Insercion Exitosa','Se ha insertado correctamente la persona','success');
			} else if(data == 'existe') {
				Swal.fire('Error','Ya existe una persona con ese documento','info');
				console.log(data);

			}else if(data == 'correo existente') {
				Swal.fire('Error durante la insercion','Ese correo ya exite, inserte uno nuevo','warning');
			}else {
				Swal.fire('Error durante la insercion','Verifique los campos o intentelo de nuevo','warning');
				console.log(data);
			}
		},
		error: function (xhr, status, error) {
		    console.log("Error en la petición AJAX:");
		    console.log("Estado: " + status);
		    console.log("Error: " + error);
		    console.log("Respuesta del servidor: " + xhr.responseText);
		}
	});
}