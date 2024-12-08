<?php  

function obtenerSucursalDelEmpleado($id_usuario, $id_persona) {
        global $conexion;
        $sql_sucursal_empleado = "
            SELECT s.id_sucursal 
                FROM empleado e
                JOIN sucursal s ON e.rela_sucursal = s.id_sucursal
                WHERE e.rela_persona = ?
                AND e.rela_usuario = ?";

        $stmt_sucursal_empleado = $conexion->prepare($sql_sucursal_empleado);
        $stmt_sucursal_empleado->bind_param("ii", $id_persona, $id_usuario);

        if ($stmt_sucursal_empleado->execute()) {
            $id_sucursal = $stmt_sucursal_empleado->get_result()->fetch_assoc()['id_sucursal'];
            return $id_sucursal;
        }
        return false;
    }

    function obtenerSucursalesDelPropietario($username, $id_persona, $id_usuario) {
        global $conexion;

        $sql_sucursal_empleado = "
            SELECT s.id_sucursal 
            FROM sucursal s
            JOIN complejo c ON s.rela_complejo = c.id_complejo
            JOIN asignacion_persona_complejo apc ON c.id_complejo = apc.rela_complejo
            JOIN contacto co ON co.rela_persona = apc.rela_persona
            JOIN usuarios u ON u.rela_contacto = co.id_contacto
            WHERE username = ? AND apc.rela_persona = ? AND apc.rela_usuario = ?";
        $stmt_sucursal_empleado = $conexion->prepare($sql_sucursal_empleado);
        $stmt_sucursal_empleado->bind_param("sii", $username, $id_persona, $id_usuario);

        if ($stmt_sucursal_empleado->execute()) {
            $sucursales = $stmt_sucursal_empleado->get_result();
            return $sucursales;
        }
        return false;
    }

?>