<?php
require('rcs/fpdf.php');
require("connect.php");

$recibo = isset($_REQUEST["recibo"])?$_REQUEST["recibo"]:"0";
$GLOBALS["titulo_reporte"] = "CERTIFICACION LABORAL USA";

class PDF extends FPDF
{
	// Cabecera de pαgina
	function Header()
	{
	    $this->Image('../phpimages/logo_original.png',5,5,50);

	}
	
	// Pie de pαgina
	function Footer()
	{
		// Posiciσn: a 1,5 cm del final
		$this->SetY(-40);
		$this->SetFont('ARIAL','BI');
		$this->Cell(5,5);
		$this->Cell(160,5,"Sub Total: ",0,0,'R');
		$this->Cell(35,5,number_format($GLOBALS["submonto"], 2, '.', ','),1,0,'R');
		$this->Ln();
		$this->Cell(5,5);
		$this->Cell(160,5,"Tax: " . number_format($GLOBALS["totaltax"], 2, '.', ',') . "%",0,0,'R');
		$this->Cell(35,5,number_format($GLOBALS["totaltax"], 2, '.', ','),1,0,'R');
		$this->Ln();
		$this->Cell(5,5);
		$this->Cell(160,5,"Total: ",0,0,'R');
		$this->Cell(35,5,number_format($GLOBALS["monto"], 2, '.', ','),1,0,'R');
		$this->Ln(10);
		$this->SetFont('ARIAL','',10);
		$this->Cell(5,15);
		$this->Cell(195,15,"Note: " . $GLOBALS["nota"],1,0,'L');
		$this->Ln();

		// Arial italic 8
		//$this->SetFont('Arial','I',8);
		// Nϊmero de pαgina
		//$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($recibo, $representante, $cia, $firma)
	{
	}
}

// Creaciσn del objeto de la clase heredada
$pdf = new PDF();
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

$sql = "SELECT valor1 AS cia, valor2 AS email, valor3 AS address, valor4 AS webpage FROM parametro WHERE codigo = '001';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cia = $row["cia"];
$email = $row["email"];
$address = $row["address"];
$webpage = $row["webpage"];

$sql = "SELECT valor1 AS phone1, valor2 AS phone2 FROM parametro WHERE codigo = '002';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$phone1 = $row["phone1"];
$phone2 = $row["phone2"];

$sql = "SELECT 
			a.certificacion, date_format(a.fecha, '%m-%d-%Y') AS fecha, 
			a.monto, c.cliente_nombre, c.cliente_apellido, c.ciudad, c.codigo_postal, 
			c.pais, c.address1, c.address2, c.telefono1, c.telefono2, date_format(a.fecha_pago, '%m-%d-%Y') AS fecha_pago, 
			a.nota, a.anulado       
		FROM 
			pagos a 
			LEFT OUTER JOIN parametro b ON b.valor1 = a.tipo_pago AND b.codigo = '004' 
			JOIN certificaciones c ON c.Ncertificacion = a.certificacion 
		WHERE Npago = '$recibo';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$fecha = $row["fecha"];
$phone2 = $row["phone2"];

$monto = $row["monto"];
$GLOBALS["submonto"] = $monto;
$GLOBALS["tax"] = 0;
$GLOBALS["totaltax"] = 0;
$GLOBALS["monto"] = $monto;
$cliente_nombre = $row["cliente_nombre"];
$cliente_apellido = $row["cliente_apellido"];
$ciudad = $row["ciudad"];
$pais = $row["pais"];
$address1 = $row["address1"];
$address2 = $row["address2"];
$telefono1 = $row["telefono1"];
$telefono2 = $row["telefono2"];
$fecha_pago = $row["fecha_pago"];
$codigo_postal = $row["codigo_postal"];
$nota = $row["nota"];
$anulado = $row["anulado"];
$GLOBALS["nota"] = $nota;

if(trim($fecha_pago) != "") $fecha = $fecha_pago; // Pongo la fecha del pago de la factura como la fecha de factura

$pdf->Ln(10);

$pdf->SetFont('ARIAL','',18);
$pdf->Cell(120,10,'',0,0,'R');
$pdf->Cell(80,10,'INVOICE',0,0,'C');

$pdf->Ln(10);
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(120,6);
$pdf->Cell(40,6,'Invoice Date: ',1,0,'R');
$pdf->SetFont('ARIAL','',10);
$pdf->Cell(40,6,$fecha,1,0,'L');

$pdf->Ln();
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(120,6);
$pdf->Cell(40,6,'Invoice Number: ',1,0,'R');
$pdf->SetFont('ARIAL','',10);
$pdf->Cell(40,6,$recibo,1,0,'L');

$pdf->Ln();
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(120,6);
$pdf->Cell(40,6,'CTTO Nro.: ',1,0,'R');
$pdf->SetFont('ARIAL','',10);
$pdf->Cell(40,6,'Data',1,0,'L');

$pdf->Ln(15);

// Datos compaρνa
$pdf->SetFont('ARIAL','B',16);
$pdf->Cell(5,6);
$pdf->Cell(95,6,utf8_decode($cia),0,0,'L');
$pdf->Ln();
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(5,5);
$pdf->Cell(95,5,utf8_decode($address),0,0,'L');
$pdf->SetFont('ARIAL','');
$pdf->Cell(100,5,"Email: " . utf8_decode($email),0,0,'R');
$pdf->Ln();
$pdf->Cell(5,5);
$pdf->SetFont('ARIAL','B');
$pdf->Cell(100,5,"Phones: " . utf8_decode($phone1) . ' ' . utf8_decode($phone1),0,0,'L');
$pdf->SetFont('ARIAL','');
$pdf->Cell(95,5,"Web Page: " . utf8_decode($webpage),0,0,'R');
$pdf->Ln(6);

$pdf->SetFont('ARIAL','BI');
$pdf->Cell(5,5);
$pdf->Cell(195,5,"Customer",1,0,'L');
$pdf->Ln();
$pdf->SetFont('ARIAL','');
$pdf->Cell(5,5);
$pdf->Cell(195,8,utf8_decode($cliente_nombre) . " " . utf8_decode($cliente_apellido),1,0,'L');
$pdf->Ln();

$pdf->SetFont('ARIAL','BI');
$pdf->Cell(5,5);
$pdf->Cell(95,5,"Address 1",1,0,'L');
$pdf->Cell(100,5,"Address 2",1,0,'L');
$pdf->Ln();
$pdf->SetFont('ARIAL','');
$pdf->Cell(5,5);
$pdf->Cell(95,8,utf8_decode($address1),1,0,'L');
$pdf->Cell(100,8,utf8_decode($address2),1,0,'L');
$pdf->Ln();

$pdf->SetFont('ARIAL','BI');
$pdf->Cell(5,5);
$pdf->Cell(45,5,"Country",1,0,'L');
$pdf->Cell(50,5,"City",1,0,'L');
$pdf->Cell(30,5,"Postal Code",1,0,'L');
$pdf->Cell(70,5,"Phones",1,0,'L');
$pdf->Ln();
$pdf->SetFont('ARIAL','');
$pdf->Cell(5,5);
$pdf->Cell(45,8,utf8_decode($pais),1,0,'L');
$pdf->Cell(50,8,utf8_decode($ciudad),1,0,'L');
$pdf->Cell(30,8,utf8_decode($codigo_postal),1,0,'L');
$pdf->Cell(70,8,utf8_decode($telefono1) .  " " . utf8_decode($telefono2),1,0,'L');
$pdf->Ln();
$pdf->Ln(5);

$pdf->SetFont('ARIAL','BI');
$pdf->Cell(5,5);
$pdf->Cell(25,5,"Quantity",1,0,'C');
$pdf->Cell(100,5,"Description",1,0,'C');
$pdf->Cell(35,5,"Unit Price",1,0,'R');
$pdf->Cell(35,5,"Total",1,0,'R');
$pdf->Ln();

$sql = "SELECT 
			a.articulo, a.cantidad, a.precio, a.total 
		FROM 
			pago_detalle AS a WHERE a.pago = '$recibo';";
$rs = mysqli_query($link, $sql);
$pdf->SetFont('ARIAL','',10);
while($row = mysqli_fetch_array($rs)) {
	$pdf->Cell(5,5);
	$pdf->Cell(25,6,$row["cantidad"],0,0,'C');
	$pdf->Cell(100,6,$row["articulo"],0,0,'L');
	$pdf->Cell(35,6,number_format($row["precio"], 2, '.', ','),0,0,'R');
	$pdf->Cell(35,6,number_format($row["total"], 2, '.', ','),0,0,'R');
	$pdf->Ln();
}

if(trim($fecha_pago) != "") {
	$pdf->Ln(15);
	$pdf->SetFont('ARIAL','',16);
	$pdf->Cell(30,5);
	$pdf->Cell(100,8,"*** PAID ***",0,0,'C');
}

if(trim($anulado) == "Y") {
	$pdf->Ln(15);
	$pdf->SetFont('ARIAL','B',16);
	$pdf->Cell(30,5);
	$pdf->Cell(100,8,"*** VOID ***",0,0,'C');
}

$pdf->Line(7, 120, 7, 256);
$pdf->Line(32, 120, 32, 256);
$pdf->Line(132, 120, 132, 256);
$pdf->Line(167, 120, 167, 256);
$pdf->Line(202, 120, 202, 256);

$pdf->Line(7, 256, 202, 256);

$pdf->SetFont('ARIAL','',10);

	
$pdf->Output();
?>