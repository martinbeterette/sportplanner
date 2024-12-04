<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sportsplanner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/inicio.css">
    <style>
        ul {
            list-style: none;
        }
    </style>
</head>

<body>
    <div class="contenedor_banner">
        <section>
            <div class="section1" style="display: flex;">
                <div class="parte1">
                    <div class="nombre_pagina">
                        <h1>Sportsplanner</h1>
                        <img src="../../../assets/icons/icono22.png">
                    </div>

                    <div class="nosotros_pagina">
                        <h1>Reserva y gestiona tus turnos, gestiona las reserva de las anchas y ganancias con nuestra
                            plataforma
                            intuitiva!</h1>
                    </div>
                </div>
                <div class="parte2">
                    <div class="iniciarsesion">
                        <form action="../../../login/inicio_sesion/Inicio_sesion.php" method="POST">
                            <button type="submit" id="register-btn" class="btn">Iniciar sesión</button>
                        </form>
                    </div>

                    <div class="filtroBusqueda">
                        <form>
                            <div class="search-container">
                                <select name="deporte" id="deporte">
                                    <option value="" disabled selected>Deporte</option>
                                </select>

                                <select name="tipoDeporte" id="tipoDeporte">
                                    <option value="" disabled selected>Tipo de deporte</option>
                                </select>

                                <input type="date" id="fecha" name="fecha" placeholder="Fecha">

                                <select name="horario" id="horario">
                                    <option value="" disabled selected>Hora</option>
                                </select>

                                <button class="search-btn" type="submit">Buscar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="porquenosotros">
            <header class="header">
                <div class="header-content">
                    <h1>Registra tu complejo deportivo</h1>
                    <p>¡Forma parte de nuestro sistema y gestiona tus canchas fácilmente!</p>
                    <button id="register-complejo" class="btn">Registrar complejo</button>
                </div>
            </header>
            <main class="main-content">
                <h2>¿Qué ofrecemos?</h2>
                <div class="cards">
                    <div class="card">
                        <img src="../../../assets/icons/cancha-de-futbol.webp" alt="Cancha">
                        <h3>Gestión de canchas y reservas</h3>
                        <p>Administra las reservas de tus canchas de forma rápida y sencilla.</p>
                    </div>
                    <div class="card">
                        <img src="../../../assets/icons/sucursales.webp" alt="Sucursal">
                        <h3>Creación de nuevas sucursales</h3>
                        <p>Amplía tu negocio añadiendo nuevas sucursales en minutos.</p>
                    </div>
                    <div class="card">
                        <img src="../../../assets/icons/tarifa-por-hora.webp" alt="Tarifas">
                        <h3>Gestión de tarifas</h3>
                        <p>Establece y organiza tus tarifas de manera eficiente.</p>
                    </div>
                    <div class="card">
                        <img src="../../../assets/icons/apreton-de-manos.webp" alt="Socios">
                        <h3>Gestión de socios y membresías</h3>
                        <p>Crea y gestiona las membresías de tus usuarios.</p>
                    </div>
                </div>
            </main>
        </section>

        <section class="facilreserva">
            <header class="facilreservaheader">
                <div class="facilreservaheader-content">
                    <h1>Haz tu reserva facil</h1>
                    <p>¡Registrate y reserva de manera segura y manten registro de ellas!</p>
                    <form action="../../../login/inicio_sesion/Inicio_sesion.php" method="POST">
                        <button type="submit" id="register-btn" class="btn">Iniciar sesión</button>
                    </form>
                </div>
            </header>
            <main class="facilreservamain-content">
                <h2>Reserva en pocos pasos</h2>
                <div class="facilreservacards">
                    <div class="facilreservacard">
                        <img src="../../../assets/icons/deportes.webp" alt="Pelotas">
                        <h3>Elije deporte, fecha y hora</h3>
                        <p>Selecciona las condiciones y te ofrecemos lo mejor.</p>
                    </div>
                    <img src="../../../assets/icons/angulo-de-la-flecha-apuntando-hacia-la-derecha.webp"
                        alt="flecha siguiente derecha" style="height: 100px;">
                    <div class="facilreservacard">
                        <img src="../../../assets/icons/cancha-de-futbol.webp" alt="Cancha">
                        <h3>Elije la cancha</h3>
                        <p>Elije la cancha a veras los horarios y zonas disponibles.</p>
                    </div>
                    <img src="../../../assets/icons/angulo-de-la-flecha-apuntando-hacia-la-derecha.webp"
                        alt="flecha siguiente derecha" style="height: 100px;">
                    <div class="facilreservacard">
                        <img src="../../../assets/icons/comprobado.webp" alt="Reserva Exitosa">
                        <h3>Realiza la reserva</h3>
                        <p>Una vez seleccionada haz la paga o pagas en la cancha.</p>
                    </div>
                </div>
            </main>
        </section>

        <section class="footerpage">
            <div class="footerdiv1">
                <div class="ubicacionargentina">
                    <h1>Aportamos un sistema orientado al deporte para poder disfrutar de inicio a fin</h1>
                    <h3>Estamos en Argentina y queremos llegar a todas las provincias</h3>
                    <button id="registrarProvincia">Registrar mi provincia</button>
                </div>
                <div class="imagenarg">
                    <img src="../../../assets/img/mapargentina.webp">
                </div>
            </div>
            <div class="footerdiv">
                <div class="logotitulo">
                    <img src="../../../assets/icons/prototipo_logo-Photoroom.png">
                    <h2>sportsplanner</h2>
                </div>
                <div class="redes_pagina">
                    <h3>Redes Sociales</h3>
                    <div class="social_icons">
                        <i class="fa-brands fa-facebook"></i>
                        <i class="fa-brands fa-twitter"></i>
                        <i class="fa-brands fa-youtube"></i>
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                </div>
                <div class="terminocondiciones">
                    <div class="condiciones">
                        <h3>Terminos y Condiciones Generales</h3>
                        <button class="vercondiciones">Condiciones</button>
                    </div>
                    <h3>copyright © 2024</h3>
                </div>
                <div class="ubicacionweb">
                    <h3><i class="fa-solid fa-map-location-dot"></i> Ubicacion:</h3>
                    <div class="iconarg">
                        <h3>Argentina</h3>
                        <img src="../../../assets/icons/TwemojiFlagArgentina.svg">
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="../../../libs/sweetalert2.all.min.js"></script>
    <script src="../js/inicio.js"></script>
    <script src="../../../js/terminoscondiciones.js"></script>

    <script>
        // Cuando el botón sea clickeado
        document.getElementById("register-complejo").addEventListener("click", function() {
            // Muestra el modal con SweetAlert2
            Swal.fire({
                title: '¿Cómo registrar tu complejo?',
                text: 'Sigue estos pasos para registrar tu complejo deportivo en nuestro sistema:',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ir al registro',
                cancelButtonText: 'Cancelar',
                footer: '<p>Pasos a seguir:</p><ul><li>1. Completa los datos del complejo.</li><li>2. Agrega las instalaciones disponibles.</li><li>3. Confirma la información y guarda.</li></ul>',
            }).then((result) => {
                // Si el usuario hace clic en "Ir al registro"
                if (result.isConfirmed) {
                    // Redirige al archivo PHP donde se pueden cargar los datos
                    window.location.href = 'registro_complejo.php'; // Cambia la URL según el archivo PHP que necesites
                }
            });
        });
    </script>

    <script>
        document.getElementById('registrarProvincia').addEventListener('click', function() {
            Swal.fire({
                title: 'Registrar Provincia',
                html: `
                <div style="text-align: left;">
                    <label for="provincia" style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">Seleccione su provincia:</label>
                    <select id="provincia" name="provincia" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 14px;">
                        <option value="" disabled selected>Seleccione una provincia</option>
                        <option value="Buenos Aires">Buenos Aires</option>
                        <option value="Córdoba">Córdoba</option>
                        <option value="Santa Fe">Santa Fe</option>
                        <option value="Mendoza">Mendoza</option>
                        <option value="Tucumán">Tucumán</option>
                        <option value="Salta">Salta</option>
                        <option value="Misiones">Misiones</option>
                        <option value="Neuquén">Neuquén</option>
                        <option value="Chaco">Chaco</option>
                        <!-- Agregar más opciones según sea necesario -->
                    </select>
                </div>
            `,
                showCloseButton: true,
                confirmButtonText: 'Registrar',
                confirmButtonColor: '#28a745',
                width: '50%',
                customClass: {
                    popup: 'swal2-modal-custom'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const selectedProvince = document.getElementById('provincia').value;
                    if (selectedProvince) {
                        Swal.fire({
                            title: '¡Provincia registrada!',
                            text: `Has registrado: ${selectedProvince}`,
                            icon: 'success',
                            confirmButtonColor: '#28a745'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Por favor, selecciona una provincia antes de continuar.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                }
            });
        });
    </script>
</body>

</html>