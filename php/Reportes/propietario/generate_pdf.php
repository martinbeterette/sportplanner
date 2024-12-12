<?php
require('../../../libs/fpdf/fpdf.php');

// Función para convertir base64 a imagen
function base64_to_image($base64_string, $output_file)
{
    $ifp = fopen($output_file, 'wb');
    $data = explode(',', $base64_string);
    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);
}

// Obtener los datos enviados
$data = json_decode(file_get_contents('php://input'), true);

// Directorio temporal para guardar las imágenes
$tmpDir = 'tmp/';
if (!file_exists($tmpDir)) {
    mkdir($tmpDir, 0777, true); // Crear directorio si no existe
}

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();

// ** Primera página: Encabezado e imágenes **
// Logo y título centrado
$pdf->SetFont('Arial', 'B', 16);
$pdf->Image('../../../assets/icons/prototipo_logo-Photoroom.png', 95, 10, 20); // Centrado
$pdf->Ln(25);
$pdf->Cell(0, 10, utf8_decode('SportPlanner'), 0, 1, 'C');
$pdf->Ln(10);

// Encabezado principal
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Reporte de Reservas de Canchas'), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Complejo: ' . 'YPFFFF'), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode('Fecha: ' . '10:24:12' . 'HS.    Usuario: ' . 'propietario'), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode('Criterios: ' . 'mes'), 0, 1, 'C');
$pdf->Ln(15);

if (!empty($data['reservasChart']) && !empty($data['gananciasChart'])) {
    // Decodificar y guardar las imágenes
    $reservasChart = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['reservasChart']));
    $gananciasChart = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['gananciasChart']));

    // Guardar las imágenes como archivos temporales
    file_put_contents($tmpDir . 'reservas_chart.png', $reservasChart);
    file_put_contents($tmpDir . 'ganancias_chart.png', $gananciasChart);

    // Centrar y agregar la primera imagen
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode('Reserva por Sucursal'), 0, 1, 'C');
    $pdf->Image($tmpDir . 'reservas_chart.png', ($pdf->GetPageWidth() - 140) / 2, $pdf->GetY(), 140, 60);
    $pdf->Ln(70); // Espacio después de la imagen

    // Centrar y agregar la segunda imagen
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode('Recaudación por Sucursal'), 0, 1, 'C');
    $pdf->Image($tmpDir . 'ganancias_chart.png', ($pdf->GetPageWidth() - 140) / 2, $pdf->GetY(), 140, 60);

    // Eliminar los archivos temporales
    unlink($tmpDir . 'reservas_chart.png');
    unlink($tmpDir . 'ganancias_chart.png');
}

// ** Segunda página: Tablas centradas **
$pdf->AddPage();

// Tabla de reservas
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Detalle de Reservas por Sucursal'), 0, 1, 'C');
$pdf->Ln(5);

// Cabecera
$tableWidth = 190; // Ancho total de la tabla
$startX = ($pdf->GetPageWidth() - $tableWidth) / 2; // Calcular margen izquierdo
$pdf->SetX($startX);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(40, 10, utf8_decode('Sucursal'), 1, 0, 'C', true);
$pdf->Cell(30, 10, utf8_decode('Total'), 1, 0, 'C', true);
$pdf->Cell(30, 10, utf8_decode('Canceladas'), 1, 0, 'C', true);
$pdf->Cell(30, 10, utf8_decode('Ausentes'), 1, 0, 'C', true);
$pdf->Cell(30, 10, utf8_decode('Finalizadas'), 1, 0, 'C', true);
$pdf->Cell(30, 10, utf8_decode('Recaudacion'), 1, 1, 'C', true);

// Cuerpo
$pdf->SetFont('Arial', '', 10);
$isGray = false;
foreach ($data['reservasData'] as $row) {
    $pdf->SetFillColor($isGray ? 240 : 255, $isGray ? 240 : 255, $isGray ? 240 : 255);
    $pdf->SetX($startX);
    $pdf->Cell(40, 10, utf8_decode($row['sucursal']), 1, 0, 'C', true);
    $pdf->Cell(30, 10, utf8_decode($row['total']), 1, 0, 'C', true);
    $pdf->Cell(30, 10, utf8_decode($row['canceladas']), 1, 0, 'C', true);
    $pdf->Cell(30, 10, utf8_decode($row['ausentes']), 1, 0, 'C', true);
    $pdf->Cell(30, 10, utf8_decode($row['finalizadas']), 1, 0, 'C', true);
    $pdf->Cell(30, 10, utf8_decode($row['ganancias']), 1, 1, 'C', true);
    $isGray = !$isGray;
}

// Tabla de empleados
$pdf->Ln(10); // Espacio entre tablas
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Empleados por Sucursal'), 0, 1, 'C');
$pdf->Ln(5);

$startX = ($pdf->GetPageWidth() - 180) / 2; // Centrar tabla de empleados
$pdf->SetX($startX);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(90, 10, utf8_decode('Sucursal'), 1, 0, 'C', true);
$pdf->Cell(90, 10, utf8_decode('Empleados'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$isGray = false;
foreach ($data['empleadosData'] as $row) {
    $pdf->SetFillColor($isGray ? 240 : 255, $isGray ? 240 : 255, $isGray ? 240 : 255);
    $pdf->SetX($startX);
    $pdf->Cell(90, 10, utf8_decode($row['sucursal']), 1, 0, 'C', true);
    $pdf->Cell(90, 10, utf8_decode($row['empleados']), 1, 1, 'C', true);
    $isGray = !$isGray;
}

// Generar y mostrar el PDF
$pdf->Output();
