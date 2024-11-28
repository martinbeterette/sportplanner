<?php 
require_once('../../config/database/conexion.php');

$id = $_GET['id_usuario'];

$sqlPersona = "SELECT
                    persona.id_persona,
                    documento.id_documento,
                    contacto.id_contacto
                FROM
                        persona 
                    JOIN
                        documento 
                    ON
                        persona.rela_documento = documento.id_documento

                    JOIN
                        contacto
                    ON
                        contacto.rela_persona = persona.id_persona
                    JOIN 
                        usuarios
                    ON
                        usuarios.rela_contacto = contacto.id_contacto
                    WHERE
                        id_usuario = $id";

$registros = $conexion->query($sqlPersona);
if ($reg = $registros->fetch_assoc()) {
    $idUsuario      = $id;
    $idContacto     = $reg['id_contacto'];
    $idDocumento    = $reg['id_documento'];
    $idPersona      = $reg['id_persona'];

    echo $reg['id_persona']. ' ' .$reg['id_contacto']. ' ' .$id;
}


//eliminar el producto
$deleteUsuario = "DELETE FROM
                        usuarios 
        	       WHERE id_usuario = $idUsuario";

$deleteContacto = "DELETE FROM
                        contacto 
                    WHERE id_contacto = $idContacto";

$deleteDocumento = "DELETE FROM
                        documento 
                    WHERE id_documento = $idDocumento";

$deletePersona = "DELETE FROM
                        persona 
                    WHERE id_persona = $idPersona";

$conexion->begin_transaction();

try {
    $conexion->query($deleteUsuario); // Delete en la tabla Persona
    $conexion->query($deleteContacto); // Delete en la tabla Documento

    $conexion->commit(); // Confirma la transacción si todo salió bien
    header("Location: tablaUsuarios.php");
    exit();
} catch (Exception $e) {
    $conexion->rollback(); // Revertir cambios si algo falla
    echo "Error: " . $e->getMessage();
}
?>