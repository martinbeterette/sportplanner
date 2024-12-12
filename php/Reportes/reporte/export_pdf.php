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
$reservationsChartImg = $_POST['reservationsChartImg'];
$popularCourtsChartImg = $_POST['popularCourtsChartImg'];
$dayNightComparisonChartImg = $_POST['dayNightComparisonChartImg'];
$reservationData = json_decode($_POST['reservationData']);
$tarifasData = json_decode($_POST['tarifasData']);
$fechaHora = $_POST['fechaHora'];
$usuario = $_POST['usuario'];
$criterios = $_POST['criterios'];
$descripcionsucursal = $_POST['descripcionsucursal'];
$descripcionComplejo = $_POST['descripcionComplejo'];

// Directorio temporal para guardar las imágenes
$tmpDir = 'tmp/';
if (!file_exists($tmpDir)) {
    mkdir($tmpDir, 0777, true); // Crear directorio si no existe
}

// Convertir las imágenes base64 a archivos temporales
$reservationsChartImgPath = $tmpDir . 'reservations_chart.png';
$popularCourtsChartImgPath = $tmpDir . 'popular_courts_chart.png';
$dayNightComparisonChartImgPath = $tmpDir . 'day_night_comparison_chart.png';

base64_to_image($reservationsChartImg, $reservationsChartImgPath);
base64_to_image($popularCourtsChartImg, $popularCourtsChartImgPath);
base64_to_image($dayNightComparisonChartImg, $dayNightComparisonChartImgPath);

// Crear un nuevo documento PDF
$pdf = new FPDF();
$pdf->AddPage();

// ** Primera página **
// Logo y título centrado
$pdf->SetFont('Arial', 'B', 16);
$pdf->Image('../../../assets/icons/prototipo_logo-Photoroom.png', 95, 10, 20); // Centrado (x = 95 para A4)
$pdf->Ln(25);
$pdf->Cell(0, 10, utf8_decode('SportPlanner'), 0, 1, 'C');
$pdf->Ln(10);

// Encabezado principal: título del reporte
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Reporte de Reservas de Canchas'), 0, 1, 'C');
$pdf->Ln(5);

// Complejo y sucursal en la misma línea
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Complejo: ' . $descripcionComplejo . '    Sucursal: ' . $descripcionsucursal), 0, 1, 'C');

// Fecha y usuario en la misma línea
$pdf->Cell(0, 10, utf8_decode('Fecha: ' . $fechaHora . 'HS    Usuario: ' . $usuario), 0, 1, 'C');

// Criterios centrados en una línea
$pdf->Cell(0, 10, utf8_decode('Criterios: ' . $criterios), 0, 1, 'C');
$pdf->Ln(15);

// Título y gráfico de la primera sección
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Días con más Reservas'), 0, 1, 'C');
$pdf->Ln(5);
$pdf->Image($reservationsChartImgPath, 30, $pdf->GetY(), 150, 90);

// Agregar nueva página
$pdf->AddPage();

// ** Segunda página **
// Título y gráfico de la segunda sección
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Canchas más Reservadas'), 0, 1, 'C');
$pdf->Ln(5);
$pdf->Image($popularCourtsChartImgPath, 30, $pdf->GetY(), 150, 90);
$pdf->Ln(100);

// Tabla de reservaciones por cancha
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(32, 10, utf8_decode('Cancha'), 1, 0, 'C', true);
$pdf->Cell(32, 10, utf8_decode('Total Reservas'), 1, 0, 'C', true);
$pdf->Cell(32, 10, utf8_decode('Finalizados'), 1, 0, 'C', true);
$pdf->Cell(32, 10, utf8_decode('Ausentes'), 1, 0, 'C', true);
$pdf->Cell(32, 10, utf8_decode('Cancelados'), 1, 0, 'C', true);
$pdf->Cell(32, 10, utf8_decode('Recaudación'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 12);
$isGray = false;
foreach ($reservationData as $row) {
    $pdf->SetFillColor($isGray ? 240 : 255, $isGray ? 240 : 255, $isGray ? 240 : 255);
    $pdf->Cell(32, 10, utf8_decode($row[0]), 1, 0, 'C', true);
    $pdf->Cell(32, 10, utf8_decode($row[1]), 1, 0, 'C', true);
    $pdf->Cell(32, 10, utf8_decode($row[2]), 1, 0, 'C', true);
    $pdf->Cell(32, 10, utf8_decode($row[3]), 1, 0, 'C', true);
    $pdf->Cell(32, 10, utf8_decode($row[4]), 1, 0, 'C', true);
    $pdf->Cell(32, 10, utf8_decode($row[5]), 1, 1, 'C', true);
    $isGray = !$isGray;
}

$pdf->Ln(160);
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Turnos más Reservados'), 0, 1, 'C');
$pdf->Ln(5);

$pdf->Image($dayNightComparisonChartImgPath, 30, $pdf->GetY(), 150, 90);
$pdf->Ln(100);

// Segunda tabla de tarifas
// Configurar el ancho de la tabla y el inicio en el centro
$totalWidth = 126; // Suma de los anchos de las columnas: 63 + 63
$pageWidth = $pdf->GetPageWidth();
$marginLeft = ($pageWidth - $totalWidth) / 2;

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 220, 255);

// Mover el cursor al margen calculado antes de dibujar la tabla
$pdf->SetX($marginLeft);

// Dibujar encabezados
$pdf->Cell(63, 10, utf8_decode('Descripción Tarifa'), 1, 0, 'C', true);
$pdf->Cell(63, 10, utf8_decode('Total Reservas'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 12);
$isGray = false;

foreach ($tarifasData as $row) {
    // Establecer el color de relleno alternado
    $pdf->SetFillColor($isGray ? 240 : 255, $isGray ? 240 : 255, $isGray ? 240 : 255);

    // Mover el cursor al margen calculado para centrar cada fila
    $pdf->SetX($marginLeft);

    $pdf->Cell(63, 10, utf8_decode($row[0]), 1, 0, 'C', true);
    $pdf->Cell(63, 10, utf8_decode($row[1]), 1, 1, 'C', true);

    $isGray = !$isGray; // Alternar color de fondo
}

// Guardar el PDF
$pdfFilePath = 'path_to_generated_pdf.pdf';
$pdf->Output('F', $pdfFilePath);

// Devolver la URL
echo json_encode(['pdfUrl' => $pdfFilePath]);
