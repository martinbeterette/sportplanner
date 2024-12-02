<?php  
$id_perfil = isset($_SESSION['id_perfil']) ? $_SESSION['id_perfil'] : die("no hay perfil"); // Verifica el perfil

// Consulta los módulos con permisos para el perfil actual
$sql = "
    SELECT m.id_modulo, m.ruta, m.padre, m.nombre 
    FROM modulo_prueba m 
    JOIN modulo_por_perfil p ON m.id_modulo = p.rela_modulo 
    WHERE p.rela_perfil = $id_perfil
";
$resultado = $conexion->query($sql);

// Almacena los módulos por jerarquía
$modulos = [];
while ($fila = $resultado->fetch_assoc()) {
    // Si el padre es NULL, vacío o 0, lo tratamos como raíz
    $padre = $fila['padre'] ?: null; 
    $modulos[$padre][] = $fila; // Clasifica los módulos por su padre
}

// Genera el menú
$menu = construir_menu($modulos);
?>

<!-- Botón de menú (hamburguesa) -->
<button class="menu-btn" id="toggle-menu">☰</button>
<aside id="aside-menu" class="menu_desplegable">
    <?php echo $menu; // Muestra el menú dinámico ?>
</aside>

<?php 
function construir_menu($modulos, $padre = null) {
    $html = '<ul class="menu">'; // Abre la lista de menú
    if (isset($modulos[$padre])) {
        foreach ($modulos[$padre] as $modulo) {
            $esIndice = empty($modulo['ruta']) || $modulo['ruta'] === '#';
            $html .= '<li class="menu-item">';

            // Contenido del módulo (enlace o índice)
            $html .= '<div class="indice">';
            if ($esIndice) {
                $html .= '<p>' . htmlspecialchars($modulo['nombre']) . '</p>';
            } else {
                $html .= '<a href="' . htmlspecialchars(BASE_URL . $modulo['ruta']) . '">' . htmlspecialchars($modulo['nombre']) . '</a>';
            }
            $html .= '</div>';

            // Llamada recursiva para los hijos de este módulo
            $html .= construir_menu($modulos, $modulo['id_modulo']);
            $html .= '</li>';
        }
    }
    $html .= '</ul>'; // Cierra la lista de menú
    return $html;
}
?>
