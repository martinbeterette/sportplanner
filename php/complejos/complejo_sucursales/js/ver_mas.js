$(document).ready(function() {
    $('.btn-ver').on('click', function() {
        // Obtenemos la información de la sucursal de los atributos data del botón
        // let registros = $(this).data('reg');
        const reg = $(this).data('reg');

        // Mostramos el modal con SweetAlert2
        Swal.fire({
            title: 'Detalles de la Sucursal',
            html: `
                <p><strong>Descripción:</strong> ${reg.descripcion_sucursal}</p>
                <p><strong>Fecha de creación:</strong> ${reg.fecha_de_creacion}</p>
                <p><strong>Dirección:</strong> ${reg.direccion}</p>
                <p><strong>Barrio:</strong> ${reg.descripcion_barrio}</p>
            `,
            confirmButtonText: 'Cerrar',
            customClass: {
                confirmButton: 'swal2-confirm btn-ver-mas' // Clase personalizada para el botón de confirmación
            },
            animation: false,
            buttonsStyling: false // Desactivamos el estilo por defecto de SweetAlert
        });
    });
});