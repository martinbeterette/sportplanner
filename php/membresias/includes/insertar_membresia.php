<?php 

    
$descripcion 		= $_POST['descripcion'];
$descuento          = $_POST['descuento'];
$id_complejo        = $_POST['id_complejo'];
$precio        		= $_POST['precio'];

$sql = "INSERT INTO 
					membresia(descuento, descripcion_membresia,precio_membresia, rela_complejo)
		VALUES
			('{$descuento}','{$descripcion}', $precio,$id_complejo)";

if ($conexion->query($sql)) {
	header("Location: ../index.php");
}

?>