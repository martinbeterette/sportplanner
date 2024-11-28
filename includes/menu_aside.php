<?php  
    // session_start();
    // $_SESSION['id_perfil'] = 1;
    $id_perfil = isset($_SESSION['id_perfil']) ? $_SESSION['id_perfil'] : die("no hay perfil");/*23*/; // perfil del usuario almacenado en sesión
    /*
    $querySinRuta = "SELECT m.descripcion_modulo, sm.descripcion_submodulo
              FROM modulo m 
              JOIN asignacion_perfil_modulo apm ON m.id_modulo = apm.rela_modulo
              JOIN submodulo sm ON m.id_modulo = sm.rela_modulo
              WHERE apm.rela_perfil = ?";
    */
    $query_modulos = "SELECT m.descripcion_modulo, sm.descripcion_submodulo, m.ruta as ruta_modulo,sm.ruta as ruta_submodulo 
              FROM modulo m 
              JOIN asignacion_perfil_modulo apm ON m.id_modulo = apm.rela_modulo
              JOIN submodulo sm ON m.id_modulo = sm.rela_modulo
              WHERE apm.rela_perfil = ?";

    $stmt_modulos = $conexion->prepare($query_modulos);
    $stmt_modulos->bind_param("i", $id_perfil);
    $stmt_modulos->execute();
    $result_modulos = $stmt_modulos->get_result();

    $modulos = [];
    while ($row = $result_modulos->fetch_assoc()) {
        $modulos[$row['descripcion_modulo']]['ruta_modulo'] = $row['ruta_modulo'];
        $modulos[$row['descripcion_modulo']]['submodulos'][] = [
            'nombre' => $row['descripcion_submodulo'],
            'ruta' => $row['ruta_submodulo']
        ];
    }



?>
    <!-- Botón de menú (hamburguesa) -->
    <!-- <div class="menu-btn" > -->
        <!-- &#9776; <!-- Icono de las tres rayas --> 
    <!-- </div> -->
    <button class="menu-btn" id="toggle-menu">☰</button>
    <aside id="aside-menu" class="menu_desplegable">

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




