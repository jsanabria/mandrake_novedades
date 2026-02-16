<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect.php");

$xtitulo = isset($_REQUEST["xtitulo"])?$_REQUEST["xtitulo"]:"";
$xfecha = isset($_REQUEST["xfecha"])?$_REQUEST["xfecha"]:"0";
$yfecha = isset($_REQUEST["yfecha"])?$_REQUEST["yfecha"]:"0";
$GLOBALS["username"] = isset($_REQUEST["username"])?$_REQUEST["username"]:"";
$GLOBALS["cliente"] = isset($_REQUEST["xcliente"])?$_REQUEST["xcliente"]:"0";
$GLOBALS["asesor"] = isset($_REQUEST["xasesor"])?$_REQUEST["xasesor"]:"0";

$xfecha = str_replace("/", "-", $xfecha);
$yfecha = str_replace("/", "-", $yfecha);

$xfecha = substr($xfecha, 0, 10);
$yfecha = substr($yfecha, 0, 10);

$xF = explode("-", $xfecha);
$xfecha = $xF[2] . "-" . $xF[1] . "-" . $xF[0];

$xF = explode("-", $yfecha);
$yfecha = $xF[2] . "-" . $xF[1] . "-" . $xF[0];

$GLOBALS["titulo"] = $xtitulo;
//die("$xcliente - $xfecha - $yfecha"); 


class PDF extends FPDF
{
	// Cabecera de p?gina
	function Header()
	{
		// Consulto datos de la compa??a 
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
		$ci_rif = $row["ci_rif"];
		$ciudad = $row["ciudad"];
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

		$this->Ln(15);
		$this->Cell(10, 5);
		$this->Cell(100, 5, "RIF $ci_rif", 0, 0, "L");
		$this->Ln();
		$this->SetFont('Arial','',7);
		$this->Cell(10, 5);
		$this->Cell(100, 5, utf8_decode($cia),0,0,'L');

		$this->Ln(5);
		
		$this->SetFont('Arial','B',14);
		$this->Cell(200, 6, utf8_decode($GLOBALS["titulo"]),0,0,'C');
		$this->SetFont('Arial','',12);
		$this->Ln();
		$this->Cell(200, 6, "Desde " . $_REQUEST["xfecha"] . " Hasta " . $_REQUEST["yfecha"],0,0,'C');
		$this->SetFont('Arial','',8);		


		$this->Ln(8);
		

		require("../include/desconnect.php");
		$this->Ln(6);

		$this->Cell(10, 6);
		$this->Cell(15, 6, "FECHA", 1, 0, 'L');
		$this->Cell(15, 6, "DOC.", 1, 0, 'L');
		$this->Cell(65, 6, "CLIENTE", 1, 0, 'L');
		$this->Cell(40, 6, "VENDEDOR", 1, 0, 'L');
		$this->Cell(15, 6, "USUARIO", 1, 0, 'L');
		$this->Cell(10, 6, "MON", 1, 0, 'C');
		// $this->Cell(20, 6, "COSTO", 1, 0, 'R');
		$this->Cell(20, 6, "", 1, 0, 'R');
		$this->Cell(20, 6, "PRECIO", 1, 0, 'R');
		//$this->Cell(20, 6, "UTILIDAD", 1, 0, 'R');
		//$this->Cell(20, 6, "UTILIDAD", 1, 0, 'R');
		$this->Ln(6);
	}
	
	// Pie de p?gina
	function Footer()
	{
		// Posici?n: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// N?mero de p?gina
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($xTotalCosto, $xTotalPrecio, $xTotalUtilidad, $xitems, $xxfecha, $xyfecha)
	{
		//$this->AddPage();
		require("../include/connect.php");
		$this->SetFont('Arial','B',8);

		if ($this->GetY() > 230) $this->AddPage();

		if(intval($GLOBALS["cliente"]) == 0) $xcliente = " ";
		else $xcliente = " AND a.cliente = '$xcliente' ";

		if(trim($GLOBALS["asesor"]) == "") $xasesor = " ";
		else $xasesor = " AND a.asesor = '" . $GLOBALS["asesor"] . "' ";

		if(trim($GLOBALS["username"]) == "") $xuser = " ";
		else $xuser = " AND a.username = '" . $GLOBALS["username"] . "' ";

		$sql = "SELECT 
					d.nombre AS vendedor, IFNULL(SUM(if(b.precio = 0, 0, b.costo)), 0) AS costo, 
					-- SUM(b.precio) AS precio  
					SUM(IFNULL(b.precio, 0)-(IFNULL(b.precio, 0)*(IFNULL(a.descuento, 0)/100))) AS precio 
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
					LEFT OUTER JOIN cliente AS c ON c.id = a.cliente 
					LEFT OUTER JOIN asesor AS d ON d.ci_rif = a.asesor 
				WHERE 
					a.tipo_documento = 'TDCNET' AND IFNULL(a.pago_premio, 'N') = 'N'  
					AND a.fecha BETWEEN '$xxfecha 00:00:00' AND '$xyfecha 23:59:59' 
					AND a.estatus = 'PROCESADO' $xasesor $xcliente  $xuser 
				GROUP BY d.nombre;"; 
		$rs = mysqli_query($link, $sql) or die(mysqli_error());
		$utilidad2 = 0;
		while($row = mysqli_fetch_array($rs))
		{
			$this->Ln();
			$this->Cell(170, 4, $row["vendedor"], 0, 0, 'R');
			//$this->Cell(20, 4, number_format($row["costo"], 2, ".", ","), 0, 0, 'R');
			$this->Cell(20, 4, "", 0, 0, 'R');
			$this->Cell(20, 4, number_format($row["precio"], 2, ".", ","), 0, 0, 'R');
			// $this->Cell(20, 4, number_format(doubleval($row["precio"])-doubleval($row["costo"]), 2, ".", ","), 0, 0, 'R');
			//$this->Cell(20, 4, number_format((doubleval($row["precio"])-doubleval($row["costo"]))/doubleval($row["precio"]), 2, ".", ","), 0, 0, 'R');
			//$utilidad2 += (doubleval($row["precio"])-doubleval($row["costo"]))/doubleval($row["precio"]);
		}


		$this->Ln();
		$this->Cell(170, 5, "TOTAL: ", 0, 0, 'R');
		// $this->Cell(20, 5, number_format($xTotalCosto, 2, ".", ","), 1, 0, 'R');
		$this->Cell(20, 5, "", 1, 0, 'R');
		$this->Cell(20, 5, number_format($xTotalPrecio, 2, ".", ","), 1, 0, 'R');
		// $this->Cell(20, 5, number_format($xTotalUtilidad, 2, ".", ","), 1, 0, 'R');
		//$this->Cell(20, 5, number_format($utilidad2, 2, ".", ","), 1, 0, 'R');

		require("../include/desconnect.php");
	}
}

// Creaci?n del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

if(intval($GLOBALS["cliente"]) == 0) $xcliente = " ";
else $xcliente = " AND a.cliente = '$xcliente' ";

if(trim($GLOBALS["asesor"]) == "") $xasesor = " ";
else $xasesor = " AND a.asesor = '" . $GLOBALS["asesor"] . "' ";

if(trim($GLOBALS["username"]) == "") $xuser = " ";
else $xuser = " AND a.username = '" . $GLOBALS["username"] . "' ";

$sql = "SELECT 
			a.username AS usuario, date_format(a.fecha, '%d/%m/%Y') AS fecha, c.nombre AS cliente, 
			a.nro_documento, a.moneda, 
			a.monto_total, IFNULL(SUM(if(b.precio = 0, 0, b.costo)), 0) AS costo, 
			-- SUM(b.precio) AS precio, 
			SUM(IFNULL(b.precio, 0)-(IFNULL(b.precio, 0)*(IFNULL(a.descuento, 0)/100))) AS precio, 
			d.nombre AS vendedor  
		FROM 
			salidas AS a 
			JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
			LEFT OUTER JOIN cliente AS c ON c.id = a.cliente 
			LEFT OUTER JOIN asesor AS d ON d.ci_rif = a.asesor 
		WHERE 
			a.tipo_documento = 'TDCNET' AND IFNULL(a.pago_premio, 'N') = 'N' 
			AND a.fecha BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' 
			AND a.estatus = 'PROCESADO' $xasesor $xcliente  $xuser 
		GROUP BY a.username, a.fecha, c.nombre, a.nro_documento, a.moneda, a.monto_total, d.nombre, a.descuento 
		ORDER BY a.fecha, a.nro_documento ASC;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$TotalCosto = 0;
$TotalPrecio = 0;
$TotalUtilidad = 0;
while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 8);

	$pdf->Cell(10, 4);
	$pdf->Cell(15, 4, $row["fecha"], 0, 0, 'L');
	$pdf->Cell(15, 4, $row["nro_documento"], 0, 0, 'L');
	$pdf->Cell(65, 4, utf8_decode(substr($row["cliente"], 0, 30)), 0, 0, 'L');
	$pdf->Cell(40, 4, utf8_decode(substr($row["vendedor"], 0, 10)), 0, 0, 'L');
	$pdf->Cell(15, 4, utf8_decode(substr($row["usuario"], 0, 10)), 0, 0, 'L');
	$pdf->Cell(10, 4, $row["moneda"], 0, 0, 'C');
	// $pdf->Cell(20, 4, number_format($row["costo"], 2, ".", ","), 0, 0, 'R');
	$pdf->Cell(20, 4, "", 0, 0, 'R');
	$pdf->Cell(20, 4, number_format($row["precio"], 2, ".", ","), 0, 0, 'R');
	// $pdf->Cell(20, 4, number_format(doubleval($row["precio"])-doubleval($row["costo"]), 2, ".", ","), 0, 0, 'R');
	// $pdf->Cell(20, 4, number_format((doubleval($row["precio"])-doubleval($row["costo"]))/(doubleval($row["precio"])<=0 ? 1: doubleval($row["precio"])) * 100, 2, ".", ","), 0, 0, 'R');
	$pdf->Ln();
	$items++;
	$TotalCosto += $row["costo"];
	$TotalPrecio += $row["precio"];
	$TotalUtilidad += doubleval($row["precio"])-doubleval($row["costo"]);
}

$pdf->EndReport($TotalCosto, $TotalPrecio, $TotalUtilidad, $items, $xfecha, $yfecha);

	
require("../include/desconnect.php");

$pdf->Output();
?>