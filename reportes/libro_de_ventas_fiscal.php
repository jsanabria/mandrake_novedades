<?php
require('rcs/fpdf.php');
require("../include/connect.php");

$xfecha = isset($_REQUEST["xfecha"])?$_REQUEST["xfecha"]:"0";
$yfecha = isset($_REQUEST["yfecha"])?$_REQUEST["yfecha"]:"0";

$f = explode("-", $xfecha);
$fecdesde = $f["2"] . "/" . $f["1"] . "/" . $f["0"];
$f = explode("-", $yfecha);
$fechasta = $f["2"] . "/" . $f["1"] . "/" . $f["0"];

$GLOBALS["titulo"] = "Libro de Ventas";
$GLOBALS["subtitulo"] = "Desde $fecdesde Hasta $fechasta";


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
		$ciudad = $row["ciudad"];
		$direccion = $row["direccion"]; 
		$cia =  $row["nombre"];
		$logo =  $row["logo"];

		
		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 50);
		}
		
		$this->Ln(5);
		$this->SetFont('Arial','',6);
		$this->Cell(340, 5, "Fecha: " . date("d/m/Y"),0,0,'R');
		$this->Ln();
		$this->Cell(340, 5, "Hora: " . date("H:i:s"),0,0,'R');

		$this->Ln(2);
		
		$this->SetFont('Arial','B',14);
		$this->Cell(340, 6, utf8_decode($GLOBALS["titulo"]),0,0,'C');
		$this->SetFont('Arial','',12);
		$this->Ln();
		$this->Cell(340, 6, $GLOBALS["subtitulo"],0,0,'C');
		$this->SetFont('Arial','',5);		


		$this->Ln(8);
		

		require("../include/desconnect.php");

		$this->Cell(5, 5);
		$this->Cell(8, 5, "", "LTR", 0, 'C');
		$this->Cell(10, 5, "", "LTR", 0, 'L');
		$this->Cell(12, 5, "", "LTR", 0, 'L');
		$this->Cell(30, 5, "", "LTR", 0, 'L');
		$this->Cell(10, 5, "N. PLAN.", "LTR", 0, 'C');
		$this->Cell(29, 5, "", "LTR", 0, 'C');
		$this->Cell(15, 5, "REGIST.", "LTR", 0, 'C');
		$this->Cell(15, 5, "NRO", "LTR", 0, 'C');
		$this->Cell(32, 5, "NRO", "LTR", 0, 'C');
		$this->Cell(15, 5, "NOTA", "LTR", 0, 'C');
		$this->Cell(15, 5, "NOTA", "LTR", 0, 'C');
		$this->Cell(5, 5, "TIP", "LTR", 0, 'C');
		$this->Cell(15, 5, "NRO", "LTR", 0, 'C');
		$this->Cell(15, 5, "FECHA DOC", "LTR", 0, 'C');
		$this->Cell(15, 5, "NRO DOC", "LTR", 0, 'C');
		$this->Cell(18, 5, "TOTAL CON IVA", "LTR", 0, 'R');
		$this->Cell(18, 5, "TOTAL SIN IVA", "LTR", 0, 'R');
		$this->Cell(44, 5, "DEBITO FISCAL", 1, 0, 'C');
		/**** RETENCION IVA ***/
		$this->Cell(18, 5, "IVA RET", 1, 0, 'C');

		$this->Ln(5);

		$this->Cell(5, 5);
		$this->Cell(8, 5, "N. OP", "LBR", 0, 'C');
		$this->Cell(10, 5, "FECHA", "LBR", 0, 'C');
		$this->Cell(12, 5, "RIF", "LBR", 0, 'L');
		$this->Cell(30, 5, "NOMBRE O RAZON SOCIAL", "LBR", 0, 'L');
		$this->Cell(10, 5, "EXPORT", "LBR", 0, 'C');
		$this->Cell(29, 5, "FACT", "LBR", 0, 'C');
		$this->Cell(15, 5, "MAQUINA", "LBR", 0, 'C');
		$this->Cell(15, 5, "REPOR. Z", "LBR", 0, 'C');
		$this->Cell(32, 5, "CONTROL", "LBR", 0, 'C');
		$this->Cell(15, 5, "DEBITO", "LBR", 0, 'C');
		$this->Cell(15, 5, "CREDITO", "LBR", 0, 'C');
		$this->Cell(5, 5, "TRA", "LBR", 0, 'R');
		$this->Cell(15, 5, "DOC. AFEC", "LBR", 0, 'C');
		$this->Cell(15, 5, "RETENCION", "LBR", 0, 'C');
		$this->Cell(15, 5, "RETENCION", "LBR", 0, 'C');
		$this->Cell(18, 5, "VENTAS", "LBR", 0, 'R');
		$this->Cell(18, 5, "EXENTAS", "LBR", 0, 'R');
		$this->Cell(18, 5, "BASE", "LBR", 0, 'R');
		$this->Cell(8, 5, "%", "LBR", 0, 'R');
		$this->Cell(18, 5, "IMPUESTO", "LBR", 0, 'R');
		/**** RETENCION IVA ***/
		$this->Cell(18, 5, "COMPRAD..", "LBR", 0, 'R');
		$this->Ln(5);
	}
	
	// Pie de página
	function Footer()
	{
		// Posición: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Número de página
		$this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($items, $_total, $_exenta, $_gravable, $_iva, $_retiva, $_retislr)
	{
		//$this->AddPage();
		//require("../connect.php");
		$this->SetFont('Arial','B',5);
		$this->Ln();
		$this->Cell(246, 4, "", 0, 0, 'R');
		$this->Cell(18, 4, number_format($_total, 2, ",", "."), 1, 0, 'R');
		$this->Cell(18, 4, number_format($_exenta, 2, ",", "."), 1, 0, 'R');
		$this->Cell(18, 4, number_format($_gravable, 2, ",", "."), 1, 0, 'R');
		$this->Cell(8, 4, "", 1, 0, 'R');
		$this->Cell(18, 4, number_format($_iva, 2, ",", "."), 1, 0, 'R');
		$this->Cell(18, 4, number_format(0, 2, ",", "."), 1, 0, 'R');
		
		$this->Ln();
		$this->Cell(250, 5, "TOTAL FACTURAS: "  . $items, 0, 0, 'R');


		$this->Ln(5);
		if ($this->GetY() > 151) { 
			//$this->Ln();
			$this->AddPage();
		}

		$this->SetFont('Arial','',5);
		$this->Cell(5, 5);
		$this->Cell(100, 5, "RESUMEN EN CUADRO: COMPRAS", 1, 0, 'C');
		$this->Cell(20, 5, "BASE", 1, 0, 'C');
		$this->Cell(20, 5, "CREDITO", 1, 0, 'C');
		$this->Cell(20, 5, "", 0, 0, 'C');
		$this->Cell(20, 5, "IVA RETENIDO", 1, 0, 'C');
		$this->Ln();
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Compras Exentas y/o sin derecho a credito fiscal"), 1, 0, 'L');
		$this->Cell(20, 5, number_format($_exenta, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Compras Alicuota General 16%"), 1, 0, 'L');
		$this->Cell(20, 5, number_format($_gravable, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format($_iva, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format($_retiva, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Compras Alicuota Adicional"), 1, 0, 'L');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Compras Alicuota Reducida"), 1, 0, 'L');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		$this->Ln();
		$this->Cell(5, 5);
		$this->Cell(120, 5, strtoupper("Total Créditos Fiscales del Mes:"), 1, 0, 'L');
		$this->Cell(20, 5, number_format($_iva, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		require("../include/desconnect.php");
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF('L', 'mm', 'Legal');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

$sql = "SELECT valor1 AS nro_maquina FROM parametro WHERE codigo = '060';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$nro_maquina =  $row["nro_maquina"];

$sql = "SELECT 
			'FC' AS documento, 
			a.tipo_documento, 
			a.estatus, date_format(a.fecha, '%Y-%m-%d') AS fecha_query, 
			date_format(a.fecha, '%d/%m/%Y') AS fecha, 
			SUM(a.`total`) AS total, 
			SUM(a.`iva`) AS iva, 
			CONCAT('Des ', MIN(REPLACE(a.nro_documento, 'FACT-', '')), ' Has ', MAX(REPLACE(a.nro_documento, 'FACT-', ''))) AS nro_documento,  
			CONCAT('Des ', MIN(REPLACE(a.nro_documento, 'FACT-', '')), ' Has ', MAX(REPLACE(a.nro_documento, 'FACT-', ''))) AS nro_documento2,  
			CONCAT('Des ', MIN(REPLACE(a.nro_documento, 'FACT-', '')), ' Has ', MAX(REPLACE(a.nro_documento, 'FACT-', ''))) AS afectado, 
			MIN(a.id) AS idd, MAX(a.id) AS idh 
		FROM 
			salidas AS a 
			LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
		WHERE 
			a.tipo_documento = 'TDCFCV' 
			AND a.fecha BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' 
			AND a.documento = 'FC' 
		GROUP BY a.tipo_documento, date_format(a.fecha, '%d/%m/%Y'), a.estatus, date_format(a.fecha, '%Y-%m-%d') 
		UNION ALL SELECT 
			a.documento, 
			a.tipo_documento, 
			a.estatus, date_format(a.fecha, '%Y-%m-%d') AS fecha_query, 
			date_format(a.fecha, '%d/%m/%Y') AS fecha, 
			a.`total`, 
			a.`iva`, 
			IF(a.documento = 'NC', REPLACE(a.nro_documento, 'NC-', ''), '') AS nro_documento, 
			IF(a.documento = 'ND', REPLACE(a.nro_documento, 'ND-', ''), '') AS nro_documento2, 
			REPLACE(REPLACE(REPLACE(a.doc_afectado, 'ND-', ''), 'NC-', ''), 'FACT-', '') AS afectado, 
			a.id AS idd, a.id AS idh 
		FROM 
			salidas AS a 
			LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
		WHERE 
			a.tipo_documento = 'TDCFCV' 
			AND a.fecha BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' 
			AND a.documento <> 'FC' 
		ORDER BY fecha;"; 
$rs = mysqli_query($link, $sql) or die(mysqli_error());

$items = 0;

$_total = 0.00;
$_exenta = 0.00;
$_gravable = 0.00;
$_iva = 0.00;
$_retiva = 0.00;
$_retislr = 0.00;

while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 5);

	$pdf->Cell(5, 4);
	$pdf->Cell(8, 4, ($items+1), 0, 0, 'C');
	$pdf->Cell(10, 4, $row["fecha"], 0, 0, 'L');
	$ci_rif = "";
	$pdf->Cell(12, 4, (trim($row["estatus"])=="ANULADO" ? "" : $ci_rif ), 0, 0, 'L');
	$cliente = "Resumen Diario de Ventas";
	$pdf->Cell(30, 4, substr((trim($row["estatus"])=="ANULADO" ? "ANULADA" : $cliente), 0, 30), 0, 0, 'L');
	$pdf->Cell(10, 4, "", 0, 0, 'C');
	$pdf->Cell(29, 4, ($row["documento"]=="FC" ? $row["nro_documento"] : ""), 0, 0, 'C');
	$nro_control = $nro_maquina;
	$pdf->Cell(15, 4, $nro_control, 0, 0, 'C');

	
	$sql2 = "SELECT nro_reporte_z FROM reporte_z WHERE fecha = '" . $row["fecha_query"] . "';"; 
	$rs2 = mysqli_query($link, $sql2);
	$row2 = mysqli_fetch_array($rs2);
	$nro_reporte_z =  $row2["nro_reporte_z"] ?? "";

	$pdf->Cell(15, 4, $nro_reporte_z, 0, 0, 'C');
	$pdf->Cell(32, 4, "Des $nro_control Has $nro_control", 0, 0, 'C');
	$pdf->Cell(15, 4, ($row["documento"]=="ND" ? $row["nro_documento2"] : ""), 0, 0, 'C');
	$pdf->Cell(15, 4, ($row["documento"]=="NC" ? $row["nro_documento"] : ""), 0, 0, 'C');
	$pdf->Cell(5, 4, "01", 0, 0, 'C');
	$pdf->Cell(15, 4, ($row["documento"]!="FC" ? $row["afectado"] : ""), 0, 0, 'C');

	$pdf->Cell(15, 4, "", 0, 0, 'C');
	$pdf->Cell(15, 4, "", 0, 0, 'C');


	$pdf->Cell(18, 4, trim($row["estatus"])=="ANULADO" ? "" : ($row["total"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["total"], 2, ",", ".")), 0, 0, 'R');
	$_total += trim($row["estatus"])=="ANULADO" ? 0 : floatval(($row["documento"]=="NC" ? -1 : 1)*$row["total"]);

	$sql = "SELECT 
				SUM(IF(IFNULL(a.alicuota, 0)=0, precio, 0)) AS exenta 
			FROM 
				entradas_salidas AS a 
				JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
			WHERE a.id_documento BETWEEN " . $row["idd"] . " AND " . $row["idh"] . " AND a.tipo_documento = 'TDCFCV';"; 
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error());
	$row2 = mysqli_fetch_array($rs3);
	$pdf->Cell(18, 4, trim($row["estatus"])=="ANULADO" ? "" : ($row2["exenta"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*($row2["exenta"]), 2, ",", ".")), 0, 0, 'R');
	$_exenta += trim($row["estatus"])=="ANULADO" ? 0 : floatval(($row["documento"]=="NC" ? -1 : 1)*($row2["exenta"]));

	if($row["documento"] == "FC") {
		$sql = "SELECT 
					SUM(IF(a.alicuota>0, precio, 0)) AS gravable -- (IF(documento='NC', -1, 1)*precio), 0)) AS gravable 
				FROM 
					entradas_salidas AS a 
					JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
				WHERE a.id_documento BETWEEN " . $row["idd"] . " AND " . $row["idh"] . " AND a.tipo_documento = 'TDCFCV' AND documento='FC';"; 
		$rs3 = mysqli_query($link, $sql) or die(mysqli_error());
		$row2 = mysqli_fetch_array($rs3);
		$pdf->Cell(18, 4, trim($row["estatus"])=="ANULADO" ? "" : (($row2["gravable"]==0) ? "" : number_format($row2["gravable"], 2, ",", ".")), 0, 0, 'R');
	} 
	else {
		$sql = "SELECT 
					SUM(IF(alicuota>0, (IF(documento='NC', -1, 1)*precio), 0)) AS gravable 
				FROM 
					entradas_salidas AS a 
					JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
				WHERE a.id_documento = " . $row["idd"] . " AND a.tipo_documento = 'TDCFCV';"; 
		$rs3 = mysqli_query($link, $sql) or die(mysqli_error());
		$row2 = mysqli_fetch_array($rs3);
		$pdf->Cell(18, 4, trim($row["estatus"])=="ANULADO" ? "" : (($row2["gravable"]==0) ? "" : number_format($row2["gravable"], 2, ",", ".")), 0, 0, 'R');
	}
	$_gravable += trim($row["estatus"])=="ANULADO" ? 0 : $row2["gravable"];

	if($row["documento"] == "FC") {
		$sql = "SELECT 
					MAX(alicuota) AS alicuota_iva 
				FROM 
					entradas_salidas AS a 
					JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
				WHERE a.id_documento BETWEEN " . $row["idd"] . " AND " . $row["idh"] . " AND a.tipo_documento = 'TDCFCV' AND documento='FC';";
		$rs3 = mysqli_query($link, $sql) or die(mysqli_error());
		$row2 = mysqli_fetch_array($rs3);
		$pdf->Cell(8, 4, (trim($row["estatus"])=="ANULADO" ? " " : $row2["alicuota_iva"]==0) ? "" : number_format($row2["alicuota_iva"], 2, ",", "."), 0, 0, 'R');
	} 
	else {
		$sql = "SELECT 
					MAX(alicuota) AS alicuota_iva 
				FROM 
					entradas_salidas AS a 
					JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
				WHERE a.id_documento = " . $row["idd"] . " AND a.tipo_documento = 'TDCFCV';";
		$rs3 = mysqli_query($link, $sql) or die(mysqli_error());
		$row2 = mysqli_fetch_array($rs3);
		$pdf->Cell(8, 4, (trim($row["estatus"])=="ANULADO" ? " " : $row2["alicuota_iva"]==0) ? "" : number_format($row2["alicuota_iva"], 2, ",", "."), 0, 0, 'R');
	}
	$pdf->Cell(18, 4, (trim($row["estatus"])=="ANULADO" ? "" : ($row["iva"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["iva"], 2, ",", "."))), 0, 0, 'R');
	$_iva += trim($row["estatus"])=="ANULADO" ? 0 : floatval(($row["documento"]=="NC" ? -1 : 1)*$row["iva"]);


	/**** RETENCION IVA ***/
	$pdf->Cell(18, 4, number_format(0, 2, ",", "."), 0, 0, 'R');

	$pdf->Ln();

	$_retiva += floatval($row["ret_iva"] ?? 0);
	$_retislr += floatval($row["ret_islr"] ?? 0);
	$items++;
}

$pdf->EndReport($items, $_total, $_exenta, $_gravable, $_iva, $_retiva, $_retislr); 

	
require("../include/desconnect.php");

$pdf->Output();
?>