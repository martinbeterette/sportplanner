<?php
require_once("../../../libs/fpdf/fpdf.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sociosTableHTML = $_POST["sociosTableHTML"];
    $membresiasTableHTML = $_POST["membresiasTableHTML"];
    $sociosImage = $_POST["sociosImage"];
    $membresiasImage = $_POST["membresiasImage"];

    // Decodificar imágenes (Base64 a archivo temporal)
    $sociosImgPath = "temp_socios.png";
    $membresiasImgPath = "temp_membresias.png";

    file_put_contents($sociosImgPath, base64_decode(explode(",", $sociosImage)[1]));
    file_put_contents($membresiasImgPath, base64_decode(explode(",", $membresiasImage)[1]));

    // Crear PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Título
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Socios y Membresias', 0, 1, 'C');
    $pdf->Ln(10);

    // Gráfico de Socios
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Grafico de Socios con mas Reservas', 0, 1, 'L');
    $pdf->Image($sociosImgPath, 10, $pdf->GetY(), 190);
    $pdf->Ln(90);

    // Gráfico de Membresías
    $pdf->Cell(0, 10, 'Grafico de Membresias mas Solicitadas', 0, 1, 'L');
    $pdf->Image($membresiasImgPath, 10, $pdf->GetY(), 190);
    $pdf->Ln(90);

    // Tablas
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Tabla de Socios con mas Reservas', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);

    // Convertir tabla HTML a texto
    $sociosLines = strip_tags(str_replace("</tr>", "\n", $sociosTableHTML));
    $sociosLines = explode("\n", $sociosLines);

    foreach ($sociosLines as $line) {
        $pdf->MultiCell(0, 10, $line);
    }

    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Tabla de Membresias mas Solicitadas', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);

    $membresiasLines = strip_tags(str_replace("</tr>", "\n", $membresiasTableHTML));
    $membresiasLines = explode("\n", $membresiasLines);

    foreach ($membresiasLines as $line) {
        $pdf->MultiCell(0, 10, $line);
    }

    // Guardar PDF
    $filePath = "reporte_socios_membresias.pdf";
    $pdf->Output('F', $filePath);

    // Limpiar imágenes temporales
    unlink($sociosImgPath);
    unlink($membresiasImgPath);

    // Devolver la URL del PDF generado
    echo $filePath;
}
