$(document).ready(function() { 


    let tabla = $('table:eq(0)');
    let celdasDisponibles = tabla.find('td.disponible');

    celdasDisponibles.each(function() {
        let celda = $(this);
        let idHora = celda.attr('id-hora');
        let hora = celda.text();
        let enlace = $('<a></a>');

        enlace.attr('href', "formularioReserva3.php?id_horario=" + encodeURIComponent(idHora) + 
            "&fecha_reserva=" + encodeURIComponent(fecha) + "&id_persona=" + persona + "&cancha=" + cancha );
        enlace.text(hora);

        celda.empty().append(enlace);
    });

});