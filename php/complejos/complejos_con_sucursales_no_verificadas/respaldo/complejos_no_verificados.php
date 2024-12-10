<?php  
session_start();
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

function obtenerComplejosNoVerificados($conexion) {
    $sql = "SELECT * FROM complejo WHERE verificado LIKE 'no verificado'";
    $result = mysqli_query($conexion, $sql);
    $complejos = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $complejos[] = $row;
        }
    }
    return $complejos;
}

$complejos_no_verificados = obtenerComplejosNoVerificados($conexion);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Complejos No Verificados</title>
    <link rel="stylesheet" href="css/listado_complejos.css">
</head>
<body>
    <div class="contenedor-listado">
        <h2>Complejos No Verificados</h2>
        <?php if (count($complejos_no_verificados) > 0): ?>
            <table class="tabla-complejos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Fecha de Fundación</th>
                        <th>Fecha de insercion del registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($complejos_no_verificados as $complejo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($complejo['id_complejo']); ?></td>
                            <td><?php echo htmlspecialchars($complejo['descripcion_complejo']); ?></td>
                            <td><?php echo htmlspecialchars($complejo['fecha_fundacion']); ?></td>
                            <td><?php echo htmlspecialchars($complejo['fecha_alta']); ?></td>
                            <td>
                                <button class="btn-verificar">Verificar</button>
                                <button class="btn-eliminar">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="mensaje-vacio">No hay complejos no verificados.</p>
        <?php endif; ?>
    </div>
</body>
</html>
