<?php  

function esPropietarioDelComplejo($id_usuario, $id_complejo, $conexion) {
    // Preparar la consulta
    $sql = "SELECT COUNT(*) as total 
            FROM asignacion_persona_complejo 
            WHERE rela_usuario = ? AND rela_complejo = ?";
    
    // Preparar el statement para evitar inyección SQL
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ii', $id_usuario, $id_complejo);
    $stmt->execute();
    
    // Obtener el resultado
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    // Si hay un registro, la persona es propietaria del complejo
    return $data['total'] > 0;
}

?>