$(document).ready(function () {
	// AJAX para verificar disponibilidad del nombre de usuario
    $("#username").blur(function () {
        let username = $(this).val();
        if (username) { // Solo hace la validación si el campo no está vacío
            $.ajax({
                url: 'ajax/nombre_usuario.php', // Cambia a tu archivo PHP
                type: 'POST',
                data: { username: username },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                    	alert
                        $("#username-feedback").text("Nombre de usuario disponible").removeClass("error").addClass("success");
                    } else {
                        $("#username-feedback").text("El nombre de usuario ya está en uso").removeClass("success").addClass("error");
                    }
                },
                error: function () {
                    $("#username-feedback").text("Error al verificar el nombre de usuario").removeClass("success").addClass("error");
                }
            });
        } else {
            $("#username-feedback").text("");
        }
    });
});