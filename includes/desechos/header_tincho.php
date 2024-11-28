        <?php 
            $deporte = $conexion->query("SELECT id_deporte, descripcion_deporte FROM deporte");

            $superficie = $conexion->query("SELECT id_tipo_terreno, descripcion_tipo_terreno FROM tipo_terreno");

            $horario = $conexion->query("SELECT id_horario, horario_inicio, horario_fin FROM horario");
        ?>
        <header>
            <div class="titulo_inicio">
                <!-- <img src="../../../../assets/icons/icono22.png" alt="icono inicio"> -->
                <h2>Sportolanner</h2>
            </div>

            <!-- Botón que aparece en pantallas pequeñas -->
            <button id="open-modal-btn" class="open-modal-btn" style="display: none;" onclick="openModal()">Buscar
                Cancha</button>

            <!-- Formulario de filtro que será contenido del modal en pantallas pequeñas -->
            <form id="filtro_deporte" method="GET" action="<?php echo BASE_URL. "php/reservas/reserva_formulario/listado_canchas_disponibles.php"; ?>">
                <!-- Selección del deporte -->
                <div class="form_filtro">
                    <label for="deporte">Deporte:</label>
                    <select id="deporte" name="deporte" onchange="updateTipoDeporte()">
                        <option value="" disabled selected>Seleccione un deporte</option>
                        <?php foreach ($deporte as $reg) :?>
                            <option value="<?= $reg['id_deporte'] ?>"><?= $reg['descripcion_deporte'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tipo de deporte dinámico -->
                <div class="form_filtro" id="tipo-deporte-container">
                    <label for="tipoDeporte">Formato:</label>
                    <select id="tipoDeporte" name="tipoDeporte">
                        <option value="">Seleccione un tipo de deporte</option>
                        <!-- Se llenará dinámicamente según el deporte -->
                    </select>
                </div>

                <!-- Selección de la superficie -->
                <div class="form_filtro">
                    <label for="superficie">Superficie:</label>
                    <select id="superficie" name="superficie">
                        <option value="" disabled selected>seleccione una superficie</option>
                        <?php foreach ($superficie as $reg) :?>
                            <option value="<?= $reg['id_tipo_terreno'] ?>"><?= $reg['descripcion_tipo_terreno'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Selección de la fecha -->
                <div class="form_filtro">
                    <label for="fecha">Fecha:</label>
                    <input type="text" id="fecha" name="fecha">
                </div>

                <!-- Selección de la hora -->
                <div class="form_filtro">
                    <label for="horario">Hora:</label>
                    <select name="horario" id="horario">
                        <option value="" disabled selected>seleccione una hora</option>
                        <?php foreach ($horario as $reg) :?>
                            <option value="<?= $reg['id_horario'] ?>"><?= $reg['horario_inicio'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit">Buscar Partido</button>
            </form>

            <!-- Modal -->
            <div id="filter-modal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h3>Filtrar Deportes</h3>
                    <!-- Aquí se carga el formulario de filtros -->
                    <form id="filtro_deporte_modal"></form>
                </div>
            </div>

            <div class="profile-dropdown">
                <div onclick="toggle()" class="profile-dropdown-btn">
                    <div class="profile-img">
                    </div>

                    <span><?php echo $_SESSION['usuario']; ?>
                        <i class="fa-solid fa-angle-down"></i>
                    </span>
                </div>

                <ul class="profile-dropdown-list">
                    <li class="profile-dropdown-list-item">
                        <a href="<?php echo BASE_URL; ?>login/miPerfil/mis_datos.php">
                            <i class="fa-regular fa-user"></i>
                            Mi Perfil
                        </a>
                    </li>

                    <li class="profile-dropdown-list-item">
                        <a href="#">
                            <i class="fa-regular fa-envelope"></i>
                            Notificacion
                        </a>
                    </li>

                    <li class="profile-dropdown-list-item">
                        <a href="#">
                            <i class="fa-solid fa-futbol"></i>
                            Mis Reservas
                        </a>
                    </li>

                    <li class="profile-dropdown-list-item">
                        <a href="#">
                            <i class="fa-solid fa-toggle-off"></i>
                            Modo Oscuro
                        </a>
                    </li>

                    <li class="profile-dropdown-list-item">
                        <a href="#">
                            <i class="fa-regular fa-circle-question"></i>
                            Ayuda
                        </a>
                    </li>
                    <hr />

                    <li class="profile-dropdown-list-item">
                        <a href="<?php echo BASE_URL; ?>login/cerrar_sesion.php">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            Cerrar Sesion
                        </a>
                    </li>
                </ul>
            </div>
            <script src="<?= BASE_URL . "libs/jquery-3.7.1.min.js"?>"></script>
            <script>
                $("#deporte").on("change", function() {
                    id_deporte = $(this).val();
                    $.ajax({
                        url: '<?= BASE_URL . "includes/ajax/formato_deporte.php"?>',
                        type: 'GET',
                        dataType: 'json',
                        data: {id_deporte: id_deporte},
                        success: function(data) {
                            $('#tipoDeporte').empty();
                            $('#tipoDeporte').append('<option value="" disabled selected>Seleccione una tipo de deporte</option>');

                            $.each(data, function (index,formato_deporte) {
                                 $('#tipoDeporte').append('<option value="' + formato_deporte.id_formato_deporte + '">' + formato_deporte.descripcion_formato_deporte + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error("error al cargar los tipos de deporte: ", error);
                        }


                    });

                });
            </script>
            <script>
                let profileDropdownList = document.querySelector(".profile-dropdown-list");
                let btn = document.querySelector(".profile-dropdown-btn");

                let classList = profileDropdownList.classList;

                const toggle = () => classList.toggle("active");

                window.addEventListener("click", function (e) {
                    if (!btn.contains(e.target)) classList.remove("active");
                });
            </script>
        </header>