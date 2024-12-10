$(() => { //document ready

	$(document).on("click",".btn-verificar", function() {
		let id_complejo = $(this).attr('data-id');
		window.location.href = `includes/listado_sucursales.php?id_complejo=${id_complejo}`;
	});

});// fin del document ready