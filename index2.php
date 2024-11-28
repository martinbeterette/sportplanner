<?php 
    require_once("config/root_path.php");       
    require_once(RUTA . "config/database/conexion.php");
    require_once(RUTA . "config/database/db_functions/personas.php");
    require_once(RUTA . "php/functions/controlar_acceso.php");
    session_start(); 

    $deporte = $conexion->query("SELECT id_deporte, descripcion_deporte FROM deporte");

    $superficie = $conexion->query("SELECT id_tipo_terreno, descripcion_tipo_terreno FROM tipo_terreno");

    $horario = $conexion->query("SELECT id_horario, horario_inicio, horario_fin FROM horario");



    $modulo = "Inicio";
    $perfil = $_SESSION['perfil'];
    validarAcceso($modulo, $perfil);
    // print_r($_SESSION);

    //probandooooooooooooo
    $resetear_credenciales = $conexion->query("SELECT resertear_credenciales FROM usuarios WHERE id_usuario = {$_SESSION['id_usuario']}")->fetch_assoc()['resertear_credenciales'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Inicio</title>
</head>

<body>
    <!-- Header + Hero envueltos en una sección para hacer que el carrusel sea su fondo -->
    <div class="container">
        
    
        <section class="hero-container">

            <?php include(RUTA. "includes/header.php"); ?>

            
            <?php include(RUTA."includes/menu_aside.php") ?>

            <section class="hero">

                <form action="<?php echo BASE_URL. "php/reservas/reserva_formulario/listado_canchas_disponibles.php"?>">

                    <div class="search-container">
                        <select name="deporte" id="deporte">
                            <option value="" disabled selected>Deporte</option>
                                <?php foreach ($deporte as $reg) :?>
                                    <option value="<?= $reg['id_deporte'] ?>"><?= $reg['descripcion_deporte'] ?></option>
                                <?php endforeach; ?>
                        </select>

                        <select name="tipoDeporte" id="tipoDeporte">
                            <option value="">Tipo de deporte</option>
                        </select>

                        <input type="text" id="fecha" name="fecha" placeholder="fecha">
                        <select name="horario" id="horario">
                            <option value="" disabled selected>Hora</option>
                                <?php foreach ($horario as $reg) :?>
                                    <option value="<?= $reg['id_horario'] ?>"><?= $reg['horario_inicio'] ?></option>
                                <?php endforeach; ?>

                        </select>
                        <button class="search-btn" type="submit">Buscar</button>
                    </div>
                    
                </form>


            </section>
        </section>
    </div>

    <section class="info-section">
        <h2>Registra tu complejo deportivo para ser parte de nuestro sistema</h2>
        <button onclick="location.href='tu-formulario.html'" class="register-btn">Registrar complejo</button>
    </section>

    <section class="features-section">
        <h3>¿Qué ofrecemos?</h3>
        <div class="features-container">
            <div class="feature-card">
                <img src="https://img.icons8.com/?size=100&id=98MdZ2P948ii&format=png&color=000000" alt="Icono Gestión de Canchas y Reservas">
                <p>Gestión de canchas y reservas</p>
            </div>
            <div class="feature-card">
                <img src="https://img.icons8.com/?size=100&id=39358&format=png&color=000000" alt="Icono Creación de Sucursales">
                <p>Creación de nuevas sucursales físicas</p>
            </div>
            <div class="feature-card">
                <img src="https://img.icons8.com/?size=100&id=44371&format=png&color=000000" alt="Icono Gestión de Tarifas">
                <p>Gestión de tarifas</p>
            </div>
            <div class="feature-card">
                <img src="https://img.icons8.com/?size=100&id=EKeYRe0-QCY9&format=png&color=000000" alt="Icono Gestión de Socios">
                <p>Gestión de socios y membresías</p>
            </div>
            <div class="feature-card">
                <img src="https://img.icons8.com/?size=100&id=kSbhCMkEg8pf&format=png&color=000000" alt="Icono Gestión por Empleados">
                <p>Permitir gestión del sistema a empleados registrados</p>
            </div>
        </div>
    </section>
    <!-- LIBRERIAS -->
    <script src="libs/jquery-3.7.1.min.js"></script>
    <script src="libs/sweetalert2.all.min.js"></script>
    <?php if($resetear_credenciales == 'si') {?>
        <script>swal.fire("Debe resetear sus credenciales","","info");</script>
    <?php } ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/es.js"></script>
    <!-- LIBRERIAS -->

    <script src="js/header.js"></script>
    <script src="js/aside.js"></script>
    <script>
        console.log(base_url);
    </script>
    <!-- <script src="js/notificaciones.js"></script> -->
    <!-- carrusel -->
    <script>
        $(document).ready(function () {
            
            let images = [
                'url("maquetado inicio/carrusel_2.jpg")',
                'url("maquetado inicio/carrusel_3.jpg")',
                'url("maquetado inicio/carrusel_4.jpg")',
                // Agrega más URLs de imágenes según lo necesites
            ];

            let currentIndex = 0;
            let container = $('.container'); // Selecciona el contenedor donde cambiará el fondo

            // Función para mostrar la siguiente imagen
            function showNextImage() {
                // Cambiar el background-image del contenedor
                container.css('background-image', images[currentIndex]);

                // Actualizar el índice de la imagen
                currentIndex = (currentIndex + 1) % images.length;
            }

            // Inicializar con la primera imagen
            container.css('background-image', images[currentIndex]);

            // Cambiar la imagen cada 7 segundos
            setInterval(showNextImage, 7000);
            $("#deporte").on("change", function() {
                id_deporte = $(this).val();
                $.ajax({
                    url: '<?= BASE_URL . "includes/ajax/formato_deporte.php"?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {id_deporte: id_deporte},
                    success: function(data) {
                        $('#tipoDeporte').empty();
                        $('#tipoDeporte').append('<option value="" disabled selected>Tipo de deporte</option>');

                        $.each(data, function (index,formato_deporte) {
                             $('#tipoDeporte').append('<option value="' + formato_deporte.id_formato_deporte + '">' + formato_deporte.descripcion_formato_deporte + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("error al cargar los tipos de deporte: ", error);
                    }
                });

            });

            flatpickr("#fecha", {
                dateFormat: "Y-m-d",  // Formato de fecha almacenado
                minDate: "today",     // Hoy como la fecha mínima
                maxDate: new Date().fp_incr(7),  // 7 días hacia adelante desde hoy
                defaultDate: "today", // Preselecciona la fecha de hoy
                altInput: true,
                altFormat: "F j, Y",  // Formato alternativo que se muestra
                allowInput: false,     // Evita que el usuario escriba manualmente
            });

            

        });
    </script>

</body>
</html>
