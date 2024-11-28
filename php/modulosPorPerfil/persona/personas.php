<?php  
      
    // require_once("../../../config/root_path.php");
    $conexion = new mysqli("localhost","root","","proyecto_pp2");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
        $persona = $conexion->real_escape_string($_GET['persona']);

        $sql = "SELECT id_persona, 
                       nombre,
                       apellido,
                       descripcion_documento AS documento,
                       descripcion_sexo AS sexo
                FROM persona
                JOIN documento
                ON persona.rela_documento = documento.id_documento
                JOIN sexo
                ON persona.rela_sexo = sexo.id_sexo
                WHERE nombre LIKE ? OR apellido LIKE ?";

        $stmt = $conexion->prepare($sql);
        $param = "%{$persona}%";
        $stmt->bind_param("ss", $param, $param);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $perfiles = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($reg = $resultado->fetch_assoc()) {
                $perfiles[] = $reg;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($perfiles);
    }

    $conexion->close();




    /*// require_once("../../../config/root_path.php");
    $conexion = new mysqli("localhost","root","","proyecto_pp2");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $persona = $conexion->real_escape_string($_GET['persona']);
        $sql = "SELECT  id_persona, 
                        nombre,
                        apellido,
                        descripcion_documento AS documento,
                        descripcion_sexo AS sexo
                    FROM persona
                    JOIN documento
                    ON persona.rela_documento = documento.id_documento
                    JOIN sexo
                    ON persona.rela_sexo = sexo.id_sexo";
        
        if ($persona == '') {

            $consulta = $sql;

        } else {
            // Buscar primero por nombre
            $consulta = $sql . " WHERE nombre LIKE '%$persona%'";
            $resultado = $conexion->query($consulta);

            // Si no encuentra resultados, buscar por apellido
            if ($resultado->num_rows == 0) {
                $consulta = $sql . " WHERE apellido LIKE '%$persona%'";
                $resultado = $conexion->query($consulta);
            }
        }

        // $consulta = $sql . ' ' .$condicional;
        // echo $consulta; die; // Comentado para no detener la ejecución

        $perfiles = [];

        if ($resultado && $resultado->num_rows > 0) {
            foreach ($resultado as $reg) {
                $perfiles[] = $reg;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($perfiles);
    }

    $conexion->close();
    */
?>