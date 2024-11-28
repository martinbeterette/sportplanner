$(document).ready(function () {
    const $menu = $("#aside-menu");
    const $toggleButton = $("#toggle-menu");

    // Mostrar/Ocultar el menú cuando se hace clic en el botón
    $toggleButton.on("click", function () {
        $menu.toggleClass("active");
        // $(this).hide(); 
    });

    // Cerrar el menú al hacer clic fuera de él
    $(document).on("click", function (e) {
        if (!$menu.is(e.target) && !$menu.has(e.target).length && !$toggleButton.is(e.target)) {
            $menu.removeClass("active");
            $toggleButton.show(); // Mostrar el botón de nuevo
        }
    });

});