<?php

$id_perfil = isset($_SESSION['id_perfil']) ? $_SESSION['id_perfil'] : 1/*23*/; // perfil del usuario almacenado en sesión

$query = "SELECT m.descripcion_modulo, sm.descripcion_submodulo, m.ruta as ruta_modulo,sm.ruta as ruta_submodulo 
              FROM modulo m 
              JOIN asignacion_perfil_modulo apm ON m.id_modulo = apm.rela_modulo
              JOIN submodulo sm ON m.id_modulo = sm.rela_modulo
              WHERE apm.rela_perfil = ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_perfil);
$stmt->execute();
$asideMenu = $stmt->get_result();

$modulos = [];
while ($row = $asideMenu->fetch_assoc()) {
    $modulos[$row['descripcion_modulo']]['ruta_modulo'] = $row['ruta_modulo'];
    $modulos[$row['descripcion_modulo']]['submodulos'][] = [
        'nombre' => $row['descripcion_submodulo'],
        'ruta' => $row['ruta_submodulo']
    ];
}

?>

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