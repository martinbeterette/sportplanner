<?php
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
session_start();
require('../../libs/fpdf/fpdf.php');

$id_persona = $_SESSION['id_persona'];
$id_usuario = $_SESSION['id_usuario'];

$id_complejo = isset($_GET['id_complejo']) ? $_GET['id_complejo'] : die("falta get de complejo");

$sucursal_complejo = $conexion->query("SELECT descripcion_complejo, descripcion_sucursal
                                        FROM empleado e 
                                        JOIN sucursal s ON s.id_sucursal = e.rela_sucursal
                                        JOIN complejo c ON s.rela_complejo = c.id_complejo
                                        WHERE e.rela_usuario = $id_usuario");

foreach ($sucursal_complejo as $reg) {
    $descripcionSucursal = $reg['descripcion_sucursal'];
    $descripcionComplejo = $reg['descripcion_complejo'];
}

// Consulta con paginación
$query = "SELECT 
                membresia.id_membresia, 
                membresia.descripcion_membresia, 
                membresia.descuento, 
                membresia.precio_membresia, 
                complejo.descripcion_complejo
            FROM 
                membresia
            JOIN 
                complejo 
            ON 
                membresia.rela_complejo = complejo.id_complejo
            WHERE  membresia.rela_complejo = $id_complejo
            AND membresia.estado = 1
            ORDER BY membresia.id_membresia";

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
        $this->Cell(0, 10, 'Reporte de Reservas', 0, 1, 'C');
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
$widths = [60, 40, 40]; // Anchos ajustados de cada columna
$totalWidth = array_sum($widths); // Ancho total de la tabla

// Calcular la posición X para centrar la tabla
$x = ($pdf->GetPageWidth() - $totalWidth) / 2; // Ancho de página menos el ancho total de la tabla dividido por 2

$pdf->SetX($x); // Establece la posición X inicial para centrar la tabla
$pdf->Cell($widths[0], 10, utf8_decode('Membresía'), 1, 0, 'C', true);
$pdf->Cell($widths[1], 10, 'Descuento (%)', 1, 0, 'C', true);
$pdf->Cell($widths[2], 10, 'Precio ($)', 1, 0, 'C', true);
$pdf->Ln(); // Nueva línea después del encabezado

// Agregar datos
$pdf->SetFont('Arial', '', 10);
foreach ($misReservas as $reserva) {
    $pdf->SetX($x); // Asegura que las celdas de datos estén alineadas con el encabezado
    $pdf->Cell($widths[0], 10, utf8_decode($reserva['descripcion_membresia']), 1, 0, 'C');
    $pdf->Cell($widths[1], 10, $reserva['descuento'] . '%', 1, 0, 'C');
    $pdf->Cell($widths[2], 10, '$' . number_format($reserva['precio_membresia'], 2), 1, 0, 'C');
    $pdf->Ln(); // Nueva línea para la siguiente fila
}


// Salida del archivo
$pdf->Output('I', 'reporte_reservas.pdf');
