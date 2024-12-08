$(document).ready(function () {
    // Mostramos solo el primer form
    $(".paso").hide();
    $("#paso1").show();

    // Rellenamos el select de provincias
    cargarProvincias();
    cargarDeportes();
    cargarEstadoZona();
    cargarTipoTerreno();

    // Botón siguiente
    $(".siguiente").on("click", function () {
        var id_paso = $(this).attr("paso");
        siguientePaso(id_paso);
    });

    // Botón anterior
    $(".anterior").on("click", function () {
        var id_paso = $(this).attr("paso");
        pasoAnterior(id_paso);
    });

    // Cambio en el select de provincias
    $("#provincia").on("change", function () {
        var id_provincia = $(this).val();
        limpiarSelect("#localidad", "Seleccione una localidad");
        limpiarSelect("#barrio", "Seleccione un barrio");
        cargarLocalidades(id_provincia);
    });

    // Cambio en el select de localidades
    $("#localidad").on("change", function () {
        var id_localidad = $(this).val();
        limpiarSelect("#barrio", "Seleccione un barrio");
        cargarBarrios(id_localidad);
    });

    $("#deporte").on("change", function () {
        var id_deporte = $(this).val();
        limpiarSelect("#formato_deporte", "Seleccione un tipo de deporte");
        cargarFormatoDeporte(id_deporte);
    });

    // Función para cargar provincias
    function cargarProvincias() {
        realizarAjax('ajax/provincia.php', {}, '#provincia', 'Seleccione una provincia', 'descripcion_provincia', 'id_provincia');
    }

    // Función para cargar localidades
    function cargarLocalidades(id_provincia) {
        realizarAjax('ajax/localidad.php', { id_provincia: id_provincia }, '#localidad', 'Seleccione una localidad', 'descripcion_localidad', 'id_localidad');
    }

    // Función para cargar barrios
    function cargarBarrios(id_localidad) {
        realizarAjax('ajax/barrio.php', { id_localidad: id_localidad }, '#barrio', 'Seleccione un barrio', 'descripcion_barrio', 'id_barrio');
    }

    //funcion para cargar los deportes
    function cargarDeportes() {
        realizarAjax('ajax/deporte.php', {}, '#deporte', 'Seleccione un deporte', 'descripcion_deporte', 'id_deporte');        
    }

    //funccion para cargar formato_deporte
    function cargarFormatoDeporte(id_deporte) {
        realizarAjax('ajax/formato_deporte.php', { id_deporte: id_deporte }, '#formato_deporte', 'Seleccione un tipo deporte', 'descripcion_formato_deporte', 'id_formato_deporte');
    }

    //funcion para cargar los estados de la cancha
    function cargarEstadoZona() {
        realizarAjax('ajax/estado_zona.php', {}, '#estado_zona', 'Seleccione un estado', 'descripcion_estado_zona', 'id_estado_zona');
    }

    function cargarTipoTerreno() {
        realizarAjax('ajax/tipo_terreno.php', {}, '#tipo_terreno', 'Seleccione una superficie', 'descripcion_tipo_terreno', 'id_tipo_terreno');
    }

    // Función genérica para realizar AJAX
    function realizarAjax(url, data, selectId, defaultOption, descripcion, id) {
        $.ajax({
            url: url,
            type: 'GET',
            data: data,
            dataType: 'json',
            success: function (data) {
                limpiarSelect(selectId, defaultOption);
                $.each(data, function (index, item) {
                    $(selectId).append('<option value="' + item[id] + '">' + item[descripcion] + '</option>');
                });
            },
            error: function (xhr, status, error) {
                console.error("Error al cargar los datos desde " + url + ": ", error);
            }
        });
    }

    // Función para limpiar y añadir opción por defecto a un select
    function limpiarSelect(selectId, defaultOption) {
        $(selectId).empty().append('<option value="" disabled selected>' + defaultOption + '</option>');
    }

    // Función para avanzar al siguiente paso
    function siguientePaso(id_paso) {
        var paso = parseInt(id_paso) + 1;
        if (paso < 4) {
	        $(".paso").hide();
	        $("#paso" + paso).show();		        	
        }
    }

    // Función para retroceder un paso
    function pasoAnterior(id_paso) {
        var paso = parseInt(id_paso) - 1;
        if (paso > 0) {
	        $(".paso").hide();
	        $("#paso" + paso).show();    	
        }
    }

}); // document ready