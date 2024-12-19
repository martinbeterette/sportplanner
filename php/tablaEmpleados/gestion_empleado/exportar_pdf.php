<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
session_start();
require('../../../libs/fpdf/fpdf.php');

// Verificar sesión y parámetros requeridos
if (!isset($_SESSION['id_persona'], $_SESSION['id_usuario'])) {
    die("Sesión no iniciada");
}

$id_persona = $_SESSION['id_persona'];
$id_usuario = $_SESSION['id_usuario'];

$id_sucursal = isset($_GET['id_sucursal']) ? intval($_GET['id_sucursal']) : die("Falta el parámetro 'id_sucursal'");

// Obtener datos de sucursal
$stmtSucursal = $conexion->prepare("SELECT descripcion_sucursal, descripcion_complejo FROM sucursal JOIN complejo ON rela_complejo = id_complejo WHERE id_sucursal = ?");
$stmtSucursal->bind_param("i", $id_sucursal);
$stmtSucursal->execute();
$resultSucursal = $stmtSucursal->get_result();

if ($resultSucursal->num_rows === 0) {
    die("Sucursal no encontrada");
}

$sucursalData = $resultSucursal->fetch_assoc();
$descripcionSucursal = $sucursalData['descripcion_sucursal'];
$descripcionComplejo = $sucursalData['descripcion_complejo'];
$stmtSucursal->close();

// Consulta de empleados
$query = "SELECT  
                empleado.id_empleado,
                persona.nombre,
                persona.apellido,
                documento.descripcion_documento,
                persona.fecha_nacimiento,
                empleado.empleado_cargo,
                empleado.fecha_alta
            FROM empleado
            JOIN persona ON empleado.rela_persona = persona.id_persona
            JOIN sucursal ON empleado.rela_sucursal = sucursal.id_sucursal
            JOIN documento ON documento.rela_persona = persona.id_persona
            WHERE empleado.estado = 1 AND empleado.rela_sucursal = ?
            ORDER BY empleado.id_empleado";

$stmtEmpleados = $conexion->prepare($query);
$stmtEmpleados->bind_param("i", $id_sucursal);
$stmtEmpleados->execute();
$resultEmpleados = $stmtEmpleados->get_result();

// Clase personalizada para el PDF
class PDF extends FPDF
{
    // Encabezado
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Image('../../../assets/icons/prototipo_logo-Photoroom.png', 85, 10, 30);
        $this->Ln(40);
        $this->Cell(0, 10, 'SPORTSPLANNER', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Reporte de Empleados', 0, 1, 'C');
        $this->Ln(5);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Crear PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Información general
$nombreUsuario = $_SESSION['usuario'];
$fechaHoraActual = date('d/m/Y H:i');

$pdf->Cell(95, 10, "Complejo: " . $descripcionComplejo, 0, 0, 'R');
$pdf->Cell(10, 10, '', 0, 0);
$pdf->Cell(95, 10, "Sucursal: " . $descripcionSucursal, 0, 1, 'L');
$pdf->Cell(95, 10, "Usuario: " . $nombreUsuario, 0, 0, 'R');
$pdf->Cell(10, 10, '', 0, 0);
$pdf->Cell(95, 10, "Fecha y Hora: " . $fechaHoraActual, 0, 1, 'L');
$pdf->Ln(10);

// Configuración de la tabla
$widths = [40, 40, 30, 35, 25, 25];
$headers = ['Nombre', 'Apellido', 'Documento', 'Fecha Nac.', 'Cargo', 'Fecha Alta'];

$pdf->SetFillColor(230, 230, 230);
$pdf->SetFont('Arial', 'B', 10);
foreach ($headers as $i => $header) {
    $pdf->Cell($widths[$i], 10, $header, 1, 0, 'C', true);
}
$pdf->Ln();

// Agregar datos
$pdf->SetFont('Arial', '', 10);
while ($row = $resultEmpleados->fetch_assoc()) {
    $pdf->Cell($widths[0], 10, $row['nombre'], 1, 0, 'C');
    $pdf->Cell($widths[1], 10, $row['apellido'], 1, 0, 'C');
    $pdf->Cell($widths[2], 10, $row['descripcion_documento'], 1, 0, 'C');
    $pdf->Cell($widths[3], 10, $row['fecha_nacimiento'], 1, 0, 'C');
    $pdf->Cell($widths[4], 10, $row['empleado_cargo'], 1, 0, 'C');
    $pdf->Cell($widths[5], 10, $row['fecha_alta'], 1, 0, 'C');
    $pdf->Ln();
}
$stmtEmpleados->close();

// Salida del archivo
$pdf->Output('I', 'reporte_empleados.pdf');
