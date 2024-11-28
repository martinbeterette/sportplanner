<?php 
require_once(RUTA . "config/database/conexion.php");

function obtenerRegistros($tabla, $campos, $join = '', $condicion = '', $orden = '') {
    global $conexion;

    // Construye la consulta SQL
    $sql = 'SELECT ' . implode(', ', $campos) . ' FROM ' . $tabla;

    // Agrega el JOIN si es proporcionado
    if ($join) {
        $sql .= ' ' . $join;
    }

    // Agrega la condición si es proporcionada
    if ($condicion) {
        $sql .= ' WHERE ' . $condicion;
    }

    //agregamos orden si existe
    if ($orden) {
        $sql .= ' ORDER BY ';
    }

    // Ejecuta la consulta
    $resultado = $conexion->query($sql);

    // Verifica si hay error en la consulta
    if (!$resultado) {
        die('Error en la consulta: ' . $conexion->error);
    }

    // Devuelve los resultados en un array asociativo
    return $resultado;
} 


?>