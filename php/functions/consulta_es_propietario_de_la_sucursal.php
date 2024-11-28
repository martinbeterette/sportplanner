<?php  

function esEmpleadoDeSucursal($id_persona, $id_sucursal, $conexion) {
    // Preparar la consulta
    $sql = "SELECT COUNT(*) as total 
            FROM empleado 
            WHERE rela_persona = ? AND rela_sucursal = ?";
    
    // Preparar el statement para evitar inyección SQL
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ii', $id_persona, $id_sucursal);
    $stmt->execute();
    
    // Obtener el resultado
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    // Si hay un registro, la persona es empleada de esa sucursal
    return $data['total'] > 0;
}


?>