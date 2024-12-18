<?php
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
session_start();
require('../../libs/fpdf/fpdf.php');

$id_persona = $_SESSION['id_persona'];
$id_usuario = $_SESSION['id_usuario'];

$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("falta get de id_sucursal");

$registros_sucursal = $conexion->query("SELECT descripcion_sucursal, descripcion_complejo FROM sucursal JOIN complejo ON rela_complejo = id_complejo WHERE id_sucursal = $id_sucursal");

foreach ($registros_sucursal as $reg) {
    $descripcionSucursal = $reg['descripcion_sucursal'];
    $descripcionComplejo = $reg['descripcion_complejo'];
}

// Consulta con paginación
$query = "SELECT  
                        id_zona,
                        descripcion_zona,
                        descripcion_tipo_terreno,
                        descripcion_formato_deporte,
                        descripcion_estado_zona
            FROM zona 
            JOIN servicio ON zona.rela_servicio = servicio.id_servicio
            JOIN sucursal ON zona.rela_sucursal = sucursal.id_sucursal
            JOIN formato_deporte ON  zona.rela_formato_deporte = formato_deporte.id_formato_deporte
            JOIN tipo_terreno ON zona.rela_tipo_terreno = tipo_terreno.id_tipo_terreno
            JOIN  estado_zona ON estado_zona.id_estado_zona = zona.rela_estado_zona
            WHERE id_sucursal = $id_sucursal AND (zona.estado IN(1))
            ORDER BY (zona.id_zona)";

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
        $this->Cell(0, 10, 'Reporte de Canchas', 0, 1, 'C');
        $this->Ln(-5); // Salto de línea
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
// Información del usuario
$nombreUsuario = $_SESSION['usuario']; // Reemplazar con el usuario real
$fechaHoraActual = date('d/m/Y H:i'); // Formato de fecha y hora

// Agregar cabecera con dos filas
$pdf->SetFont('Arial', '', 10);

// Complejo y sucursal en la misma fila
$pdf->Cell(0, 10, '', 0, 1, 'C'); // Espacio para el margen superior
$pdf->Cell(95, 10, "Complejo: " . $descripcionComplejo, 0, 0, 'R'); // Alineado a la derecha
$pdf->Cell(10, 10, '', 0, 0); // Espacio entre columnas
$pdf->Cell(95, 10, "Sucursal: " . $descripcionSucursal, 0, 1, 'L'); // Alineado a la izquierda

// Usuario y fecha/hora en la misma fila
$pdf->Cell(95, 10, "Usuario: " . $nombreUsuario, 0, 0, 'R'); // Alineado a la derecha
$pdf->Cell(10, 10, '', 0, 0); // Espacio entre columnas
$pdf->Cell(95, 10, "Fecha y Hora: " . $fechaHoraActual, 0, 1, 'L'); // Alineado a la izquierda

$pdf->Ln(10); // Espacio debajo de la cabecera

// Títulos de la tabla centrada
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230); // Fondo gris claro para el encabezado
$widths = [50, 50, 50, 40]; // Anchos de cada columna
$totalWidth = array_sum($widths); // Ancho total de la tabla

// Calcular la posición X para centrar la tabla
$x = ($pdf->GetPageWidth() - $totalWidth) / 2; // Centrar la tabla

$pdf->SetX($x); // Establece la posición X para la tabla
$pdf->Cell($widths[0], 10, 'Cancha', 1, 0, 'C', true);
$pdf->Cell($widths[1], 10, 'Superficie', 1, 0, 'C', true);
$pdf->Cell($widths[2], 10, 'Formato Deporte', 1, 0, 'C', true);
$pdf->Cell($widths[3], 10, 'Estado', 1, 0, 'C', true);
$pdf->Ln();

// Agregar datos centrados
$pdf->SetFont('Arial', '', 10);
foreach ($misReservas as $reserva) {
    $pdf->SetX($x); // Asegura que los datos también se alineen correctamente
    $pdf->Cell($widths[0], 10, $reserva['descripcion_zona'], 1, 0, 'C');
    $pdf->Cell($widths[1], 10, $reserva['descripcion_tipo_terreno'], 1, 0, 'C');
    $pdf->Cell($widths[2], 10, $reserva['descripcion_formato_deporte'], 1, 0, 'C');
    $pdf->Cell($widths[3], 10, $reserva['descripcion_estado_zona'], 1, 0, 'C');
    $pdf->Ln();
}

// Salida del archivo
$pdf->Output('I', 'reporte_reservas.pdf');
