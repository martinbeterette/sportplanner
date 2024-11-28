<?php
require('../../../libs/fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        die("No se enviaron datos.");
    }

    $pdf = new FPDF();
    $pdf->AddPage();

    // Título
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Reservas', 0, 1, 'C');
    $pdf->Ln(10);

    // Agregar imágenes (gráficos)
    if (!empty($data['charts'])) {
        foreach ($data['charts'] as $chart) {
            $imgData = base64_decode($chart);
            $pdf->Image('@' . $imgData, 10, $pdf->GetY(), 190);
            $pdf->Ln(80); // Espacio después de cada gráfico
        }
    }

    // Agregar tabla de datos
    if (!empty($data['table'])) {
        $pdf->SetFont('Arial', 'B', 12);
        foreach ($data['table']['headers'] as $header) {
            $pdf->Cell(40, 10, $header, 1);
        }
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($data['table']['rows'] as $row) {
            foreach ($row as $cell) {
                $pdf->Cell(40, 10, $cell, 1);
            }
            $pdf->Ln();
        }
    }

    // Salvar PDF
    $pdf->Output('D', 'reporte_reservas.pdf');
}
