$(document).ready(function() {
	let opciones = ''; //inicializamos nulas las opciones
	membresias.forEach(function(membresia){
		opciones += `<option value="${membresia.id_membresia}">${membresia.descripcion_membresia} - ${membresia.precio_membresia}</option>`
	});
	$('#membresia').append(opciones);

	opciones = ''; //volvemos a hacer nulas las opciones para reutilizar la variable
	tipos_documento.forEach(function(tipo_documento){
		opciones += `
		<option
		 	value="${tipo_documento.id_tipo_documento}"
		 >
		 	${tipo_documento.descripcion_tipo_documento}
		 </option>`;
	});
	$('#tipo_documento').append(opciones);

	opciones = ''; //volvemos a hacer nulas las opciones para reutilizar la variable
	sexos.forEach(function(sexo){
		opciones += `<option value="${sexo.id_sexo}">${sexo.descripcion_sexo}</option>`;
	});
	$('#sexo').append(opciones);
});