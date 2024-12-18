<?php
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
session_start();
require('../../libs/fpdf/fpdf.php');

$id_persona = $_SESSION['id_persona'];
$id_usuario = $_SESSION['id_usuario'];

$id_complejo = isset($_GET['id_complejo']) ? $_GET['id_complejo'] : die("falta get de id_complejo");
$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("falta get de id_sucursal");

$registros_sucursal = $conexion->query("SELECT descripcion_sucursal, descripcion_complejo FROM sucursal JOIN complejo ON rela_complejo = id_complejo WHERE id_sucursal = $id_sucursal");

foreach ($registros_sucursal as $reg) {
    $descripcionSucursal = $reg['descripcion_sucursal'];
    $descripcionComplejo = $reg['descripcion_complejo'];
}

// Consulta con paginación
$query = "SELECT  id_socio as id, 
                    id_persona, 
                    nombre, 
                    apellido, 
                    descripcion_documento, 
                    descripcion_membresia, 
                    descuento 
                FROM socio 
                JOIN persona ON socio.rela_persona = persona.id_persona
                JOIN documento ON persona.id_persona = documento.rela_persona
                JOIN membresia ON socio.rela_membresia = membresia.id_membresia
                JOIN complejo ON socio.rela_complejo = complejo.id_complejo
                JOIN sucursal ON sucursal.rela_complejo = sucursal.id_sucursal
                WHERE (socio.rela_complejo = $id_complejo AND socio.estado IN(1))
                ORDER BY (id)";

$misReservas = mysqli_query($conexion, $query);

// Crear la clase personalizada para el PDF
class PDF extends FPDF
{
    // Encabezado
    function Header()
    {
        // Centrar logo y título
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, '', 0, 1, 'C'); // Espacio para el margen superior
        $this->Image('../../assets/icons/prototipo_logo-Photoroom.png', 85, 10, 30); // Centrado horizontal con X = 85
        $this->Ln(30); // Espacio debajo del logo
        $this->Cell(0, 10, 'SPORTSPLANNER', 0, 1, 'C');
        $this->Ln(5); // Espacio debajo del logo
        $this->SetFont(
            'Arial',
            'B',
            14
        );
        $this->Cell(0, 10, 'Reporte de Socios', 0, 1, 'C');
        $this->Ln(5); // Salto de línea
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15); // Posición a 1.5 cm del final
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Crear instancia del PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Información del usuario
$nombreUsuario = $_SESSION['usuario']; // Reemplazar con el usuario real
$fechaHoraActual = date('d/m/Y H:i'); // Formato de fecha y hora
$pdf->Cell(0, 10, "Complejo: " . $descripcionComplejo, 0, 1, 'C');
$pdf->Cell(0, 10, "Sucursal: " . $descripcionSucursal, 0, 1, 'C');
$pdf->Cell(0, 10, "Usuario: " . $nombreUsuario, 0, 1, 'C');
$pdf->Cell(0, 10, "Fecha y Hora: " . $fechaHoraActual, 0, 1, 'C');
$pdf->Ln(10); // Espacio debajo de la información del usuario

// Títulos de la tabla
// Títulos de la tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230); // Fondo gris claro para el encabezado

// Anchos de cada columna (96% del ancho de la página)
$widths = [40, 40, 40, 40, 41.6]; // Anchos proporcionados para ocupar 201.6 mm (96% de 210 mm)
$totalWidth = array_sum($widths); // Ancho total de la tabla

// Calcular la posición X para centrar la tabla
$x = (210 - $totalWidth) / 2; // 210 es el ancho de la página en mm (A4)

$pdf->SetX($x); // Establece la posición X para la tabla
$pdf->Cell($widths[0], 10, 'Nombre', 1, 0, 'C', true);
$pdf->Cell($widths[1], 10, 'Apellido', 1, 0, 'C', true);
$pdf->Cell($widths[2], 10, 'Documento', 1, 0, 'C', true);
$pdf->Cell($widths[3], 10, 'Membresia', 1, 0, 'C', true);
$pdf->Cell($widths[4], 10, 'Descuento %', 1, 0, 'C', true);
$pdf->Ln();

// Agregar datos
$pdf->SetFont('Arial', '', 10);
foreach ($misReservas as $reserva) {
    $pdf->SetX($x); // Asegura que los datos también se alineen correctamente
    $pdf->Cell($widths[0], 10, $reserva['nombre'], 1, 0, 'C');
    $pdf->Cell($widths[1], 10, $reserva['apellido'], 1, 0, 'C');
    $pdf->Cell($widths[2], 10, $reserva['descripcion_documento'], 1, 0, 'C');
    $pdf->Cell($widths[3], 10, $reserva['descripcion_membresia'], 1, 0, 'C');
    $pdf->Cell($widths[4], 10, $reserva['descuento'] . "%", 1, 0, 'C');
    $pdf->Ln();
}


// Salida del archivo
$pdf->Output('I', 'reporte_reservas.pdf');
