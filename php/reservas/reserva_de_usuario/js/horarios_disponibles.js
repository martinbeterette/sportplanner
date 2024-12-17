$(document).ready(function () {
    /*
           let tabla = document.getElementsByTagName('table')[0];
        let celdasDisponibles = tabla.querySelectorAll('td.disponible');
   
        celdasDisponibles.forEach(function(celda) {
        let idHora = celda.getAttribute('id-hora');
        let hora = celda.textContent;
        let enlace = document.createElement('a');
        
        enlace.href = "formularioReserva3.php?id_horario=" + encodeURIComponent(idHora) + 
        "&fecha_reserva=<?php echo $fecha; ?> + &id_persona=<?php echo $_SESSION['id']; ?> + &cancha=<?php echo $cancha; ?>";
        enlace.textContent = hora;
   
        celda.innerHTML = "";
        celda.appendChild(enlace);
   
        });
       */

    let tabla = $('table:eq(0)');
    let celdasDisponibles = tabla.find('td.disponible');

    celdasDisponibles.each(function () {
        let celda = $(this);
        let idHora = celda.attr('id-hora');
        let hora = celda.text();
        let enlace = $('<a></a>');

        enlace.attr('href', "formularioReserva3.php?id_horario=" + encodeURIComponent(idHora) +
            "&fecha_reserva=" + encodeURIComponent(fecha) + "&id_persona=" + id_usuario + "&cancha=" + cancha);
        enlace.text(hora);

        celda.empty().append(enlace);
    });

});