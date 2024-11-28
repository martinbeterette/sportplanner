$(document).ready(function() {
    // let base_url = "http://localhost/proyecto_pp2_2024/";
    let $profileDropdownList = $(".profile-dropdown-list");
    let $btn = $(".profile-dropdown-btn");
    let $notificationsList = $('.notifications-dropdown'); // Cambiado para que apunte al contenedor de la lista

    const toggleProfileDropdown = () => {
        $profileDropdownList.toggleClass("active");
    };

    const toggleNotificationsList = () => {
        $notificationsList.toggleClass("active");
    };

    // Al hacer clic en el botón de perfil, alternar la clase 'active'
    $btn.on("click", function(e) {
        e.stopPropagation(); // Evitar que el clic se propague y cierre el menú
        toggleProfileDropdown();
    });

    // Cerrar el menú si se hace clic fuera de él
    $(window).on("click", function(e) {
        if (!$btn.is(e.target) && !$btn.has(e.target).length) {
            $profileDropdownList.removeClass("active");
            $notificationsList.removeClass("active"); // Asegúrate de cerrar también las notificaciones
        }
    });

    // Abrir/Cerrar sublista de notificaciones al hacer clic
    $('#notificaciones-btn').on('click', function(e) {
        e.preventDefault(); // Evitar que la página recargue
        e.stopPropagation(); // Evitar que se cierre el dropdown
        toggleNotificationsList();
    });

    // Cerrar el menú al hacer clic fuera de su contenedor
    $(document).on("click", function() {
        if ($notificationsList.hasClass("active")) {
            $notificationsList.removeClass("active");
        }
    });

    // Evitar cerrar el dropdown al hacer clic dentro de la lista de notificaciones
    $('.sub-notifications-list').on("click", function(e) {
        e.stopPropagation(); // Evita que el evento se propague y cierre el menú
    });

    $('.notifications-dropdown .sub-notifications-list li').on("click", function(){
        let id_notificacion = $(this).data('id-notificacion');
        verNotificacion(id_notificacion,base_url);
    });
    

});

function verNotificacion(id,base_url) {
    window.location.href = base_url + `php/notificacion/notificacion.php?id_notificacion=${id}&notificacion_seleccionada`;
}
