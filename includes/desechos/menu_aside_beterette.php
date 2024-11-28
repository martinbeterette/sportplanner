<?php
/*require_once("../config/root_path.php");
require_once(RUTA. 'config/database/conexion.php');
session_start();*/
// $_SESSION['id_perfil'] = 1;
$id_perfil = isset($_SESSION['id_perfil']) ? $_SESSION['id_perfil'] : 1/*23*/; // perfil del usuario almacenado en sesión
/*
    $querySinRuta = "SELECT m.descripcion_modulo, sm.descripcion_submodulo
              FROM modulo m 
              JOIN asignacion_perfil_modulo apm ON m.id_modulo = apm.rela_modulo
              JOIN submodulo sm ON m.id_modulo = sm.rela_modulo
              WHERE apm.rela_perfil = ?";
    */
$query = "SELECT m.descripcion_modulo, sm.descripcion_submodulo, m.ruta as ruta_modulo,sm.ruta as ruta_submodulo 
              FROM modulo m 
              JOIN asignacion_perfil_modulo apm ON m.id_modulo = apm.rela_modulo
              JOIN submodulo sm ON m.id_modulo = sm.rela_modulo
              WHERE apm.rela_perfil = ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_perfil);
$stmt->execute();
$result = $stmt->get_result();

$modulos = [];
while ($row = $result->fetch_assoc()) {
    $modulos[$row['descripcion_modulo']]['ruta_modulo'] = $row['ruta_modulo'];
    $modulos[$row['descripcion_modulo']]['submodulos'][] = [
        'nombre' => $row['descripcion_submodulo'],
        'ruta' => $row['ruta_submodulo']
    ];
}



?>

<!-- <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Lateral</title>
    <link rel="stylesheet" href=<?php/* echo BASE_URL . 'css/Aside/menu_aside_beterette.css';*/ ?>>
</head> -->

<body>
    <!-- Botón de menú (hamburguesa) -->
    <button id="toggle-menu" class="menu-btn">☰</button>



    <!-- Menú lateral (aside) -->
    <aside class="menu_desplegable" id="menu">

        <?php foreach ($modulos as $modulo => $data): ?>

            <div class="indice">
                <p><?php echo $modulo; ?></p>
                <ul>
                    <?php foreach ($data['submodulos'] as $submodulo): ?>
                        <li>
                            <a href="<?php echo BASE_URL . $submodulo['ruta']; ?>"><?php echo $submodulo['nombre']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>

        <?php endforeach; ?>
    </aside>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menu = document.getElementById("menu");
            const toggleButton = document.getElementById("toggle-menu");

            // Mostrar/Ocultar el menú cuando se hace clic en el botón
            toggleButton.addEventListener("click", function() {
                menu.classList.toggle("active");
                toggleButton.style.display = "none"; // Esconder el botón
            });

            // Cerrar el menú al hacer clic fuera de él
            document.addEventListener("click", function(e) {
                if (!menu.contains(e.target) && !toggleButton.contains(e.target)) {
                    menu.classList.remove("active");
                    toggleButton.style.display = "block"; // Mostrar el botón de nuevo
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Seleccionar todos los elementos con submenú
            const menuItemsWithSubmenu = document.querySelectorAll(".has-submenu");

            menuItemsWithSubmenu.forEach(item => {
                item.addEventListener("click", function(e) {
                    e.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

                    // Alternar la clase activa para mostrar/ocultar el submenú
                    const parentLi = this.parentElement;
                    parentLi.classList.toggle("submenu-active");
                });
            });
        });
    </script>
</body>

</html>
