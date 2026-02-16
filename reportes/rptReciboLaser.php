<?php
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('rcs/fpdf.php');
require("../include/connect.php");

$recibo = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";
$GLOBALS["titulo_reporte"] = "RECIBO ABONO SANERA";

class PDF extends FPDF
{
	// Cabecera de pαgina
	function Header()
	{
	    $this->Image("../carpetacarga/" . $GLOBALS["logo"], 10, 10, 50);
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
$ciudad = $row["ciudad"];
$email = $row["email1"];
$address = $row["direccion"];
$cia =  $row["nombre"];
$GLOBALS["logo"] =  $row["logo"];
$ci_rif = $row["ci_rif"];
$phone1 = $row["telefono1"];
$webpage = "";


// Creaciσn del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);



$sql = "SELECT 
			a.id AS recibo, date_format(a.fecha, '%d-%m-%Y') AS fecha, 
			a.monto_usd AS monto, 
			c.nombre AS cliente_nombre, 
			'' AS pais, c.ciudad, c.web AS codigo_postal, 
			c.direccion AS address1, '' AS address2, 
			c.telefono1, c.telefono2, 
			date_format(a.fecha, '%d/%m/%Y') AS fecha_pago, 
			a.nota, '' AS anulado, '' AS item, 
			a.metodo_pago, a.referencia AS nro, a.monto_moneda, a.moneda, a.tasa_moneda, a.monto_bs, 
			a.tasa_usd, a.monto_usd, a.saldo, c.id AS codigo_sanera   
		FROM 
			recarga a 
			JOIN cliente c ON c.id = a.cliente  
		WHERE a.id = '$recibo';"; 

$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);

$amount = $row["moneda"] . " " . number_format($row["monto_moneda"], 2, ".", ",");

$pagos_con = "\nTipo de Pago: " . $row["metodo_pago"] . " - Ref. # " . $row["nro"];
$pagos_con .= "\nMonto: " . $row["moneda"] . " " . number_format($row["monto_moneda"], 2, ".", ",");
$pagos_con .= "\nTasa: " . number_format($row["tasa_moneda"], 2, ".", ",");
$pagos_con .= "\nMonto Bs.: " . number_format($row["monto_bs"], 2, ".", ",");
$pagos_con .= "\nTasa USD: " . number_format($row["tasa_usd"], 2, ".", ",");
$pagos_con .= "\nMonto USD: " . number_format($row["monto_usd"], 2, ".", ",");
$saldo = "Saldo: *** USD " . number_format($row["saldo"], 2, ".", ",") . " ***";

$fecha = $row["fecha"];

$monto = $row["monto"];
$GLOBALS["submonto"] = $monto;
$GLOBALS["tax"] = 0;
$GLOBALS["totaltax"] = 0;
$GLOBALS["monto"] = $monto;
$cliente_nombre = $row["cliente_nombre"];
$ciudad = $row["ciudad"];
$pais = $row["pais"];
$address1 = $row["address1"];
$address2 = $row["address2"];
$telefono1 = $row["telefono1"];
$telefono2 = $row["telefono2"];
$fecha_pago = $row["fecha_pago"];
$codigo_postal = $row["codigo_postal"];
$nota = $row["nota"];
$codigo_sanera = $row["codigo_sanera"];
$GLOBALS["nota"] = $nota;

$pdf->Ln(5);

$pdf->SetFont('ARIAL','',18);
$pdf->Cell(120,10,'',0,0,'R');
$pdf->Cell(80,10,'RECIBO',0,0,'C');

$pdf->Ln(10);
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(120,6);
$pdf->Cell(40,6,'Fecha: ',1,0,'R');
$pdf->SetFont('ARIAL','',10);
$pdf->Cell(40,6,$fecha,1,0,'L');

$pdf->Ln();
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(120,6);
$pdf->Cell(40,6,'Nro. Recibo: ',1,0,'R');
$pdf->SetFont('ARIAL','',10);
$pdf->Cell(40,6,$recibo,1,0,'L');

$pdf->Ln();
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(120,6);
$pdf->Cell(40,6,'CODIGO SANERA: ',1,0,'R');
$pdf->SetFont('ARIAL','',10);
$pdf->Cell(40,6,"$codigo_sanera",1,0,'L');

$pdf->Ln();

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
$pdf->Cell(100,5,"Telιfonos: " . utf8_decode($phone1) . ' ' . utf8_decode($phone1),0,0,'L');
$pdf->SetFont('ARIAL','');
$pdf->Cell(95,5,"Web Page: " . utf8_decode($webpage),0,0,'R');
$pdf->Ln(6);

$pdf->SetFont('ARIAL','BI');
$pdf->Cell(5,5);
$pdf->Cell(195,5,"Customer",1,0,'L');
$pdf->Ln();
$pdf->SetFont('ARIAL','');
$pdf->Cell(5,5);
$pdf->Cell(195,8,utf8_decode($cliente_nombre),1,0,'L');
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

$pdf->Cell(5,5);
$pdf->Cell(25,6,"1",0,0,'C');
$pdf->Cell(100,6,$row["item"],0,0,'L');
$pdf->Cell(35,6,number_format($row["monto"], 2, '.', ','),0,0,'R');
$pdf->Cell(35,6,number_format($row["monto"], 2, '.', ','),0,0,'R');
$pdf->Ln();


$pdf->SetFont('ARIAL','',10);
$pdf->Ln();
$pdf->Cell(30,5);
$pdf->MultiCell(100,5,"$pagos_con",0,'L');
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(30,5);
$pdf->MultiCell(100,5,"$saldo",0,'L');

$pdf->SetFont('ARIAL','',10);
$pdf->Line(7, 108, 7, 256);
$pdf->Line(32, 108, 32, 256);
$pdf->Line(132, 108, 132, 256);
$pdf->Line(167, 108, 167, 256);
$pdf->Line(202, 108, 202, 256);

$pdf->Line(7, 256, 202, 256);

$pdf->SetFont('ARIAL','',10);

	
$pdf->Output();
?>