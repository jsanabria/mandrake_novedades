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
	// Cabecera de página
	function Header()
	{
		// Consulto datos de la compañía 
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
		

		require("../include/desconnect.php");
	}
	
	// Pie de página
	function Footer()
	{
		// Posición: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Número de página
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($x_arrMP, $x_RSM, $x_grandTotalBs, $x_grandTotalUsd, $x_i, $xnotas, $fecha)
	{
		$this->Ln();

		$this->AddPage();
		$this->SetFont('Arial','',8);


		require("../include/connect.php");

		///////////////////////////// VENTAS /////////////////////////////
		$sql = "SELECT 
					COUNT(c.total) AS cantidad, SUM(c.total*tasa_dia) AS monto_bs, SUM(c.total) AS monto_usd 
				FROM 
					salidas AS c 
				WHERE 
					c.fecha BETWEEN '$fecha 00:00:00' AND '$fecha 23:59:59' AND c.estatus = 'PROCESADO';";
		$rs = mysqli_query($link, $sql) or die(mysqli_error());

		$this->SetFont('Arial', 'B', 8);
		$this->Ln();
		$this->Cell(70, 5, "", 0, 0, 'R');
		$this->Cell(110, 5, "VENTAS", 1, 0, 'L');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$this->Ln();
		$this->Cell(70, 5, "", 0, 0, 'R');
		$this->Cell(40, 5, "", 1, 0, 'R');
		$this->Cell(20, 5, "Ventas", 1, 0, 'L');
		$this->Cell(25, 5, "Bs. Total", 1, 0, 'R');
		$this->Cell(25, 5, "USD Total", 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$this->Ln();
		$totBs = 0;
		$totUSD = 0;
		$GtotBs = 0;
		$GtotUSD = 0;
		if($row = mysqli_fetch_array($rs)) {
			$this->Cell(70, 5, "", 0, 0, 'R');
			$this->Cell(40, 5, "", 0, 0, 'R');
			$this->Cell(20, 5, $row["cantidad"], 1, 0, 'L');
			$this->Cell(25, 5, number_format($row["monto_bs"], 2, ".", ","), 1, 0, 'R');
			$this->Cell(25, 5, number_format($row["monto_usd"], 2, ".", ","), 1, 0, 'R');
			$this->Cell(20, 5, "", 0, 0, 'L');
			$this->Ln();
			$totBs += $row["monto_bs"];
			$totUSD += $row["monto_usd"];
		}
		$TotVentasBs = $totBs;
		$TotVentasUsd = $totUSD;
		$this->Ln();
		$this->SetFont('Arial', '', 8);

		///////////////////////////////////////////////////////////////////////////////////////

		$sql = "SELECT 
					aa.metodo_pago, 
					sum(aa.cantidad) AS cantidad, 
					SUM(IF(aa.metodo_pago='PF' OR aa.metodo_pago='PC', (-1), 1)*aa.monto_bs) AS monto_bs, 
					SUM(IF(aa.metodo_pago='PF' OR aa.metodo_pago='PC', (-1), 1)*aa.monto_usd) AS monto_usd
				FROM (
				SELECT 
					b.metodo_pago, COUNT(b.metodo_pago) AS cantidad, 
					SUM(b.monto_bs) AS monto_bs, SUM(b.monto_usd) AS monto_usd 
				FROM 
					cobros_cliente AS a 
					JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
					LEFT OUTER JOIN salidas AS c ON c.id = a.id_documento 
					LEFT OUTER JOIN cliente AS d ON d.id = a.cliente 
					LEFT OUTER JOIN usuario AS e ON e.username = a.username 
				WHERE b.metodo_pago <> 'RC' AND 
					a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha 23:59:59' AND c.estatus = 'PROCESADO' 
				GROUP BY b.metodo_pago
				/*UNION ALL						
				SELECT 
					metodo_pago, COUNT(id) AS cantidad, SUM(monto_bs) AS monto_bs, SUM(monto_usd) AS monto_usd 
				FROM recarga WHERE fecha BETWEEN '$fecha 00:00:00' AND '$fecha 23:59:59' AND monto_usd > 0 
				GROUP BY metodo_pago*/) AS aa GROUP BY aa.metodo_pago;"; 
				//  AND b.metodo_pago <> 'RC'
		$rs = mysqli_query($link, $sql) or die(mysqli_error());

		$this->Ln();
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(70, 5, "", 0, 0, 'R');
		$this->Cell(110, 5, "INGRESOS", 1, 0, 'L');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$this->Ln();
		$this->Cell(70, 5, "", 0, 0, 'R');
		$this->Cell(40, 5, "Tipo de Pago", 1, 0, 'R');
		$this->Cell(20, 5, "Nro. Pagos", 1, 0, 'L');
		$this->Cell(25, 5, "Bs. Total", 1, 0, 'R');
		$this->Cell(25, 5, "USD Total", 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$this->Ln();
		$this->SetFont('Arial', '', 8);
		$totBs = 0;
		$totUSD = 0;
		$cnt = 0;
		while($row = mysqli_fetch_array($rs)) {
			if($row["monto_usd"] < 0) {
				$this->SetFont('Arial', 'BI', 8);
			} 
			else {
				$this->SetFont('Arial', '', 8);
			}
			$sql = "SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = '" . $row["metodo_pago"] . "';";
			$rs2 = mysqli_query($link, $sql) or die(mysqli_error());
			$row2 = mysqli_fetch_array($rs2);

			$this->Cell(70, 5, "", 0, 0, 'R');
			$this->Cell(40, 5, $row2["valor2"], 0, 0, 'R');
			$this->Cell(20, 5, $row["cantidad"], 0, 0, 'L');
			$this->Cell(25, 5, number_format(abs($row["monto_bs"]), 2, ".", ","), 0, 0, 'R');
			$this->Cell(25, 5, number_format(abs($row["monto_usd"]), 2, ".", ","), 0, 0, 'R');
			$this->Cell(20, 5, "", 0, 0, 'L');
			$this->Ln();
			$cnt += $row["cantidad"];

			if($row["monto_usd"] < 0) {
				$totBs += 0;
				$totUSD += 0;
			} 
			else {
				$totBs += $row["monto_bs"];
				$totUSD += $row["monto_usd"];
			}
		}

		$this->SetFont('Arial', 'B', 8);
		$this->Cell(110, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, $cnt, 1, 0, 'L');
		$this->Cell(25, 5, number_format($totBs, 2, ".", ","), 1, 0, 'R');
		$this->Cell(25, 5, number_format($totUSD, 2, ".", ","), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$TotIngresosBs = $totBs;
		$TotIngresosUsd = $totUSD;
		$this->Ln();
		$this->SetFont('Arial', '', 8);


/////////////////
		$sql = "SELECT 
					b.metodo_pago, COUNT(b.metodo_pago) AS cantidad, 
					SUM(b.monto_bs) AS monto_bs, SUM(b.monto_usd) AS monto_usd 
				FROM 
					cobros_cliente AS a 
					JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
					LEFT OUTER JOIN salidas AS c ON c.id = a.id_documento 
					LEFT OUTER JOIN cliente AS d ON d.id = a.cliente 
					LEFT OUTER JOIN usuario AS e ON e.username = a.username 
				WHERE 
					a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha 23:59:59' AND c.estatus = 'PROCESADO' AND b.metodo_pago = 'RC' 
				GROUP BY b.metodo_pago;";
		$rs = mysqli_query($link, $sql) or die(mysqli_error());

		$this->Ln();
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(70, 5, "", 0, 0, 'R');
		$this->Cell(110, 5, "EGRESOS", 1, 0, 'L');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$this->Ln();
		$this->Cell(70, 5, "", 0, 0, 'R');
		$this->Cell(40, 5, "Tipo de Pago", 1, 0, 'R');
		$this->Cell(20, 5, "Nro. Egresos", 1, 0, 'L');
		$this->Cell(25, 5, "Bs. Total", 1, 0, 'R');
		$this->Cell(25, 5, "USD Total", 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$this->Ln();
		$this->SetFont('Arial', '', 8);
		$totBs = 0;
		$totUSD = 0;
		$cnt = 0;
		while($row = mysqli_fetch_array($rs)) {
			$sql = "SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = '" . $row["metodo_pago"] . "';";
			$rs2 = mysqli_query($link, $sql) or die(mysqli_error());
			$row2 = mysqli_fetch_array($rs2);

			$this->Cell(70, 5, "", 0, 0, 'R');
			$this->Cell(40, 5, $row2["valor2"], 0, 0, 'R');
			$this->Cell(20, 5, $row["cantidad"], 0, 0, 'L');
			$this->Cell(25, 5, number_format($row["monto_bs"], 2, ".", ","), 0, 0, 'R');
			$this->Cell(25, 5, number_format($row["monto_usd"], 2, ".", ","), 0, 0, 'R');
			$this->Cell(20, 5, "", 0, 0, 'L');
			$this->Ln();
			if($row["monto_usd"] < 0) {
				$totBs += 0;
				$totUSD += 0;
			} 
			else {
				$totBs += $row["monto_bs"];
				$totUSD += $row["monto_usd"];
			}
			$cnt += $row["cantidad"];
		}
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(110, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, $cnt, 1, 0, 'L');
		$this->Cell(25, 5, number_format($totBs, 2, ".", ","), 1, 0, 'R');
		$this->Cell(25, 5, number_format($totUSD, 2, ".", ","), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$TotEgresosBs = $totBs;
		$TotEgresosUsd = $totUSD;
		$this->Ln();
		$this->SetFont('Arial', '', 8);


///////////////////////////////////
		$sql = "SELECT 
					metodo_pago, COUNT(id) AS cantidad, SUM(monto_bs) AS monto_bs, SUM(monto_usd) AS monto_usd, 
					SUM(IF(metodo_pago='PF' OR metodo_pago='PC', (-1), 1)*monto_bs) AS monto_bs, 
					SUM(IF(metodo_pago='PF' OR metodo_pago='PC', (-1), 1)*monto_usd) AS monto_usd
				FROM recarga WHERE fecha BETWEEN '$fecha 00:00:00' AND '$fecha 23:59:59' AND (monto_usd > 0 OR reverso = 'S') 
				GROUP BY metodo_pago"; 
		$rs = mysqli_query($link, $sql) or die(mysqli_error());

		$this->Ln();
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(70, 5, "", 0, 0, 'R');
		$this->Cell(110, 5, "ABONOS", 1, 0, 'L');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$this->Ln();
		$this->Cell(70, 5, "", 0, 0, 'R');
		$this->Cell(40, 5, "Tipo de Pago", 1, 0, 'R');
		$this->Cell(20, 5, "Nro. Abonos", 1, 0, 'L');
		$this->Cell(25, 5, "Bs. Total", 1, 0, 'R');
		$this->Cell(25, 5, "USD Total", 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$this->Ln();
		$this->SetFont('Arial', '', 8);
		$totBs = 0;
		$totUSD = 0;
		$cnt = 0;
		while($row = mysqli_fetch_array($rs)) {
			if($row["monto_usd"] < 0) {
				$this->SetFont('Arial', 'BI', 8);
			} 
			else {
				$this->SetFont('Arial', '', 8);
			}
			$sql = "SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = '" . $row["metodo_pago"] . "';";
			$rs2 = mysqli_query($link, $sql) or die(mysqli_error());
			$row2 = mysqli_fetch_array($rs2);

			$this->Cell(70, 5, "", 0, 0, 'R');
			$this->Cell(40, 5, $row2["valor2"] . ($row["metodo_pago"] == "RC" ? " en Notas de Entrega" : ""), 0, 0, 'R');
			$this->Cell(20, 5, $row["cantidad"], 0, 0, 'L');
			$this->Cell(25, 5, number_format(abs($row["monto_bs"]), 2, ".", ","), 0, 0, 'R');
			$this->Cell(25, 5, number_format(abs($row["monto_usd"]), 2, ".", ","), 0, 0, 'R');
			$this->Cell(20, 5, "", 0, 0, 'L');
			$this->Ln();
			if($row["monto_usd"] < 0) {
				$totBs += 0;
				$totUSD += 0;
			} 
			else {
				$totBs += $row["monto_bs"];
				$totUSD += $row["monto_usd"];
			}
			$cnt += $row["cantidad"];
		}
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(110, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, $cnt, 1, 0, 'L');
		$this->Cell(25, 5, number_format($totBs, 2, ".", ","), 1, 0, 'R');
		$this->Cell(25, 5, number_format($totUSD, 2, ".", ","), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$TotAbonosBs = $totBs;
		$TotAbonosUsd = $totUSD;


		$this->Ln(15);
		$this->Cell(70, 5, "", 0, 0, 'R');
		$this->Cell(60, 5, "TOTAL:  Ventas - Egresos + Abonos ", 1, 0, 'R');
		$this->Cell(25, 5, number_format($TotVentasBs - $TotEgresosBs + $TotAbonosBs, 2, ".", ","), 1, 0, 'R');
		$this->Cell(25, 5, number_format($TotVentasUsd - $TotEgresosUsd + $TotAbonosUsd, 2, ".", ","), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$this->SetFont('Arial', '', 8);

		require("../include/desconnect.php");
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','BI',12);


$pdf->Cell(200, 6, "01 - VENTAS NOTAS DE ENTREGA", 0, 0, 'C');
$pdf->SetFont('Arial','BI',8);
$pdf->Ln();
$pdf->Cell(10, 6);
$pdf->Cell(15, 6, "DOC.", 1, 0, 'L');
$pdf->Cell(50, 6, "CLIENTE", 1, 0, 'L');
$pdf->Cell(88, 6, "TIPO", 1, 0, 'L');
$pdf->Cell(20, 6, "$ COBRO", 1, 0, 'R');
$pdf->Cell(20, 6, "$ MONTO", 1, 0, 'R');
$pdf->Ln(6);

$sql = "SELECT 
			DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
			d.nombre AS cliente, 
			c.nro_documento, 
			if(c.DOCUMENTO='NC', (-1)*c.total, c.total) total, 
			c.estatus, 
			IF(b.metodo_pago='RC', 'RC', 'EF') AS tipo_pago, 
			SUM(b.monto_bs) AS monto_bs, 
			SUM(b.monto_usd) AS monto_usd, 
			c.id 
		FROM 
			salidas AS c 
			LEFT OUTER JOIN cliente AS d ON d.id = c.cliente 
			LEFT OUTER JOIN cobros_cliente AS a ON a.id_documento = c.id 
			LEFT OUTER JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
		WHERE 
			a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha 23:59:59' AND c.estatus = 'PROCESADO' 
		GROUP BY 
			DATE_FORMAT(a.fecha, '%d/%m/%Y'), 
			d.nombre, 
			c.nro_documento, 
			c.DOCUMENTO, 
			c.estatus, 
			b.metodo_pago, c.id 
		ORDER BY c.id, b.metodo_pago ASC;";

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$TotalBs = 0;
$TotalUsd = 0;
$i = 0;
$Resumen = array();
$RSM = array();
$Notas = 0;
$NE = 0;
$TotalNE = 0;
$xDoc = "";
while($row = mysqli_fetch_array($rs))
{
	if($row["tipo_pago"] == "RC" or $row["tipo_pago"] == "PC" or $row["tipo_pago"] == "PF") {
		$pdf->SetFont('Arial', 'BI', 8);
	} 
	else {
		$pdf->SetFont('Arial', '', 8);
	}

	$pdf->Cell(10, 4);
	$pdf->Cell(15, 4, 	$row["nro_documento"], 0, 0, 'L');
	$pdf->Cell(50, 4, substr(utf8_decode($row["cliente"]), 0, 60), 0, 0, 'L');
	$pdf->Cell(88, 4, $row["tipo_pago"] == "RC" ? "Egreso Cuenta Cliente " . number_format($row["total"], 2, ".", ",") . " - " . number_format($row["monto_usd"], 2, ".", ",") . " = " . number_format($row["total"]-$row["monto_usd"], 2, ".", ",") : "Contado", 0, 0, 'L');
	$pdf->Cell(20, 4, number_format($row["monto_usd"], 2, ".", ","), 0, 0, 'R');
	if($xDoc != $row["nro_documento"]) {
		$pdf->Cell(20, 4, number_format($row["total"], 2, ".", ","), 0, 0, 'R');
		$TotalNE += $row["total"];
		$xDoc = $row["nro_documento"];
	}
	else 
		$pdf->Cell(20, 4, "", 0, 0, 'R');

	if($row["tipo_pago"] == "RC" or $row["tipo_pago"] == "PC" or $row["tipo_pago"] == "PF") {
		$TotalBs += 0;
		$TotalUsd += 0;
	} 
	else {
		$TotalBs += $row["monto_bs"];
		$TotalUsd += $row["monto_usd"];
	}
	$pdf->Ln();

	$i++;

	if($NE != $row["id"]) $Notas++;
	$NE = $row["id"];
}

$pdf->SetFont('Arial','BI',8);

$pdf->Cell(10, 5);
$pdf->Cell(138, 5, "Total General: $i registros de pago en $Notas Nota(s) de Entrega", 0, 0, 'R');
//$pdf->Cell(20, 5, number_format($TotalBs, 2, ".", ","), 0, 0, 'R');
$pdf->Cell(15, 5);
$pdf->Cell(20, 5, number_format($TotalUsd, 2, ".", ","), 0, 0, 'R');
$pdf->Cell(20, 5, number_format($TotalNE, 2, ".", ","), 0, 0, 'R');


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$pdf->AddPage();
$pdf->SetFont('Arial','BI',12);
$pdf->Cell(200, 6, "02 - EGRESOS PAGOS CON ABONOS", 0, 0, 'C');
$pdf->Ln();
$pdf->SetFont('Arial','BI',8);
$pdf->Cell(10, 6);
$pdf->Cell(15, 6, "DOC.", 1, 0, 'L');
$pdf->Cell(158, 6, "CLIENTE", 1, 0, 'L');
$pdf->Cell(20, 6, "$ MONTO", 1, 0, 'R');
$pdf->Ln(6);
$sql = "SELECT 
			DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
			d.nombre AS cliente, c.nro_documento, 
			if(c.DOCUMENTO='NC', (-1)*c.total, c.total) total, c.estatus, 
			(SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = b.metodo_pago) AS metodo_pago, 
			b.referencia, b.moneda, b.monto_moneda, b.tasa_moneda, 
			b.monto_bs, b.tasa_usd, b.monto_usd, c.id 
		FROM 
			cobros_cliente AS a 
			LEFT OUTER JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
			LEFT OUTER JOIN salidas AS c ON c.id = a.id_documento 
			LEFT OUTER JOIN cliente AS d ON d.id = a.cliente 
		WHERE 
			a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha 23:59:59' AND c.estatus = 'PROCESADO' AND tipo_documento = 'TDCNET' AND b.metodo_pago = 'RC';"; 
			 // AND b.metodo_pago = 'RC'

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$TotalBs = 0;
$TotalUsd = 0;
$i = 0;
$Resumen = array();
$RSM = array();
$Notas = 0;
$NE = 0;
while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 8);

	$pdf->Cell(10, 4);
	$pdf->Cell(15, 4, $row["nro_documento"], 0, 0, 'L');
	$pdf->Cell(158, 4, substr($row["cliente"], 0, 45), 0, 0, 'L');
	$pdf->Cell(20, 4, number_format($row["monto_usd"], 2, ".", ","), 0, 0, 'R');
	$pdf->Ln();
	$TotalBs += $row["monto_bs"];
	$TotalUsd += $row["monto_usd"];
	$i++;

	if($NE != $row["id"]) $Notas++;
	$NE = $row["id"];
}

$pdf->SetFont('Arial','BI',8);

$pdf->Cell(10, 5);
$pdf->Cell(158, 5, "Total General: $i registros de egresos en $Notas Nota(s) de Entrega", 0, 0, 'R');
//$pdf->Cell(20, 5, number_format($TotalBs, 2, ".", ","), 0, 0, 'R');
$pdf->Cell(15, 5);
$pdf->Cell(20, 5, number_format($TotalUsd, 2, ".", ","), 0, 0, 'R');


//////////////////////////

$pdf->AddPage();
$pdf->SetFont('Arial','BI',12);
$pdf->Cell(200, 6, "INGRESOS POR ABONOS", 0, 0, 'C');
$pdf->Ln();
$pdf->SetFont('Arial','BI',8);
$pdf->Cell(10, 6);
$pdf->Cell(28, 6, "TIPO", 1, 0, 'L');
$pdf->Cell(50, 6, "CLIENTE", 1, 0, 'L');
$pdf->Cell(15, 6, "REC.", 1, 0, 'L');
$pdf->Cell(10, 6, "MON", 1, 0, 'R');
$pdf->Cell(20, 6, "PAGO", 1, 0, 'R');
$pdf->Cell(15, 6, "TASA", 1, 0, 'R');
$pdf->Cell(20, 6, "Bs. MONTO", 1, 0, 'R');
$pdf->Cell(15, 6, "$ TASA", 1, 0, 'R');
$pdf->Cell(20, 6, "$ MONTO", 1, 0, 'R');
$pdf->Ln(6);
$sql = "SELECT 
			DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, b.nombre AS cliente, 
			(SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = a.metodo_pago) AS metodo_pago, 
			a.referencia, a.moneda, a.monto_moneda, a.tasa_moneda, 
			a.tasa_usd, a.nro_recibo,  
			IF(a.metodo_pago='PF' OR a.metodo_pago='PC', (-1), 1)*a.monto_bs AS monto_bs, 
			IF(a.metodo_pago='PF' OR a.metodo_pago='PC', (-1), 1)*a.monto_usd AS monto_usd, metodo_pago AS tipo_pago, a.reverso  
		FROM 
			recarga AS a 
			JOIN cliente AS b ON b.id = a.cliente  
		WHERE 
			a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha 23:59:59' AND (a.monto_usd > 0 OR a.reverso = 'S') ORDER BY a.metodo_pago;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$TotalBs = 0;
$TotalUsd = 0;
$i = 0;
$Resumen = array();
$RSM = array();
$Notas = 0;
$NE = 0;

$TotalGTBs = 0;
$TotalGTUsd = 0;
$xDoc = "";
$items = 0;
while($row = mysqli_fetch_array($rs))
{
	if($xDoc != $row["metodo_pago"]) {
		if($xDoc != "") {
			$pdf->SetFont('Arial','BI',8);
			$pdf->Cell(140, 4, "Total $xDoc ($items):", "0", "0", 'R');
			$pdf->Cell(28, 4, number_format($TotalBs, 2, ".", ","), "0", "0", 'R');
			$pdf->Cell(35, 4, number_format($TotalUsd, 2, ".", ","), "0", "0", 'R');
			$TotalBs = 0;
			$TotalUsd = 0;
			$items = 0;
			$pdf->SetFont('Arial', '', 8);
			$pdf->Ln();
			$pdf->Ln();
		}
	}

	if($row["tipo_pago"] == "PC" or $row["tipo_pago"] == "PF") {
		$pdf->SetFont('Arial', 'BI', 8);
	} 
	else {
		$pdf->SetFont('Arial', '', 8);
	}

	$pdf->Cell(10, 4);
	// $pdf->Cell(18, 4, $row["fecha"], 0, 0, 'L');
	$pdf->Cell(28, 4, trim(substr(str_replace("TARJETA", "", $row["metodo_pago"]), 0, 10)), 0, 0, 'L');
	$pdf->Cell(50, 4, utf8_decode(substr($row["cliente"], 0, 45)), 0, 0, 'L');
	$pdf->Cell(15, 4, str_pad($row["nro_recibo"], 7, "0", STR_PAD_LEFT), 0, 0, 'L');
	$pdf->Cell(10, 4, $row["moneda"], 0, 0, 'R');
	$pdf->Cell(20, 4, number_format($row["monto_moneda"], 2, ".", ","), 0, 0, 'R');
	$pdf->Cell(15, 4, number_format($row["tasa_moneda"], 2, ".", ","), 0, 0, 'R');
	$pdf->Cell(20, 4, number_format(abs($row["monto_bs"]), 2, ".", ","), 0, 0, 'R');
	$pdf->Cell(15, 4, number_format($row["tasa_usd"], 2, ".", ","), 0, 0, 'R');
	$pdf->Cell(20, 4, number_format(abs($row["monto_usd"]), 2, ".", ","), 0, 0, 'R');
	$pdf->Ln();
	if(($row["monto_usd"] >= 0 and $row["reverso"] == "N") or ($row["reverso"] == "S" and $row["tipo_pago"] != "PC" and $row["tipo_pago"] != "PF")) {
		$TotalBs += $row["monto_bs"];
		$TotalUsd += $row["monto_usd"];
		$TotalGTBs += $row["monto_bs"];
		$TotalGTUsd += $row["monto_usd"];
	}
	$i++;
	$items++;
	$xDoc = $row["metodo_pago"];
}

if($xDoc != "") {
	$pdf->SetFont('Arial','BI',8);
	$pdf->Cell(140, 4, "Total $xDoc ($items):", "0", "0", 'R');
	$pdf->Cell(28, 4, number_format($TotalBs, 2, ".", ","), "0", "0", 'R');
	$pdf->Cell(35, 4, number_format($TotalUsd, 2, ".", ","), "0", "0", 'R');
	$TotalBs = 0;
	$TotalUsd = 0;
	$pdf->SetFont('Arial', '', 8);
	$pdf->Ln();
	$pdf->Ln();
}

$pdf->SetFont('Arial','BI',8);

$pdf->Cell(10, 5);
$pdf->Cell(138, 5, "Total General: $i registros de abonos", 0, 0, 'R');
$pdf->Cell(20, 5, number_format($TotalGTBs, 2, ".", ","), 0, 0, 'R');
$pdf->Cell(15, 5);
$pdf->Cell(20, 5, number_format($TotalGTUsd, 2, ".", ","), 0, 0, 'R');

$pdf->EndReport($arrMP, $RSM, $TotalBs, $TotalUsd, $i, $Notas, $fecha);

	
require("../include/desconnect.php");

$pdf->Output();
?>