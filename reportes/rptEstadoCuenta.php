<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect.php");


$codcli = isset($_REQUEST["cliente"])?$_REQUEST["cliente"]:"0";

/*$sql = "SELECT * FROM entradas_salidas where id = '$id';";
$rs = mysqli_query($link, $sql);
if(!$row = mysqli_fetch_array($rs)) die("La Factura no tiene Detalle");*/

$sql = "SELECT nombre FROM cliente where id = '$codcli'"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["cliente"] = $row["nombre"];

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
					b.campo_descripcion AS banco, 
					a.titular AS titular, 
					a.tipo, 
					a.numero 
				FROM 
					compania_cuenta AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.banco AND b.tabla = 'BANCO' 
				WHERE 
					a.compania = '$cia' AND a.mostrar = 'S' AND a.activo = 'S';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$GLOBALS["cta_cia"] =  $row["numero"];


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
		$direccion = $row["direccion"]; 
		$cia =  $row["nombre"];
		$logo =  $row["logo"];
		$ci_rif = $row["ci_rif"];

		
		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 50);
		}
		
		$this->Ln(15);
		
		$this->SetFont('Arial','',12);
		$this->Cell(200, 6, "REPORTE DE PUNTOS" ,0,0,'C');
		


		$this->Ln(8);
		
		$this->SetFont('Arial','',8);
		$this->Cell(10, 5);
		$this->Cell(50, 5, utf8_decode($cia),0,0,'L');
		$this->SetFont('Arial','',8);

		$this->Ln();
		$this->Cell(10, 5);
		$this->SetFont('Arial','B',8);
		$this->Cell(100, 5, "R.I.F: $ci_rif", 0, 0, "L");
		$this->SetFont('Arial','',8);
		$this->Cell(50, 5, "CIUDAD: $ciudad",0,0,'R');
		$this->Cell(40, 5, "FECHA: " . date("d/m/Y H:i:s"),0,0,'R');



		require("../include/desconnect.php");

		$this->Ln();
		$this->SetFont('Arial','B',8);
		$this->Cell(10, 4);
		$this->Cell(180, 4,"CLIENTE: " . $GLOBALS["cliente"],'0',0,'C');

		$this->SetFont('Arial','',8);


		$this->Ln();
		$this->Cell(10, 6);
		$this->Cell(15, 6, "FECHA.", 1, 0, 'L');
		$this->Cell(10, 6, "TIPO", 1, 0, 'C');
		$this->Cell(15, 6, "DOC", 1, 0, 'L');
		$this->Cell(50, 6, "REFERENCIA", 1, 0, 'L');
		$this->Cell(70, 6, "NOTA", 1, 0, 'L');
		$this->Cell(15, 6, "PUNTOS", 1, 0, 'R');
		$this->Cell(15, 6, "SALDO", 1, 0, 'R');
		$this->Ln(6);
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
	
	function EndReport($items)
	{
		//$this->AddPage();
		//require("../connect.php");
		$this->SetFont('Arial', 'BI', 8);
		$this->Ln();
		$this->Cell(200, 6, "TOTAL ITEMS: "  . $items, 0, 0, 'R');
		$this->SetFont('Arial', '', 8);
		//require("../desconnect.php");
	}
}

// Creaciσn del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);


$sql = "SELECT 
			DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
			a.tipo, a.nro_documento, CONCAT(a.referencia, ' ', b.principio_activo) AS referencia, a.puntos, a.saldo, 
			a.nota  
		FROM 
			puntos AS a 
			LEFT OUTER JOIN articulo AS b ON b.codigo_ims = a.referencia  
		WHERE a.cliente = $codcli ORDER BY a.id DESC;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(10, 4);
	$pdf->Cell(15, 4, $row["fecha"], 0, 0, 'L');
	$pdf->Cell(10, 4, $row["tipo"], 0, 0, 'L');
	$pdf->Cell(15, 4, $row["nro_documento"], 0, 0, 'L');
	$pdf->Cell(50, 4, substr($row["referencia"], 0, 28), 0, 0, 'L');
	$pdf->Cell(70, 4, substr($row["nota"], 0, 45), 0, 0, 'L');

	$pdf->Cell(15, 4, intval($row["puntos"]), 0, 0, 'R');
	$pdf->Cell(15, 4, number_format($row["saldo"], 0, ",","."), 0, 0, 'R');

	$pdf->Ln();

	$items++;
}

$pdf->EndReport($items);

	
require("../include/desconnect.php");

$pdf->Output();
?>