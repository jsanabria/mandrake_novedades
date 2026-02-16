<?php
session_start();
require('rcs/fpdf.php');
require("../include/connect.php");

$fecha_desde = '2025-01-01'; // isset($_REQUEST["fecha_desde"]) ? $_REQUEST["fecha_desde"] : date("Y-m-d");
$fecha_hasta = isset($_REQUEST["fecha_hasta"]) ? $_REQUEST["fecha_hasta"] : date("Y-m-d");

$GLOBALS["titulo"] = "DETALLE NOTAS DE CREDITO";
$GLOBALS["subtitulo"] = "DESDE " . date("d/m/Y", strtotime($fecha_desde)) . " HASTA " . date("d/m/Y", strtotime($fecha_hasta));

class PDF extends FPDF {
    function Header() {
        require("../include/connect.php");
        $rs = mysqli_query($link, "SELECT nombre, logo FROM compania LIMIT 1");
        $row = mysqli_fetch_array($rs);
        if(!empty($row["logo"])) {
            $this->Image("../carpetacarga/".$row["logo"], 10, 8, 30);
        }
        
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, $GLOBALS["titulo"], 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, $GLOBALS["subtitulo"], 0, 1, 'C');
        $this->Ln(5);

        // Encabezados Verticales
        $this->SetFillColor(230, 230, 230);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 7, 'TIPO', 1, 0, 'C', true);
        $this->Cell(25, 7, 'CEDULA', 1, 0, 'C', true);
        $this->Cell(55, 7, 'CLIENTE', 1, 0, 'L', true);
        $this->Cell(20, 7, 'FECHA', 1, 0, 'C', true);
        $this->Cell(25, 7, 'USUARIO', 1, 0, 'C', true);
        $this->Cell(25, 7, 'BS.', 1, 0, 'R', true);
        $this->Cell(25, 7, 'USD', 1, 0, 'R', true);
        $this->Ln();
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ').$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

$sql = "SELECT 'ABONO Bs' AS tipo, c.ci_rif AS cedula, c.nombre AS cliente, a.fecha, a.monto_bs, a.monto_usd, b.nota, a.username 
        FROM recarga AS a 
        JOIN abono AS b ON b.id = a.abono 
        JOIN cliente AS c ON c.id = a.cliente 
        WHERE a.metodo_pago IN ('NC') AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
        UNION 
        SELECT 'ABONO USD' AS tipo, c.ci_rif AS cedula, c.nombre AS cliente, a.fecha, a.monto_bs, a.monto_usd, b.nota, a.username 
        FROM recarga2 AS a 
        JOIN abono2 AS b ON b.id = a.abono 
        JOIN cliente AS c ON c.id = a.cliente 
        WHERE a.metodo_pago IN ('NC') AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
        ORDER BY fecha DESC";

$rs = mysqli_query($link, $sql) or die(mysqli_error($link));

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);

$totalBs = 0; $totalUsd = 0; $contador = 0;

while($row = mysqli_fetch_array($rs)) {
    // Fila principal
    $pdf->Cell(20, 6, $row["tipo"], 'T', 0, 'L');
    $pdf->Cell(25, 6, $row["cedula"], 'T', 0, 'L');
    $pdf->Cell(55, 6, substr(utf8_decode($row["cliente"]), 0, 32), 'T', 0, 'L');
    $pdf->Cell(20, 6, date("d/m/Y", strtotime($row["fecha"])), 'T', 0, 'C');
    $pdf->Cell(25, 6, $row["username"], 'T', 0, 'C');
    $pdf->Cell(25, 6, number_format($row["monto_bs"], 2, ",", "."), 'T', 0, 'R');
    $pdf->Cell(25, 6, number_format($row["monto_usd"], 2, ",", "."), 'T', 0, 'R');
    $pdf->Ln();

    // Fila secundaria para la NOTA (MultiCell)
    $pdf->SetFont('Arial', 'I', 7);
    $pdf->Cell(20, 4, 'NOTA:', 'B', 0, 'R'); // Etiqueta pequeña
    $pdf->MultiCell(175, 4, utf8_decode($row["nota"]), 'B', 'L'); 
    $pdf->SetFont('Arial', '', 8);
    
    $totalBs += $row["monto_bs"];
    $totalUsd += $row["monto_usd"];
    $contador++;

    // Salto de página manual si se acerca al final
    if($pdf->GetY() > 240) $pdf->AddPage();
}

// Totales Finales
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(50, 6, "REGISTROS: " . $contador, 0, 0, 'L');
$pdf->Cell(95, 6, "TOTALES: ", 0, 0, 'R');
$pdf->Cell(25, 6, number_format($totalBs, 2, ",", "."), 0, 0, 'R');
$pdf->Cell(25, 6, number_format($totalUsd, 2, ",", "."), 0, 0, 'R');

require("../include/desconnect.php");
$pdf->Output();
?>