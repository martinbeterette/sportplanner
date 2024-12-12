<?php 

    
$descripcion 		= $_POST['descripcion'];
$descuento          = $_POST['descuento'];
$id_complejo        = $_POST['id_complejo'];
$precio        		= $_POST['precio'];
$id_membresia        = $_POST['id_membresia'];

$sql = "UPDATE
					membresia SET 
						descuento = '$descuento', 
						descripcion_membresia = '$descripcion',
						precio_membresia = $precio, 
						rela_complejo = $id_complejo
						WHERE id_membresia = $id_membresia";

if ($conexion->query($sql)) {
	header("Location: ../index.php");
}

?>