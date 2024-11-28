$(document).ready(function() {
    $('.btn-eliminar').on('click', function() {
        const idSucursal = $(this).data('id'); // Obtener el ID de la sucursal
        const idComplejo = $(this).data('id-complejo');
        // Mostrar mensaje de confirmación
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará la sucursal.",
            icon: 'warning',
            animation: false,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, redirigimos a eliminar.php pasando el id_sucursal por GET
                window.location.href = `includes/eliminar.php?id_sucursal=${idSucursal}&id_complejo=${idComplejo}`;
            }
        });
    });
});
