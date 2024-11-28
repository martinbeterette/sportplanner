<?php  
    require_once(RUTA. 'config/database/conexion.php');
    // session_start();
    // $_SESSION['id_perfil'] = 1;
    $id_perfil = $_SESSION['id_perfil']; // perfil del usuario almacenado en sesión
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

<header>
        <div class="menu">
            <img src="<?php echo BASE_URL; ?>assets/icons/prototipo_logo-Photoroom.png"  width="60px" height="60px">

            <ul class="cont-ul">
                
                <?php foreach ($modulos as $modulo => $data): ?>

                    <li>

                        <a href=
                            "<?php 
                                if ($data['ruta_modulo'] != '#' && !empty($data['ruta_modulo'])) {
                                    echo BASE_URL . $data['ruta_modulo'];
                                }

                            ?>" 
                        >
                            <?php echo $modulo; ?>
                                
                        </a>
                        <ul>

                            <?php foreach ($data['submodulos'] as $submodulo): ?>
                                <li><a href="<?php echo BASE_URL . $submodulo['ruta']; ?>"><?php echo $submodulo['nombre']; ?></a></li>
                            <?php endforeach; ?>

                        </ul>

                    </li>

                <?php endforeach; ?>

            </ul>

        </div>

        <div class="profile-menu">
            <button class="profile-button">Mi Perfil</button>
            <ul class="profile-dropdown">
                <li><a href="<?php echo BASE_URL; ?>login/miPerfil/mis_datos.php">Mis Datos</a></li>
                <li><a href="<?php echo BASE_URL; ?>login/cerrar_sesion.php">Cerrar Sesión</a></li>
            </ul>
        </div>

        <script src="<?php echo BASE_URL . 'js/jquery-3.7.1.min.js'; ?>"></script>
        <script src="<?php echo BASE_URL . 'js/desplegar_perfil.js'; ?>"></script>

    </header>