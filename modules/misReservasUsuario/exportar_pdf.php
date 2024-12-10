<?php
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
session_start();
require('../../libs/fpdf/fpdf.php');

$id_persona = $_SESSION['id_persona'];
$id_usuario = $_SESSION['id_usuario'];

// Consulta con paginación
$query = "SELECT r.id_reserva, p.nombre, 
            r.fecha_reserva, 
            h.horario_inicio,
            s.descripcion_sucursal AS sucursal, 
            z.descripcion_zona AS zona, 
            fd.descripcion_formato_deporte AS formato,
            er.descripcion_estado_reserva AS estado,
            co.monto_base
          FROM reserva r
          JOIN persona p ON r.rela_persona = p.id_persona
          JOIN horario h ON r.rela_horario = h.id_horario
          JOIN zona z ON r.rela_zona = z.id_zona
          JOIN sucursal s ON z.rela_sucursal = s.id_sucursal
          JOIN formato_deporte fd ON z.rela_formato_deporte = fd.id_formato_deporte
          JOIN tipo_terreno tt ON z.rela_tipo_terreno = tt.id_tipo_terreno
          JOIN control co ON co.rela_reserva = r.id_reserva
          JOIN estado_reserva er ON r.rela_estado_reserva = er.id_estado_reserva
          WHERE r.rela_persona = $id_persona";

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
$pdf->Cell(0, 10, "Usuario: " . $nombreUsuario, 0, 1, 'C');
$pdf->Cell(0, 10, "Fecha y Hora: " . $fechaHoraActual, 0, 1, 'C');
$pdf->Ln(10); // Espacio debajo de la información del usuario

// Títulos de la tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230); // Fondo gris claro para el encabezado
$widths = [30, 30, 30, 30, 30, 30, 30]; // Anchos de cada columna
$totalWidth = array_sum($widths); // Ancho total de la tabla

// Calcular la posición X para centrar la tabla
$x = (210 - $totalWidth) / 2; // 210 es el ancho de la página en mm (A4)

$pdf->SetX($x); // Establece la posición X para la tabla
$pdf->Cell($widths[0], 10, 'Sucursal', 1, 0, 'C', true);
$pdf->Cell($widths[1], 10, 'Zona', 1, 0, 'C', true);
$pdf->Cell($widths[2], 10, 'Formato', 1, 0, 'C', true);
$pdf->Cell($widths[3], 10, 'Fecha Reserva', 1, 0, 'C', true);
$pdf->Cell($widths[4], 10, 'Hora Inicio', 1, 0, 'C', true);
$pdf->Cell($widths[5], 10, 'Monto Base', 1, 0, 'C', true);
$pdf->Cell($widths[6], 10, 'Estado', 1, 0, 'C', true);
$pdf->Ln();

// Agregar datos
$pdf->SetFont('Arial', '', 10);
foreach ($misReservas as $reserva) {
    $pdf->SetX($x); // Asegura que los datos también se alineen correctamente
    $pdf->Cell($widths[0], 10, $reserva['sucursal'], 1, 0, 'C');
    $pdf->Cell($widths[1], 10, $reserva['zona'], 1, 0, 'C');
    $pdf->Cell($widths[2], 10, $reserva['formato'], 1, 0, 'C');
    $pdf->Cell($widths[3], 10, $reserva['fecha_reserva'], 1, 0, 'C');
    $pdf->Cell($widths[4], 10, $reserva['horario_inicio'], 1, 0, 'C');
    $pdf->Cell($widths[5], 10, '$' . $reserva['monto_base'], 1, 0, 'C');
    $pdf->Cell($widths[6], 10, $reserva['estado'], 1, 0, 'C');
    $pdf->Ln();
}

// Salida del archivo
$pdf->Output('I', 'reporte_reservas.pdf');
