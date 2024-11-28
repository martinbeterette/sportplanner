$(document).ready(function() {
    $('.btn-modificar').on('click', function() {
        const idSucursal = $(this).data('id');
        const idComplejo = $(this).data('id-complejo');

        // Redirigimos a modifica.php con los parámetros necesarios
        window.location.href = `includes/modificar.php?id_sucursal=${idSucursal}&id_complejo=${idComplejo}`;
    });
});
