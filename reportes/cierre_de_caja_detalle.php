<?php
session_start();

$username = isset($_REQUEST["username"])?$_REQUEST["username"]:"";
$where = ($username=="" ? "" : " AND xy.username='$username'");
$GLOBALS["where"] = $where;

$GLOBALS["usuario_caja"] = trim($username)!=""?$username:"Todos";

require('rcs/fpdf.php');
require("../include/connect.php");


$xtitulo = isset($_REQUEST["xtitulo"])?$_REQUEST["xtitulo"]:"";
$xcliente = isset($_REQUEST["xcliente"])?$_REQUEST["xcliente"]:"0";
$fecha = isset($_REQUEST["fecha"])?$_REQUEST["fecha"]:"0";
$fecha2 = isset($_REQUEST["fecha2"])?$_REQUEST["fecha2"]:"0";

$fecha = substr($fecha, 0, 10);
$fecha2 = substr($fecha2, 0, 10); 


$sql = "SELECT 
			b.tasa_usd AS tasa  
		FROM 
			cobros_cliente AS a 
			JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
		WHERE 
			a.fecha = '$fecha' 
		UNION SELECT 
			a.tasa_usd AS tasa  
		FROM 
			recarga AS a 
		WHERE 
			a.fecha = '$fecha' 
		UNION SELECT 
			a.tasa_usd AS tasa  
		FROM 
			recarga2 AS a 
		WHERE 
			a.fecha = '$fecha' 
		LIMIT 0,1;"; 
$rs = mysqli_query($link, $sql) or die(mysqli_error());
if($row = mysqli_fetch_array($rs))
	$GLOBALS["tasa_usd"] = floatval($row["tasa"]);
else 
	$GLOBALS["tasa_usd"] = 0.00;


$xF = explode("-", $fecha);
$GLOBALS["xfecha"] = $xF[2] . "/" . $xF[1] . "/" . $xF[0];
$xF = explode("-", $fecha2);
$GLOBALS["xfecha2"] = $xF[2] . "/" . $xF[1] . "/" . $xF[0];

$GLOBALS["tasa_usd"] = "Tasa Cambio USD " . $GLOBALS["xfecha"] . ": " . number_format($GLOBALS["tasa_usd"], 2, ".", ",")  . " Bs. ";

$GLOBALS["titulo"] = $xtitulo;

$sql = "SELECT valor1, valor2 FROM parametro WHERE codigo = '009';";
$rs = mysqli_query($link, $sql);

$arrMP = array();
while($row = mysqli_fetch_array($rs)) {
	$arrMP[$row["valor1"]] = $row["valor2"];
}

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
		$this->Ln();
		$this->Cell(200, 5, "Usuario: " . $GLOBALS["usuario_caja"],0,0,'R');
		$this->Ln(3);

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
		$this->Cell(200, 6, "Para la fecha desde " . $GLOBALS["xfecha"] .  " hasta " . $GLOBALS["xfecha2"],0,0,'C');
		$this->SetFont('Arial','',8);		


		$this->Ln();
		

		require("../include/desconnect.php");
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
	
	function EndReport($x_arrMP, $x_RSM, $x_grandTotalBs, $x_grandTotalUsd, $x_i, $xnotas, $fecha, $fecha2)
	{
		$where = $GLOBALS["where"];
		$this->Ln();

		$this->AddPage();
		$this->SetFont('Arial','',8);


		require("../include/connect.php");

		$this->SetFont('Arial','BI',12);
		$this->Ln();
		$this->Cell(200, 6, "RESUMEN", 0, 0, 'C');
		$this->Ln();

		///////////////////////////// VENTAS /////////////////////////////
		$sql = "SELECT 
					COUNT(c.total) AS cantidad, SUM(c.total*tasa_dia) AS monto_bs, SUM(c.total) AS monto_usd 
				FROM 
					salidas AS c 
				WHERE 
					c.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' AND c.estatus = 'PROCESADO' 
					AND c.tipo_documento = 'TDCNET' AND IFNULL(c.pago_premio, 'N') = 'N' " . str_replace("xy.", "c.", $where);  
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

///////////////////////////////////
		$sql = "SELECT 
					metodo_pago, COUNT(id) AS cantidad, -- SUM(monto_bs) AS monto_bs, SUM(monto_usd) AS monto_usd, 
					SUM(IF(metodo_pago='PF' OR metodo_pago='PC' OR metodo_pago='DV' OR metodo_pago='NC' OR metodo_pago='ND', (-1), 1)*monto_bs) AS monto_bs, 
					SUM(IF(metodo_pago='PF' OR metodo_pago='PC' OR metodo_pago='DV' OR metodo_pago='NC' OR metodo_pago='ND', (-1), 1)*monto_usd) AS monto_usd
				FROM recarga WHERE fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
					AND (monto_usd > 0  OR (reverso = 'S' AND monto_usd < 0)) " . str_replace("xy.", "", $where) . " 
				GROUP BY metodo_pago
				UNION ALL SELECT 
					metodo_pago, COUNT(id) AS cantidad, -- SUM(monto_bs) AS monto_bs, SUM(monto_usd) AS monto_usd, 
					SUM(IF(metodo_pago='PF' OR metodo_pago='PC' OR metodo_pago='DV' OR metodo_pago='NC' OR metodo_pago='ND', (-1), 1)*monto_bs) AS monto_bs, 
					SUM(IF(metodo_pago='PF' OR metodo_pago='PC' OR metodo_pago='DV' OR metodo_pago='NC' OR metodo_pago='ND', (-1), 1)*monto_usd) AS monto_usd
				FROM recarga2 WHERE fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
					AND (monto_usd > 0  OR (reverso = 'S' AND monto_usd < 0)) " . str_replace("xy.", "", $where) . " 
				GROUP BY metodo_pago;"; 

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
		$this->Ln();
		$TotAbonosBs = $totBs;
		$TotAbonosUsd = $totUSD;

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
					a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' AND c.estatus = 'PROCESADO' 
					AND b.metodo_pago IN ('RC', 'RD') AND IFNULL(c.pago_premio, 'N') = 'N'  " . str_replace("xy.", "a.", $where) . "
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


/////////////////////////////////////


		$this->Ln(15);
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(70, 5, $GLOBALS["tasa_usd"], 0, 0, 'R');
		$this->Cell(60, 5, "TOTAL:  VENTAS + ABONOS - EGRESOS ", 1, 0, 'R');
		$this->Cell(25, 5, number_format($TotVentasBs + $TotAbonosBs - $TotEgresosBs, 2, ".", ","), 1, 0, 'R');
		$this->Cell(25, 5, number_format($TotVentasUsd + $TotAbonosUsd - $TotEgresosUsd, 2, ".", ","), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'L');
		$this->SetFont('Arial', '', 8);

		$this->Ln(15);
		$sql = "SELECT 
					COUNT(*) AS cantidad 
				FROM 
					salidas 
				WHERE 
					tipo_documento = 'TDCASA' 
					AND fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' AND IFNULL(pago_premio, 'N') = 'N';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		if(intval($row["cantidad"]) > 0) {
			$sql = "SELECT 
						 a.nro_documento AS documento, b.nombre AS cliente, a.estatus, a.nota, a.fecha  
					FROM 
						salidas AS a LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
					WHERE 
						a.tipo_documento = 'TDCASA' AND fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' AND IFNULL(a.pago_premio, 'N') = 'N';"; 
			$rs = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($rs);

			$this->SetFont('Arial', 'B', 8);
			$this->Cell(10, 5);
			$this->Cell(180, 5, "AJUSTES DE SALIDA", 1, 0, 'C');
			$this->Ln();
			$this->Cell(10, 5);
			$this->Cell(20, 5, "Documento", 1, 0, 'L');
			$this->Cell(60, 5, "Cliente", 1, 0, 'L');
			$this->Cell(20, 5, "Estatus", 1, 0, 'L');
			$this->Cell(80, 5, "Nota", 1, 0, 'L');
			$this->Cell(10, 5);
			$this->Ln();
			$this->SetFont('Arial', '', 8);

			$cant = 0;
			$rs = mysqli_query($link, $sql);
			while ($row = mysqli_fetch_array($rs)) {
				$this->Cell(10, 4);
				$this->Cell(20, 4, $row["documento"], 0, 0, 'L');
				$this->Cell(60, 4, $row["cliente"], 0, 0, 'L');
				$this->Cell(20, 4, $row["estatus"], 0, 0, 'L');
				$this->MultiCell(80, 4, $row["nota"], 0, 'L');
				$cant++;
			}
			$this->Cell(10, 5);
			$this->Cell(180, 5, "Total Ajustes de salidas: $cant", 1, 0, 'R');
		}

////////////////////////////////
		$this->ln(10);
		$sql = "SELECT 
					COUNT(*) AS cantidad 
				FROM 
					(SELECT 
						'NE' AS tipo, nro_documento, nota 
					FROM 
						salidas 
					WHERE 
						tipo_documento = 'TDCNET' 
						AND fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
						AND estatus = 'ANULADO' AND IFNULL(pago_premio, 'N') = 'N' 
					UNION ALL 
					SELECT 
						a.metodo_pago AS tipo, LPAD(b.nro_recibo, 7, '0') AS nro_documento, b. nota 
					FROM 
						recarga AS a JOIN abono AS b ON b.id = a.abono 
						AND b.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
						AND a.metodo_pago IN ('NC', 'ND')
					UNION ALL 
					SELECT 
						a.metodo_pago AS tipo, LPAD(b.nro_recibo, 7, '0') AS nro_documento, b. nota 
					FROM 
						recarga2 AS a JOIN abono AS b ON b.id = a.abono 
						AND b.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
						AND a.metodo_pago IN ('NC', 'ND')
					) AS a;";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs); 
		if(intval($row["cantidad"]) > 0) {
			$sql = "SELECT 
						'NE' AS tipo, nro_documento, nota 
					FROM 
						salidas 
					WHERE 
						tipo_documento = 'TDCNET' 
						AND fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
						AND estatus = 'ANULADO' AND IFNULL(pago_premio, 'N') = 'N' 
					UNION ALL 
					SELECT 
						a.metodo_pago AS tipo, LPAD(b.nro_recibo, 7, '0') AS nro_documento, b. nota 
					FROM 
						recarga AS a JOIN abono AS b ON b.id = a.abono 
						AND b.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
						AND a.metodo_pago IN ('NC', 'ND') 
					UNION ALL 
					SELECT 
						a.metodo_pago AS tipo, LPAD(b.nro_recibo, 7, '0') AS nro_documento, b. nota 
					FROM 
						recarga2 AS a JOIN abono AS b ON b.id = a.abono 
						AND b.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
						AND a.metodo_pago IN ('NC', 'ND') 
					ORDER BY 1 ASC;"; 
			$rs = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($rs);
			
			$this->SetFont('Arial', 'B', 8);
			$this->Cell(10, 5);
			$this->Cell(180, 5, "ANULACIONES EN N.E. Y NOTAS EN N.C. y N.D. ", 1, 0, 'C');
			$this->Ln();
			$this->Cell(10, 5);
			$this->Cell(20, 5, "Tipo", 1, 0, 'L');
			$this->Cell(30, 5, "Documento", 1, 0, 'L');
			$this->Cell(130, 5, "Nota", 1, 0, 'L');
			$this->Cell(10, 5);
			$this->Ln();
			$this->SetFont('Arial', '', 8);

			$cant = 0;
			$rs = mysqli_query($link, $sql);
			while ($row = mysqli_fetch_array($rs)) {
				$this->Cell(10, 4);
				$this->Cell(20, 4, $row["tipo"], 0, 0, 'L');
				$this->Cell(30, 4, $row["nro_documento"], 0, 0, 'L');
				$this->MultiCell(130, 4, utf8_decode($row["nota"]), 0, 'L');
				$cant++;
			}
			$this->Cell(10, 5);
			$this->Cell(180, 5, "Total: $cant", 1, 0, 'R');

		}

//////////////////////////// REVERSOS /////////////////////////////////////
		$this->Ln(15);
		$sql = "SELECT SUM(xz.cantidad) AS cantidad 
				FROM 
				(
					SELECT 
						COUNT(*) AS cantidad 
					FROM 
						recarga AS a 
					WHERE 
						reverso = 'S' 
						AND fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59'
					UNION ALL SELECT 
						COUNT(*) AS cantidad 
					FROM 
						recarga2 AS a 
					WHERE 
						reverso = 'S' 
						AND fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59'
				) AS xz;";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		if(intval($row["cantidad"]) > 0) {
			$sql = "SELECT 
						'B' AS rg, c.nombre AS cliente, DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
						(SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = a.metodo_pago) AS tipo_pago, 
						a.monto_moneda, a.moneda, b.nota, a.nota AS nota2   
					FROM 
						recarga AS a 
						JOIN abono AS b ON b.id = a.abono 
						JOIN cliente AS c ON c.id = a.cliente 
					WHERE 
						a.reverso = 'S' 
						AND a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59'
					UNION ALL SELECT 
						'D' AS rg, c.nombre AS cliente, DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
						(SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = a.metodo_pago) AS tipo_pago, 
						a.monto_moneda, a.moneda, b.nota, a.nota AS nota2   
					FROM 
						recarga2 AS a 
						JOIN abono2 AS b ON b.id = a.abono 
						JOIN cliente AS c ON c.id = a.cliente 
					WHERE 
						a.reverso = 'S' 
						AND a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59';"; 
			$rs = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($rs);

			$this->SetFont('Arial', 'B', 8);
			$this->Cell(10, 5);
			$this->Cell(180, 5, "REVERSOS DE ABONOS", 1, 0, 'C');
			$this->Ln();
			$this->Cell(10, 5);
			$this->Cell(20, 5, "Doc Rev", 1, 0, 'L');
			$this->Cell(40, 5, "Cliente", 1, 0, 'L');
			$this->Cell(80, 5, "Nota", 1, 0, 'L');
			$this->Cell(15, 5, "Fecha", 1, 0, 'L');
			$this->Cell(10, 5, "Mon", 1, 0, 'R');
			$this->Cell(15, 5, "Monto", 1, 0, 'R');
			$this->Cell(10, 5);
			$this->Ln();
			$this->SetFont('Arial', '', 8);

			$cant = 0;
			$rs = mysqli_query($link, $sql);
			while ($row = mysqli_fetch_array($rs)) { 
				$arrrev = explode(":", $row["nota"]); 
				if(isset($arrrev[1])) { 
					if($row["rg"] == "B") {
						$sql2 = "SELECT 
									b.nro_recibo 
								FROM recarga AS a JOIN abono AS b ON b.id = a.abono 
								WHERE a.id = '" . intval($arrrev[1]) . "';"; 						
					} 
					else {
						$sql2 = "SELECT 
									b.nro_recibo 
								FROM recarga2 AS a JOIN abono AS b ON b.id = a.abono 
								WHERE a.id = '" . intval($arrrev[1]) . "';"; 						
					}
					$rs2 = mysqli_query($link, $sql2);
					if($row2 = mysqli_fetch_array($rs2)) $nrbo = $row2["nro_recibo"];
					else $nrbo = "000000";					
				} 
				else { 
					$arrrev2 = explode("TDCNET ", $row["nota2"]); 
					if(isset($arrrev2[1])) $nrbo = "N.E.: " . $arrrev2[1];
					else $nrbo = $row["nota2"];
				} 
				$this->Cell(10, 4);
				$this->Cell(20, 4, str_pad($nrbo, 7, "0", STR_PAD_LEFT), 0, 0, 'L');
				$this->Cell(40, 4, substr($row["cliente"],0, 20), 0, 0, 'L');
				$this->Cell(80, 4, substr($row["nota2"],0, 55), 0, 0, 'L');
				$this->Cell(15, 4, $row["fecha"], 0, 0, 'L');
				$this->Cell(10, 4, $row["moneda"], 0, 0, 'R');
				$this->Cell(15, 4, $row["monto_moneda"], 0, 0, 'R');
				$cant++;
				$this->Ln();
			}
			$this->Cell(10, 5);
			$this->Cell(180, 5, "Total Reversos de Abonos: $cant", 1, 0, 'R');
		}

		if($GLOBALS["canjes"]) { 
			/// NOTAS DE ENTREGAS COMO PAGOS DE PREMIOS ///

			$this->Cell(200, 6, "NOTAS DE ENTREGAS DE PREMIOS CANJEADOS", 0, 0, 'C');
			$this->SetFont('Arial','BI',8);
			$this->Ln();
			$this->Cell(10, 6);
			$this->Cell(15, 6, "DOC.", 1, 0, 'L');
			$this->Cell(50, 6, "CLIENTE", 1, 0, 'L');
			$this->Cell(88, 6, "TIPO", 1, 0, 'L');
			$this->Cell(20, 6, "$ COBRO", 1, 0, 'R');
			$this->Cell(20, 6, "$ MONTO", 1, 0, 'R');
			$this->Ln(6);

			$sql = "SELECT c.id, a.id,  
						DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
						d.nombre AS cliente, 
						c.nro_documento, 
						if(c.estatus <> 'PROCESADO' , 0, if(c.DOCUMENTO='NC', (-1)*c.total, c.total)) total, 
						c.estatus, 
						IF((SELECT DISTINCT metodo_pago FROM cobros_cliente_detalle WHERE cobros_cliente = a.id AND metodo_pago = 'RC')='RC', 'RC', 'EF') AS tipo_pago, 
						(SELECT SUM(monto_bs) FROM cobros_cliente_detalle WHERE cobros_cliente = a.id AND metodo_pago = 'RC') AS rc_monto_bs, 
						(SELECT SUM(monto_usd) FROM cobros_cliente_detalle WHERE cobros_cliente = a.id AND metodo_pago = 'RC') AS rc_monto_usd, 
						(SELECT SUM(monto_bs) FROM cobros_cliente_detalle WHERE cobros_cliente = a.id) AS monto_bs, 
						(SELECT SUM(monto_usd) FROM cobros_cliente_detalle WHERE cobros_cliente = a.id) AS monto_usd, 
						c.id, c.descuento  
					FROM 
						salidas AS c 
						LEFT OUTER JOIN cliente AS d ON d.id = c.cliente 
						LEFT OUTER JOIN cobros_cliente AS a ON a.id_documento = c.id 
					WHERE 
						c.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' AND IFNULL(c.pago_premio, 'N') = 'S'  
						AND c.tipo_documento = 'TDCNET' " . str_replace("xy.", "c.", $where) . " -- AND c.estatus = 'PROCESADO' 
					GROUP BY 
						DATE_FORMAT(a.fecha, '%d/%m/%Y'), 
						d.nombre, 
						c.nro_documento, 
						c.DOCUMENTO, 
						c.estatus, 
						c.id, a.id 
					ORDER BY c.id;"; 

			$rs = mysqli_query($link, $sql) or die(mysqli_error());
			$items = 0;
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
				if($row["tipo_pago"] == "RC") {
					$this->SetFont('Arial', 'BI', 8);
				} 
				else {
					$this->SetFont('Arial', '', 8);
				}

				$this->Cell(10, 4);
				$this->Cell(15, 4, 	$row["nro_documento"], 0, 0, 'L');
				$this->Cell(50, 4, substr(utf8_decode($row["cliente"]), 0, 60), 0, 0, 'L');
				$pago = floatval($row["total"])-floatval($row["rc_monto_usd"]);
				$this->Cell(88, 4, ($row["tipo_pago"] == "RC" ? "Egreso Cuenta Cliente " . ($row["estatus"] == "ANULADO" ? "" : number_format($row["total"], 2, ".", ",") . " - " . number_format($row["rc_monto_usd"], 2, ".", ",") . " = " . number_format($pago, 2, ".", ",")) : "Contado") .  ($row["estatus"] == "PROCESADO" ? "" : " - " . $row["estatus"]) . (floatval($row["descuento"]) == 0.00 ? "" : " *** Desc.: " . number_format(floatval($row["descuento"]), 2, ".", ",") . "% ***"), 0, 0, 'L');

				if($row["tipo_pago"] == "RC") {
					$this->Cell(20, 4, number_format(($row["estatus"] == "ANULADO" ? 0 : $pago), 2, ".", ","), 0, 0, 'R'); 
					$TotalUsd += ($row["estatus"] == "ANULADO" ? 0 : $pago); // $pago;
				}
				else {
					$this->Cell(20, 4, number_format($row["monto_usd"], 2, ".", ","), 0, 0, 'R'); 
					$TotalUsd += floatval($row["monto_usd"]);
				}

				if($xDoc != $row["nro_documento"]) {
					$this->Cell(20, 4, number_format($row["total"], 2, ".", ","), 0, 0, 'R');
					$TotalNE += $row["total"];
					$xDoc = $row["nro_documento"];
				}
				else 
					$this->Cell(20, 4, "", 0, 0, 'R');

				// $TotalUsd += floatval($row["monto_usd"]) - floatval($row["rc_monto_usd"]);

				$this->Ln();

				$i++;

				if($NE != $row["id"]) $Notas++;
				$NE = $row["id"];
			}

			$this->SetFont('Arial','BI',8);

			$this->Cell(10, 5);
			//$pdf->Cell(138, 5, "Total General: $i registros de pago en $Notas Nota(s) de Entrega", 0, 0, 'R');
			$this->Cell(138, 5, "Total $Notas Nota(s) de Entrega", 0, 0, 'R');
			$this->Cell(15, 5);
			$this->Cell(20, 5, number_format($TotalUsd, 2, ".", ","), 0, 0, 'R');
			$this->Cell(20, 5, number_format($TotalNE, 2, ".", ","), 0, 0, 'R');

			/// ///
		}

		require("../include/desconnect.php");
	}
}

// Creaci?n del objeto de la clase heredada
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

$sql = "SELECT c.id, a.id,  
			DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
			d.nombre AS cliente, 
			c.nro_documento, 
			if(c.estatus <> 'PROCESADO' , 0, if(c.DOCUMENTO='NC', (-1)*c.total, c.total)) total, 
			c.estatus, 
			IF((SELECT DISTINCT metodo_pago FROM cobros_cliente_detalle WHERE cobros_cliente = a.id AND metodo_pago = 'RC')='RC', 'RC', 'EF') AS tipo_pago, 
			(SELECT SUM(monto_bs) FROM cobros_cliente_detalle WHERE cobros_cliente = a.id AND metodo_pago = 'RC') AS rc_monto_bs, 
			(SELECT SUM(monto_usd) FROM cobros_cliente_detalle WHERE cobros_cliente = a.id AND metodo_pago = 'RC') AS rc_monto_usd, 
			(SELECT SUM(monto_bs) FROM cobros_cliente_detalle WHERE cobros_cliente = a.id) AS monto_bs, 
			(SELECT SUM(monto_usd) FROM cobros_cliente_detalle WHERE cobros_cliente = a.id) AS monto_usd, 
			c.id, c.descuento  
		FROM 
			salidas AS c 
			LEFT OUTER JOIN cliente AS d ON d.id = c.cliente 
			LEFT OUTER JOIN cobros_cliente AS a ON a.id_documento = c.id 
		WHERE 
			c.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' AND IFNULL(c.pago_premio, 'N') = 'N'  
			AND c.tipo_documento = 'TDCNET' " . str_replace("xy.", "c.", $where) . " -- AND c.estatus = 'PROCESADO' 
		GROUP BY 
			DATE_FORMAT(a.fecha, '%d/%m/%Y'), 
			d.nombre, 
			c.nro_documento, 
			c.DOCUMENTO, 
			c.estatus, 
			c.id, a.id 
		ORDER BY c.id;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
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
	if($row["tipo_pago"] == "RC") {
		$pdf->SetFont('Arial', 'BI', 8);
	} 
	else {
		$pdf->SetFont('Arial', '', 8);
	}

	$pdf->Cell(10, 4);
	$pdf->Cell(15, 4, 	$row["nro_documento"], 0, 0, 'L');
	$pdf->Cell(50, 4, substr(utf8_decode($row["cliente"]), 0, 60), 0, 0, 'L');
	$pago = floatval($row["total"])-floatval($row["rc_monto_usd"]);
	$pdf->Cell(88, 4, ($row["tipo_pago"] == "RC" ? "Egreso Cuenta Cliente " . ($row["estatus"] == "ANULADO" ? "" : number_format($row["total"], 2, ".", ",") . " - " . number_format($row["rc_monto_usd"], 2, ".", ",") . " = " . number_format($pago, 2, ".", ",")) : "Contado") .  ($row["estatus"] == "PROCESADO" ? "" : " - " . $row["estatus"]) . (floatval($row["descuento"]) == 0.00 ? "" : " *** Desc.: " . number_format(floatval($row["descuento"]), 2, ".", ",") . "% ***"), 0, 0, 'L');

	if($row["tipo_pago"] == "RC") {
		$pdf->Cell(20, 4, number_format(($row["estatus"] == "ANULADO" ? 0 : $pago), 2, ".", ","), 0, 0, 'R'); 
		$TotalUsd += ($row["estatus"] == "ANULADO" ? 0 : $pago); // $pago;
	}
	else {
		$pdf->Cell(20, 4, number_format($row["monto_usd"], 2, ".", ","), 0, 0, 'R'); 
		$TotalUsd += floatval($row["monto_usd"]);
	}

	if($xDoc != $row["nro_documento"]) {
		$pdf->Cell(20, 4, number_format($row["total"], 2, ".", ","), 0, 0, 'R');
		$TotalNE += $row["total"];
		$xDoc = $row["nro_documento"];
	}
	else 
		$pdf->Cell(20, 4, "", 0, 0, 'R');

	// $TotalUsd += floatval($row["monto_usd"]) - floatval($row["rc_monto_usd"]);

	$pdf->Ln();

	$i++;

	if($NE != $row["id"]) $Notas++;
	$NE = $row["id"];
}

$pdf->SetFont('Arial','BI',8);

$pdf->Cell(10, 5);
//$pdf->Cell(138, 5, "Total General: $i registros de pago en $Notas Nota(s) de Entrega", 0, 0, 'R');
$pdf->Cell(138, 5, "Total $Notas Nota(s) de Entrega", 0, 0, 'R');
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
			a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' AND c.estatus = 'PROCESADO' AND c.tipo_documento = 'TDCNET' 
			AND b.metodo_pago IN ('RC', 'RD') AND IFNULL(c.pago_premio, 'N') = 'N'  " . str_replace("xy.", "a.", $where) . ";"; 
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


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$pdf->AddPage();
$pdf->SetFont('Arial','BI',12);
$pdf->Cell(200, 6, "INGRESOS POR ABONOS", 0, 0, 'C');
$pdf->Ln();
$pdf->SetFont('Arial','BI',8);
$pdf->Cell(10, 6);
$pdf->Cell(15, 6, "REC.", 1, 0, 'L');
$pdf->Cell(80, 6, "CLIENTE", 1, 0, 'L');
$pdf->Cell(58, 6, "TIPO", 1, 0, 'L');
$pdf->Cell(40, 6, "$ MONTO", 1, 0, 'R');
$pdf->Ln(6);

//				CONCAT('EXCEDENTE EN PAGO', IFNULL(a.nota, '')), 
$sql = "SELECT 
			DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
			CONCAT(b.nombre, ' - ', SUBSTRING(IFNULL(a.nota, ''), 45, 30)) AS cliente, 
			IF(a.metodo_pago='RC', 
				'EXCEDENTE EN PAGO',  
				IF(a.metodo_pago='PF' OR a.metodo_pago='PC' OR a.metodo_pago='DV' OR a.metodo_pago='NC' OR a.metodo_pago='ND', 
					(SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = a.metodo_pago), 
					'ABONO')
			) AS metodo_pago, 
			a.referencia, 
			a.moneda, 
			a.monto_moneda, 
			a.tasa_moneda, 
			a.tasa_usd, 
			(SELECT nro_recibo FROM abono WHERE id = a.abono) AS nro_recibo, 
			IF(a.metodo_pago='PF' OR a.metodo_pago='PC' OR a.metodo_pago='DV' OR a.metodo_pago='NC' OR a.metodo_pago='ND', (-1), 1)*a.monto_bs AS monto_bs, 
			IF(a.metodo_pago='PF' OR a.metodo_pago='PC' OR a.metodo_pago='DV' OR a.metodo_pago='NC' OR a.metodo_pago='ND', (-1), 1)*a.monto_usd AS monto_usd, 
			a.metodo_pago AS tipo_pago, 
			a.reverso, a.monto_usd as rv_monto_usd 
		FROM 
			recarga AS a 
			JOIN cliente AS b ON b.id = a.cliente 
		WHERE 
			a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
			AND (a.monto_usd > 0 OR a.reverso = 'S') " . str_replace("xy.", "a.", $where) . "
		UNION ALL SELECT 
			DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
			CONCAT(b.nombre, ' - ', SUBSTRING(IFNULL(a.nota, ''), 47, 15)) AS cliente, 
			IF(a.metodo_pago='RC', 
				'EXCEDENTE EN PAGO',
				IF(a.metodo_pago='PF' OR a.metodo_pago='PC' OR a.metodo_pago='DV' OR a.metodo_pago='NC' OR a.metodo_pago='ND', 
					(SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = a.metodo_pago), 
					'ABONO')
			) AS metodo_pago, 
			a.referencia, 
			a.moneda, 
			a.monto_moneda, 
			a.tasa_moneda, 
			a.tasa_usd, 
			(SELECT nro_recibo FROM abono WHERE id = a.abono) AS nro_recibo, 
			IF(a.metodo_pago='PF' OR a.metodo_pago='PC' OR a.metodo_pago='DV' OR a.metodo_pago='NC' OR a.metodo_pago='ND', (-1), 1)*a.monto_bs AS monto_bs, 
			IF(a.metodo_pago='PF' OR a.metodo_pago='PC' OR a.metodo_pago='DV' OR a.metodo_pago='NC' OR a.metodo_pago='ND', (-1), 1)*a.monto_usd AS monto_usd, 
			a.metodo_pago AS tipo_pago, 
			a.reverso, a.monto_usd as rv_monto_usd 
		FROM 
			recarga AS a 
			JOIN cliente AS b ON b.id = a.cliente 
		WHERE 
			a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
			AND (a.monto_usd <= 0 OR a.reverso = 'S') AND a.metodo_pago IN ('NC', 'ND') " . str_replace("xy.", "a.", $where) . "
		UNION ALL SELECT 
			DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
			CONCAT(b.nombre, ' - ', SUBSTRING(IFNULL(a.nota, ''), 45, 30)) AS cliente, 
			IF(a.metodo_pago='RC', 
				'EXCEDENTE EN PAGO',  
				IF(a.metodo_pago='PF' OR a.metodo_pago='PC' OR a.metodo_pago='DV' OR a.metodo_pago='NC' OR a.metodo_pago='ND', 
					(SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = a.metodo_pago), 
					'ABONO')
			) AS metodo_pago, 
			a.referencia, 
			a.moneda, 
			a.monto_moneda, 
			a.tasa_moneda, 
			a.tasa_usd, 
			(SELECT nro_recibo FROM abono WHERE id = a.abono) AS nro_recibo, 
			IF(a.metodo_pago='PF' OR a.metodo_pago='PC' OR a.metodo_pago='DV' OR a.metodo_pago='NC' OR a.metodo_pago='ND', (-1), 1)*a.monto_bs AS monto_bs, 
			IF(a.metodo_pago='PF' OR a.metodo_pago='PC' OR a.metodo_pago='DV' OR a.metodo_pago='NC' OR a.metodo_pago='ND', (-1), 1)*a.monto_usd AS monto_usd, 
			a.metodo_pago AS tipo_pago, 
			a.reverso, a.monto_usd as rv_monto_usd 
		FROM 
			recarga2 AS a 
			JOIN cliente AS b ON b.id = a.cliente 
		WHERE 
			a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
			AND (a.monto_usd > 0 OR a.reverso = 'S') " . str_replace("xy.", "a.", $where) . "
		UNION ALL SELECT 
			DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
			CONCAT(b.nombre, ' - ', SUBSTRING(IFNULL(a.nota, ''), 47, 15)) AS cliente, 
			IF(a.metodo_pago='RC', 
				'EXCEDENTE EN PAGO',
				IF(a.metodo_pago='PF' OR a.metodo_pago='PC' OR a.metodo_pago='DV' OR a.metodo_pago='NC' OR a.metodo_pago='ND', 
					(SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = a.metodo_pago), 
					'ABONO')
			) AS metodo_pago, 
			a.referencia, 
			a.moneda, 
			a.monto_moneda, 
			a.tasa_moneda, 
			a.tasa_usd, 
			(SELECT nro_recibo FROM abono WHERE id = a.abono) AS nro_recibo, 
			IF(a.metodo_pago='PF' OR a.metodo_pago='PC' OR a.metodo_pago='DV' OR a.metodo_pago='NC' OR a.metodo_pago='ND', (-1), 1)*a.monto_bs AS monto_bs, 
			IF(a.metodo_pago='PF' OR a.metodo_pago='PC' OR a.metodo_pago='DV' OR a.metodo_pago='NC' OR a.metodo_pago='ND', (-1), 1)*a.monto_usd AS monto_usd, 
			a.metodo_pago AS tipo_pago, 
			a.reverso, a.monto_usd as rv_monto_usd 
		FROM 
			recarga2 AS a 
			JOIN cliente AS b ON b.id = a.cliente 
		WHERE 
			a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
			AND (a.monto_usd <= 0 OR a.reverso = 'S') AND a.metodo_pago IN ('NC', 'ND') " . str_replace("xy.", "a.", $where) . "
		ORDER BY metodo_pago, nro_recibo;"; 
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
$ii=3;
$mySW = true;
while($row = mysqli_fetch_array($rs))
{
	if($xDoc != $row["metodo_pago"]) {
		if($xDoc != "") {
			$pdf->SetFont('Arial','BI',8);
			$pdf->Cell(168, 4, str_pad($ii, 2, "0", STR_PAD_LEFT) . " - Total $xDoc ($items):", "0", "0", 'R');
			$pdf->Cell(35, 4, number_format($TotalUsd, 2, ".", ","), "0", "0", 'R');
			$TotalBs = 0;
			$TotalUsd = 0;
			$items = 0;
			$pdf->SetFont('Arial', '', 8);
			$pdf->Ln();
			$pdf->Ln();
			$ii++;
			$mySW = true;
		}

		if($mySW) {
			$pdf->SetFont('Arial','BI',12);
			$pdf->Cell(200, 4, str_pad($ii, 2, "0", STR_PAD_LEFT) . " - " . $row["metodo_pago"], "0", "0", 'C');
			$pdf->SetFont('Arial', '', 8);
			$pdf->Ln();
			$mySW = false;
		}
	}

	if($row["tipo_pago"] == "PC" or $row["tipo_pago"] == "PF" or $row["tipo_pago"] == "DV") {
		$pdf->SetFont('Arial', 'BI', 8);
	} 
	else {
		$pdf->SetFont('Arial', '', 8);
	}

	if(($row["reverso"]=="S" and floatval($row["monto_usd"])>0)) {
	} 
	else {
		$pdf->Cell(10, 4);
		$pdf->Cell(15, 4, str_pad($row["nro_recibo"], 7, "0", STR_PAD_LEFT), 0, 0, 'L');
		$pdf->Cell(80, 4, utf8_decode(substr($row["cliente"], 0, 45)), 0, 0, 'L');
		$pdf->Cell(58, 4, trim($row["metodo_pago"]), 0, 0, 'L');
		$pdf->Cell(40, 4, number_format(($row["tipo_pago"] == "PC" or $row["tipo_pago"] == "PF" or $row["tipo_pago"] == "DV") ? abs($row["monto_usd"]) : floatval($row["monto_usd"]), 2, ".", ","), 0, 0, 'R');
		$pdf->Ln();
		if(($row["monto_usd"] >= 0 and $row["reverso"] == "N" AND $row["tipo_pago"] <> 'NC' AND $row["tipo_pago"] <> 'ND') or ($row["reverso"] == "S" and $row["tipo_pago"] != "PC" and $row["tipo_pago"] != "PF" and $row["tipo_pago"] != "DV")) {
			$TotalBs += $row["monto_bs"];
			$TotalUsd += $row["monto_usd"];
			$TotalGTBs += $row["monto_bs"];
			$TotalGTUsd += $row["monto_usd"];
		}

		$i++;
		$items++;
	}
	$xDoc = $row["metodo_pago"];
}

if($xDoc != "") {
	$pdf->SetFont('Arial','BI',8);
	$pdf->Cell(168, 4, str_pad($ii, 2, "0", STR_PAD_LEFT) . " - Total $xDoc ($items):", "0", "0", 'R');
	$pdf->Cell(35, 4, number_format($TotalUsd, 2, ".", ","), "0", "0", 'R');
	$TotalBs = 0;
	$TotalUsd = 0;
	$pdf->SetFont('Arial', '', 8);
	$pdf->Ln();
	$pdf->Ln();
}

$pdf->SetFont('Arial','BI',8);

$pdf->Cell(10, 5);
$pdf->Cell(158, 5, "Total General: $i registros de abonos", 0, 0, 'R');
$pdf->Cell(15, 5);
$pdf->Cell(20, 5, number_format($TotalGTUsd, 2, ".", ","), 0, 0, 'R');

/*** Pagos de Premios ***/
$sql = "SELECT 
			a.id, DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, c.nombre as cliente, 
			a.tipo, a.nro_documento, CONCAT(a.referencia, ' ', b.principio_activo) AS referencia, a.puntos, a.saldo 
		FROM 
			puntos AS a 
			JOIN articulo AS b ON b.codigo_ims = a.referencia  
			JOIN cliente AS c ON c.id = a.cliente  
		WHERE a.tipo = 'PP' AND a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59';"; 
$rs = mysqli_query($link, $sql) or die(mysqli_error());
$GLOBALS["canjes"] = false;
if($row = mysqli_fetch_array($rs)) {
	$pdf->Ln(15);
	$pdf->SetFont('Arial','BI',12);
	$pdf->Cell(200, 6, "PREMIOS CANJEADOS", 0, 0, 'C');
	$pdf->Ln();
	$pdf->SetFont('Arial','BI',8);
	$pdf->Cell(10, 6);
	$pdf->Cell(10, 6, "NRO.", 1, 0, 'L');
	$pdf->Cell(50, 6, "CLIENTE", 1, 0, 'L');
	$pdf->Cell(15, 6, "TIPO", 1, 0, 'L');
	$pdf->Cell(20, 6, "AJUSTE S", 1, 0, 'L');
	$pdf->Cell(75, 6, "REFERENCIA", 1, 0, 'L');
	$pdf->Cell(10, 6, "PTOS", 1, 0, 'R');
	$pdf->Cell(10, 6, "SALD", 1, 0, 'R');
	$pdf->Ln(6);

	$sql = "SELECT 
				a.id, DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, SUBSTRING(c.nombre, 1, 20) as cliente, 
				a.tipo, RTRIM(REPLACE(a.nro_documento, 'AJUSTE SAL:', '')) AS nro_documento, SUBSTRING(CONCAT(a.referencia, ' ', b.principio_activo), 1, 45) AS referencia, a.puntos, a.saldo 
			FROM 
				puntos AS a 
				JOIN articulo AS b ON b.codigo_ims = a.referencia 
				JOIN cliente AS c ON c.id = a.cliente  
			WHERE a.tipo = 'PP' AND a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59';"; 
	$rs = mysqli_query($link, $sql) or die(mysqli_error()); 
	while($row = mysqli_fetch_array($rs)) {
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(10, 4);
		$pdf->Cell(10, 4, $row["id"], 0, 0, 'L');
		$pdf->Cell(50, 4, $row["cliente"], 0, 0, 'L');
		$pdf->Cell(15, 4, $row["tipo"], 0, 0, 'L');
		$pdf->Cell(20, 4, $row["nro_documento"], 0, 0, 'L');
		$pdf->Cell(75, 4, $row["referencia"], 0, 0, 'L');
		$pdf->Cell(10, 4, $row["puntos"], 0, 0, 'R');
		$pdf->Cell(10, 4, $row["saldo"], 0, 0, 'R');
		$pdf->Ln();
		$GLOBALS["canjes"] = true;
	}
}
/*** ***/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$ii++;
$pdf->AddPage();
$pdf->SetFont('Arial','BI',12);
$pdf->Cell(200, 6, str_pad($ii, 2, "0", STR_PAD_LEFT) . " - INGRESOS POR TIPO DE PAGO", 0, 0, 'C');
$pdf->SetFont('Arial','BI',8);
$pdf->Ln();
$pdf->Cell(10, 6);
$pdf->Cell(25, 6, "TIPO DE PAGO", 1, 0, 'L');
$pdf->Cell(25, 6, "# REF.", 1, 0, 'L');
$pdf->Cell(30, 6, "DOCUMENTO", 1, 0, 'L');
$pdf->Cell(20, 6, "NRO DOC", 1, 0, 'C');
$pdf->Cell(50, 6, "CLIENTE", 1, 0, 'L');
$pdf->Cell(20, 6, "MONTO BS", 1, 0, 'R');
$pdf->Cell(20, 6, "MONTO USD", 1, 0, 'R');

$pdf->Ln();
$pdf->SetFont('Arial','BI',8);
$pdf->Cell(10, 6);

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
				a.metodo_pago NOT IN ('RC', 'RD', 'PF', 'PC', 'DV', 'NC', 'ND') 
				AND b.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
				AND c.estatus = 'PROCESADO' AND IFNULL(c.pago_premio, 'N') = 'N' " . str_replace("xy.", "b.", $where) . "
			UNION ALL 
			SELECT 
				'RECIBO' AS TIPO, 
				a.metodo_pago, 
				a.moneda, 
				(SELECT LPAD(nro_recibo, 7, '0') FROM abono WHERE id = a.abono) AS doc, 
				b.nombre AS cliente, 
				a.monto_bs AS monto_bs, 
				a.monto_usd AS monto_usd, a.referencia  
			FROM 
				recarga AS a 
				LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
			WHERE 
				a.metodo_pago NOT IN ('RC', 'RD', 'PF', 'PC', 'DV', 'NC', 'ND') 
				AND a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
				AND (a.monto_usd > 0 OR a.reverso = 'S') " . str_replace("xy.", "a.", $where) . "
			UNION ALL 
			SELECT 
				'RECIBO' AS TIPO, 
				a.metodo_pago, 
				a.moneda, 
				(SELECT LPAD(nro_recibo, 7, '0') FROM abono2 WHERE id = a.abono) AS doc, 
				b.nombre AS cliente, 
				a.monto_bs AS monto_bs, 
				a.monto_usd AS monto_usd, a.referencia  
			FROM 
				recarga2 AS a 
				LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
			WHERE 
				a.metodo_pago NOT IN ('RC', 'RD', 'PF', 'PC', 'DV', 'NC', 'ND') 
				AND a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' 
				AND (a.monto_usd > 0 OR a.reverso = 'S') " . str_replace("xy.", "a.", $where) . "
		) AS aa 
		LEFT OUTER JOIN parametro AS bb ON bb.valor1 = aa.metodo_pago 
		WHERE bb.codigo = '009' AND aa.metodo_pago NOT IN ('RC', 'PF', 'PC', 'DV', 'NC', 'ND') 
		ORDER BY 2, 1,3;"; 
$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$TotalBs = 0;
$TotalUsd = 0;
$TotalGTBs = 0;
$TotalGTUsd = 0;
$xDoc = "";
$ii = 0;

$pdf->Ln();

$xMetodoPgo = "";
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

	if($row["tipo_pago"] == "RC" or $row["tipo_pago"] == "PC" or $row["tipo_pago"] == "PF" or $row["tipo_pago"] == "DV") {
		$pdf->SetFont('Arial', 'BI', 8);
	} 
	else {
		$pdf->SetFont('Arial', '', 8);
	}

	$pdf->Cell(10, 4);
	// $pdf->Cell(18, 4, $row["fecha"], 0, 0, 'L');
	$xMetodoPgo = str_replace("TARJETA", "T.", $row["metodo_pago"]);
	$xMetodoPgo = str_replace("TRANSFERENCIA", "TRANS.", $xMetodoPgo);
	$xMetodoPgo = str_replace("PAGO MOVIL", "PAG. M.", $xMetodoPgo);
	$xMetodoPgo = str_replace("NOTA DE CREDITO", "NOTA DE C.", $xMetodoPgo);
	$xMetodoPgo = str_replace("NOTA DE DEBITO", "NOTA DE D.", $xMetodoPgo);
	$xMetodoPgo = str_replace("PROMOCION CATALOGO", "PRO. CATALOG.", $xMetodoPgo);
	$pdf->Cell(25, 4, $xMetodoPgo, 0, 0, 'L');
	$pdf->Cell(25, 4, $row["referencia"], 0, 0, 'L');
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
	$ii++;

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

$pdf->Ln();
$pdf->SetFont('Arial','BI',8);

$pdf->Cell(10, 5);
$pdf->Cell(150, 5, "Total General Pagos ($ii): ", 0, 0, 'R');
$pdf->Cell(20, 5, number_format($TotalGTBs, 2, ".", ","), 0, 0, 'R');
$pdf->Cell(20, 5, number_format($TotalGTUsd, 2, ".", ","), 0, 0, 'R');


$pdf->EndReport($arrMP, $RSM, $TotalBs, $TotalUsd, $i, $Notas, $fecha, $fecha2);

	
require("../include/desconnect.php");

$pdf->Output();
?>