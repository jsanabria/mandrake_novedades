<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect.php");

$xtitulo = isset($_REQUEST["xtitulo"])?$_REQUEST["xtitulo"]:"";
$xcliente = isset($_REQUEST["xcliente"])?$_REQUEST["xcliente"]:"0";
$fecha = isset($_REQUEST["fecha"])?$_REQUEST["fecha"]:"0";

$fecha = substr($fecha, 0, 10);

$xF = explode("-", $fecha);
$GLOBALS["xfecha"] = $xF[2] . "/" . $xF[1] . "/" . $xF[0];


$GLOBALS["titulo"] = $xtitulo;

$sql = "SELECT valor1, valor2 FROM parametro WHERE codigo = '009';";
$rs = mysqli_query($link, $sql);

$arrMP = array();
while($row = mysqli_fetch_array($rs)) {
	$arrMP[$row["valor1"]] = $row["valor2"];
}

class PDF extends FPDF
{
	// Cabecera de pαgina
	function Header()
	{
		// Consulto datos de la compaρνa 
		require("../include/connect.php");
		$sql = "SELECT id FROM compania ORDER BY id ASC LIMIT 0,1;";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$cia =  $row["id"];



		$sql = "SELECT 
					a.ci_rif, a.nombre, b.campo_descripcion AS ciudad, 
					a.direccion, a.telefono1, a.email1, logo  
				FROM 
					compania AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '$cia';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$razon_social = $row["nombre"];
		$rif = $row["ci_rif"];
		$direccion = $row["direccion"]; 
		$cia =  $row["nombre"];
		$logo =  $row["logo"];

		
		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 50);
		}
		
		$this->Ln(8);
		$this->SetFont('Arial','',8);
		$this->Cell(200, 5, "Fecha: " . date("d/m/Y"),0,0,'R');
		$this->Ln();
		$this->Cell(200, 5, "Hora: " . date("H:i:s"),0,0,'R');
		$this->Ln(8);

		$this->Cell(10, 5);
		$this->SetFont('Arial','B',8);
		$this->Cell(90, 5, utf8_decode($razon_social),'0','0','L');
		$this->Ln();
		$this->Cell(10, 5);
		$this->Cell(90,5,'R.I.F.: ' . $rif,'0',0,'L');
		$this->Ln();

		$this->SetFont('Arial','B',14);
		$this->Cell(200, 6, utf8_decode($GLOBALS["titulo"]),0,0,'C');
		$this->SetFont('Arial','',12);
		$this->Ln();
		$this->Cell(200, 6, "Para la Fecha " . $GLOBALS["xfecha"],0,0,'C');
		$this->SetFont('Arial','',8);		


		$this->Ln();

		$this->Cell(200, 6, "INGRESOS POR TIPO DE PAGO", 0, 0, 'C');
		$this->SetFont('Arial','BI',8);
		$this->Ln();
		$this->Cell(10, 6);
		$this->Cell(35, 6, "TIPO DE PAGO", 1, 0, 'L');
		$this->Cell(15, 6, "# REF.", 1, 0, 'L');
		$this->Cell(30, 6, "DOCUMENTO", 1, 0, 'L');
		$this->Cell(20, 6, "NRO DOC", 1, 0, 'C');
		$this->Cell(50, 6, "CLIENTE", 1, 0, 'L');
		$this->Cell(20, 6, "MONTO BS", 1, 0, 'R');
		$this->Cell(20, 6, "MONTO USD", 1, 0, 'R');
		$this->Ln(6);


		require("../include/desconnect.php");
	}
	
	// Pie de pαgina
	function Footer()
	{
		// Posiciσn: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Nϊmero de pαgina
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($item, $TotalGTBs, $TotalGTUsd)
	{
		$this->Ln();
		$this->SetFont('Arial','BI',8);

		$this->Cell(10, 5);
		$this->Cell(150, 5, "Total General Pagos ($item): ", 0, 0, 'R');
		$this->Cell(20, 5, number_format($TotalGTBs, 2, ".", ","), 0, 0, 'R');
		$this->Cell(20, 5, number_format($TotalGTUsd, 2, ".", ","), 0, 0, 'R');
	}
}

// Creaciσn del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','BI',12);


$sql = "SELECT 
			aa.tipo, 
			CONCAT(bb.valor2, ' - ', aa.moneda) AS metodo_pago, 
			aa.doc, 
			aa.cliente, 
			aa.monto_bs AS monto_bs, 
			aa.monto_usd AS monto_usd, 
			aa.metodo_pago AS tipo_pago, aa.referencia  
		FROM (
		SELECT 
			'NOTA DE ENTREGA' AS tipo, 
			a.metodo_pago, 
			a.moneda, 
			c.nro_documento AS doc, 
			d.nombre AS cliente,  
			a.monto_bs AS monto_bs, a.monto_usd AS monto_usd, a.referencia  
		FROM 
			cobros_cliente_detalle AS a 
			JOIN cobros_cliente AS b ON b.id = a.cobros_cliente 
			LEFT OUTER JOIN salidas AS c ON c.id = b.id_documento 
			LEFT OUTER JOIN cliente AS d ON d.id = b.cliente 
		WHERE 
			a.metodo_pago NOT IN ('RC', 'PF', 'PC') 
			AND b.fecha BETWEEN '$fecha 00:00:00' AND '$fecha 23:59:59' 
			AND c.estatus = 'PROCESADO' 
		UNION ALL 
		SELECT 
			'RECIBO' AS TIPO, 
			a.metodo_pago, 
			a.moneda, 
			LPAD(a.nro_recibo, 7, '0') AS doc, 
			b.nombre AS cliente, 
			a.monto_bs AS monto_bs, 
			a.monto_usd AS monto_usd, a.referencia  
		FROM 
			recarga AS a 
			LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
		WHERE 
			a.metodo_pago NOT IN ('RC', 'PF', 'PC') 
			AND a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha 23:59:59' 
			AND (a.monto_usd > 0 OR a.reverso = 'S') 
		) AS aa 
		LEFT OUTER JOIN parametro AS bb ON bb.valor1 = aa.metodo_pago 
		WHERE bb.codigo = '009' AND aa.metodo_pago NOT IN ('RC', 'PF', 'PC') 
		ORDER BY 2, 1,3;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$TotalBs = 0;
$TotalUsd = 0;
$TotalGTBs = 0;
$TotalGTUsd = 0;
$xDoc = "";
$i = 0;


while($row = mysqli_fetch_array($rs))
{
	if($xDoc != $row["metodo_pago"]) {
		if($xDoc != "") {
			$pdf->SetFont('Arial','BI',8);
			$pdf->Cell(160, 4, "Total $xDoc ($items):", "0", "0", 'R');
			$pdf->Cell(20, 4, number_format($TotalBs, 2, ".", ","), "0", "0", 'R');
			$pdf->Cell(20, 4, number_format($TotalUsd, 2, ".", ","), "0", "0", 'R');
			$TotalBs = 0;
			$TotalUsd = 0;
			$items = 0;
			$pdf->SetFont('Arial', '', 8);
			$pdf->Ln();
			$pdf->Ln();
		}
	}

	if($row["tipo_pago"] == "RC" or $row["tipo_pago"] == "PC" or $row["tipo_pago"] == "PF") {
		$pdf->SetFont('Arial', 'BI', 8);
	} 
	else {
		$pdf->SetFont('Arial', '', 8);
	}

	$pdf->Cell(10, 4);
	// $pdf->Cell(18, 4, $row["fecha"], 0, 0, 'L');
	$pdf->Cell(35, 4, $row["metodo_pago"], 0, 0, 'L');
	$pdf->Cell(15, 4, $row["referencia"], 0, 0, 'L');
	$pdf->Cell(30, 4, $row["tipo"], 0, 0, 'L');
	$pdf->Cell(20, 4, $row["doc"], 0, 0, 'C');
	$pdf->Cell(50, 4, utf8_decode(substr($row["cliente"], 0, 45)), 0, 0, 'L');
	$pdf->Cell(20, 4, number_format($row["monto_bs"], 2, ".", ","), 0, 0, 'R');
	$pdf->Cell(20, 4, number_format($row["monto_usd"], 2, ".", ","), 0, 0, 'R');

	$pdf->Ln();

	$TotalBs += $row["monto_bs"];
	$TotalUsd += $row["monto_usd"];
	//if($row["monto_bs"] > 0) {
		$TotalGTBs += $row["monto_bs"];
		$TotalGTUsd += $row["monto_usd"];
	//}

	$items++;
	$i++;

	$xDoc = $row["metodo_pago"];

}

if($xDoc != "") {
	$pdf->SetFont('Arial','BI',8);
	$pdf->Cell(160, 4, "Total $xDoc ($items):", "0", "0", 'R');
	$pdf->Cell(20, 4, number_format($TotalBs, 2, ".", ","), "0", "0", 'R');
	$pdf->Cell(20, 4, number_format($TotalUsd, 2, ".", ","), "0", "0", 'R');
	$TotalBs = 0;
	$TotalUsd = 0;
	$pdf->SetFont('Arial', '', 8);
	$pdf->Ln();
	$pdf->Ln();
}


$pdf->EndReport($i, $TotalGTBs, $TotalGTUsd);

	
require("../include/desconnect.php");

$pdf->Output();
?>