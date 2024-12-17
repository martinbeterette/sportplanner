<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
session_start();
require('../../../libs/fpdf/fpdf.php');

$id_persona = 24;
// $id_persona = $_SESSION['id_persona'];
$id_usuario = $_SESSION['id_usuario'];

// Consulta con paginación
$query = "SELECT c.descripcion_complejo, 
                 m.descripcion_membresia, 
                 m.descuento, 
                 m.precio_membresia, 
                 s.fecha_alta, 
                 s.fecha_afiliacion, 
                 s.fecha_expiracion, 
                 p.nombre, 
                 p.apellido, 
                 d.descripcion_documento, 
                 con.descripcion_contacto, 
                 s.id_socio,
                 s.estado AS estado_socio
            FROM membresia m 
            JOIN socio s ON m.id_membresia = s.rela_membresia
            JOIN persona p ON s.rela_persona = p.id_persona
            JOIN documento d ON p.id_persona = d.rela_persona
            JOIN contacto con ON con.rela_persona = p.id_persona
            JOIN complejo c ON s.rela_complejo = c.id_complejo
            WHERE s.rela_persona = $id_persona";

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
        $this->Image('../../../assets/icons/prototipo_logo-Photoroom.png', 85, 10, 30); // Centrado horizontal con X = 85
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
$pdf->Cell(0, 10, "Usuario: " . $nombreUsuario, 0, 1, 'C');
$pdf->Cell(0, 10, "Fecha y Hora: " . $fechaHoraActual . "Hs", 0, 1, 'C');
$pdf->Ln(10); // Espacio debajo de la información del usuario

// Títulos de la tabla
// Títulos de la tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230); // Fondo gris claro para el encabezado

// Definir ancho total de la tabla como el 98% de la página (A4 tiene 210mm de ancho)
$totalWidth = 210 * 0.98;
$columnWidths = [
    $totalWidth * 0.20, // 20% para "Complejo"
    $totalWidth * 0.20, // 20% para "Membresia"
    $totalWidth * 0.15, // 15% para "Descuento"
    $totalWidth * 0.15, // 15% para "Precio Membresia"
    $totalWidth * 0.15, // 15% para "Expiracion"
    $totalWidth * 0.15, // 15% para "Estado"
];

// Calcular la posición X para centrar la tabla
$x = (210 - $totalWidth) / 2; // 210 es el ancho total de la página en mm

$pdf->SetX($x); // Posicionar tabla centrada
// Encabezados
$pdf->Cell($columnWidths[0], 10, 'Complejo', 1, 0, 'C', true);
$pdf->Cell($columnWidths[1], 10, 'Membresia', 1, 0, 'C', true);
$pdf->Cell($columnWidths[2], 10, 'Descuento %', 1, 0, 'C', true);
$pdf->Cell($columnWidths[3], 10, 'Precio Membresia', 1, 0, 'C', true);
$pdf->Cell($columnWidths[4], 10, 'Expiracion', 1, 0, 'C', true);
$pdf->Cell($columnWidths[5], 10, 'Estado', 1, 0, 'C', true);
$pdf->Ln();

// Datos
$pdf->SetFont('Arial', '', 10);
foreach ($misReservas as $reserva) {
    $pdf->SetX($x); // Mantener la posición X para cada fila de datos
    $pdf->Cell($columnWidths[0], 10, utf8_decode($reserva['descripcion_complejo']), 1, 0, 'C');
    $pdf->Cell($columnWidths[1], 10, utf8_decode($reserva['descripcion_membresia']), 1, 0, 'C');
    $pdf->Cell($columnWidths[2], 10, '% ' . $reserva['descuento'], 1, 0, 'C');
    $pdf->Cell($columnWidths[3], 10, '$ ' . number_format($reserva['precio_membresia'], 2), 1, 0, 'C');
    $pdf->Cell($columnWidths[4], 10, date('d/m/Y', strtotime($reserva['fecha_expiracion'])), 1, 0, 'C');
    $pdf->Cell($columnWidths[5], 10, utf8_decode($reserva['estado_socio']), 1, 0, 'C');
    $pdf->Ln();
}


// Salida del archivo
$pdf->Output('I', 'reporte_reservas.pdf');
