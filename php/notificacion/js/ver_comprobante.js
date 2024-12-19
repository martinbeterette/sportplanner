$(() => {
	$(document).on("click",".ver_comprobante", function(){
		console.log(base_url + 'modules/misReservasUsuario/' +  reserva.comprobante);
		let comprobante = $(this).data("comprobante");
        // Mostrar el comprobante en un modal
        const modalContent = `
            <embed src="${base_url + 'modules/misReservasUsuario/' +  reserva.comprobante}" type="application/pdf" width="100%" height="500px">
        `;
        Swal.fire({
            title: 'Comprobante de Reserva',
            html: modalContent,
            showCloseButton: true,
            focusConfirm: false
        });
	    
	});
});