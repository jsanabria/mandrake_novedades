<?php
session_start();
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
require('rcs/fpdf.php');
require("../include/connect.php");


$id = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";
$GLOBALS["recibo"] = $id;

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

		
		
		$sql = "SELECT 
					a.id, a.ci_rif, a.nombre, a.cta_bco, 
					a.email1, a.direccion, b.campo_descripcion AS ciudad, 
					CONCAT(ifnull(a.telefono1,''), ' ', ifnull(a.telefono2,'')) as telf 
				FROM proveedor AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '" . $GLOBALS["proveedor"] . "';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		
		$rif = $row["ci_rif"];
		$razon_social = $row["nombre"];
		$cta_bco = $row["cta_bco"];
		$rif = $row["ci_rif"];
		$direccion_proveedor = $row["direccion"]; 
		$ciudad_proveedor = $row["ciudad"]; 
		$telf = $row["telf"]; 

		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 50);
		}
		
		$this->Ln(15);
		
		$this->SetFont('Arial','',12);
		$this->Cell(200, 6, "ORDEN DE PAGO",0,0,'C');
		


		$this->Ln(8);
		
		$this->SetFont('Arial','',8);
		$this->Cell(10, 5);
		$this->Cell(50, 5, utf8_decode($cia),0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(140, 5, "NRO PAGO: " . $GLOBALS["recibo"],0,0,'R');

		$this->Ln();
		$this->Cell(10, 5);
		$this->SetFont('Arial','B',8);
		$this->Cell(50, 5, "R.I.F: $ci_rif", 0, 0, "L");
		
		$this->SetFont('Arial','',8);
		$this->Cell(100, 5, "CTA. PAGADORA: " . utf8_decode($cia),0,0,'C');
		$this->Cell(40, 5, "FECHA PAGO: " . $GLOBALS["fecha"],0,0,'R');

		$this->Ln();

		$this->Cell(10, 6);
		$this->Cell(40, 6,"PAGUESE A LA ORDE DE: ",'0','0','L');
		$this->Cell(120, 6, utf8_decode($razon_social),'0','0','L');
	

		$this->Ln();
		$this->Cell(10, 6);
		$this->Cell(40, 6,'A CUENTA NUMERO: ','0','0','L');
		$this->Cell(150, 5, "$cta_bco", '0', '0', 'L');

		$this->Ln();
		$this->Cell(10, 6);
		$this->Cell(70,6,'R.I.F.: ' . $rif,'0',0,'L');
		$this->Ln();

		require("../include/desconnect.php");

		$this->Cell(10, 6);
		$this->Cell(20, 6, "MAYOR", 1, 0, 'L');
		$this->Cell(20, 6, "AUXILIAR", 1, 0, 'L');
		$this->Cell(20, 6, "DOC.", 1, 0, 'L');
		$this->Cell(65, 6, "DESCRIPCION", 1, 0, 'L');
		$this->Cell(20, 6, "BASE IMPON", 1, 0, 'r');
		$this->Cell(10, 6, "RET.%", 1, 0, 'R');
		$this->Cell(20, 6, "DEBITOS", 1, 0, 'R');
		$this->Cell(20, 6, "CREDITOS", 1, 0, 'R');
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
		$this->Ln();
		$this->Cell(200, 6, "TOTAL ITEMS: "  . $items, 0, 0, 'R');
		//require("../desconnect.php");
	}
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();

$sql = "SELECT date_format(fecha, '%d/%m/%Y') AS fecha FROM cont_lotes_pagos WHERE id = $id;"; 
$rs = mysqli_query($link, $sql) or die(mysqli_error());
$row = mysqli_fetch_array($rs);
$GLOBALS["fecha"] = $row["fecha"];


$proveedores = 0;
$items = 0;
$sql = "SELECT 
			DISTINCT b.id, b.nombre, b.cta_bco   
		FROM 
			cont_lotes_pagos_detalle AS a JOIN proveedor AS b ON b.id = a.proveedor 
		WHERE a.cont_lotes_pago = $id ORDER BY b.nombre ASC;"; 
$rs = mysqli_query($link, $sql) or die(mysqli_error());
while($row = mysqli_fetch_array($rs)) {
	$GLOBALS["proveedor"] = $row["id"];
	$proveedores++;

	$pdf->AddPage();

	$sql = "SELECT 
				a.Id, 
				IF(a.monto_a_pagar=a.saldo, 'NO', 'SI') AS abono, 
				IF(a.monto_a_pagar=a.saldo, 'SI', IF(a.monto_a_pagar=a.monto_pagado, 'SI', 'NO')) AS pago_completo 
			FROM 
				cont_lotes_pagos_detalle AS a 
			WHERE a.cont_lotes_pago = $id AND a.proveedor = " . $GLOBALS["proveedor"] . ";";
	$rs2 = mysqli_query($link, $sql) or die(mysqli_error());
	$debitos = 0;
	$creditos = 0;
	while($row2 = mysqli_fetch_array($rs2)) {
		$id_det_lote = $row2["Id"];
		$abono = $row2["abono"];
		$pago_completo = $row2["pago_completo"];
		$sql = "SELECT 
					'' AS mayor, a.proveedor AS auxiliar,  b.referencia, 
					LTRIM(CONCAT(IFNULL(a.tipodoc, ''), ' ', IFNULL(a.nro_documento, ''))) AS documento, 
					d.nombre AS nombre_proveedor, 
					IFNULL(c.total, 0) AS total, 
					IFNULL(c.ret_iva, 0) AS ret_iva, c.tipo_iva, 
					IFNULL(c.ret_islr, 0) AS ret_islr, c.tipo_islr, c.sustraendo,  
					IFNULL(c.ret_municipal, 0) AS ret_municipal, c.tipo_municipal, 
					IFNULL(a.monto_a_pagar, 0) AS monto_a_pagar, IFNULL(a.monto_pagado, 0) AS monto_pagado, IFNULL(a.saldo, 0) AS saldo, 
					c.gravado, (SELECT banco FROM compania_cuenta WHERE id = b.banco) AS banco   
				FROM 
					cont_lotes_pagos_detalle AS a 
					JOIN cont_lotes_pagos AS b ON b.id = a.cont_lotes_pago 
					JOIN view_x_pagar AS c ON c.tipo_documento = a.tipo_documento AND c.id = a.id_documento AND c.proveedor = a.proveedor 
					JOIN proveedor AS d ON d.id = a.proveedor  
				WHERE a.Id = " . $row2["Id"] . ";"; 
		$rs3 = mysqli_query($link, $sql) or die(mysqli_error());
		$row3 = mysqli_fetch_array($rs3);
		if($abono == "NO") {
			$pdf->Cell(10, 4);
			$pdf->Cell(20, 4, $row3["mayor"], 0, 0, 'L');
			$pdf->Cell(20, 4, $row3["auxiliar"], 0, 0, 'L');
			$pdf->Cell(20, 4, $row3["documento"], 0, 0, 'L');
			$pdf->Cell(65, 4, substr(utf8_decode($row3["documento"] . " " . $row3["nombre_proveedor"]), 0, 40), 0, 0, 'L');
			$pdf->Cell(20, 4, "", 0, 0, 'r');
			$pdf->Cell(10, 4, "", 0, 0, 'R');
			$pdf->Cell(20, 4, number_format($row3["total"], 2, ".", ","), 0, 0, 'R');
			$pdf->Cell(20, 4, "", 0, 0, 'R');
			$pdf->Ln(4);
			$debitos += floatval($row3["total"]);

			if($row3["ret_iva"] > 0) {
				$pdf->Cell(10, 4);
				$pdf->Cell(20, 4, $row3["mayor"], 0, 0, 'L');
				$pdf->Cell(20, 4, $row3["auxiliar"], 0, 0, 'L');
				$pdf->Cell(20, 4, $row3["documento"], 0, 0, 'L');
				$pdf->Cell(65, 4, "RET. IVA. " . $row3["tipo_iva"] . "%", 0, 0, 'L');
				$pdf->Cell(20, 4, number_format($row3["gravado"], 2, ".", ","), 0, 0, 'r');
				$pdf->Cell(10, 4, $row3["tipo_iva"] . "%", 0, 0, 'R');
				$pdf->Cell(20, 4, "", 0, 0, 'R');
				$pdf->Cell(20, 4, number_format($row3["ret_iva"], 2, ".", ","), 0, 0, 'R');
				$pdf->Ln(4);
				$creditos += floatval($row3["ret_iva"]);
			}

			if($row3["ret_municipal"] > 0) {
				$pdf->Cell(10, 4);
				$pdf->Cell(20, 4, $row3["mayor"], 0, 0, 'L');
				$pdf->Cell(20, 4, $row3["auxiliar"], 0, 0, 'L');
				$pdf->Cell(20, 4, $row3["documento"], 0, 0, 'L');
				$pdf->Cell(65, 4, "RET. MUN. " . $row3["tipo_municipal"] . "%", 0, 0, 'L');
				$pdf->Cell(20, 4, number_format($row3["gravado"], 2, ".", ","), 0, 0, 'r');
				$pdf->Cell(10, 4, $row3["tipo_iva"] . "%", 0, 0, 'R');
				$pdf->Cell(20, 4, "", 0, 0, 'R');
				$pdf->Cell(20, 4, number_format($row3["ret_municipal"], 2, ".", ","), 0, 0, 'R');
				$pdf->Ln(4);
				$creditos += floatval($row3["ret_municipal"]);
			}

			if($row3["ret_islr"] > 0) {
				$pdf->Cell(10, 4);
				$pdf->Cell(20, 4, $row3["mayor"], 0, 0, 'L');
				$pdf->Cell(20, 4, $row3["auxiliar"], 0, 0, 'L');
				$pdf->Cell(20, 4, $row3["documento"], 0, 0, 'L');
				$pdf->Cell(65, 4, "RET. ISLR. " . $row3["tipo_islr"] . "% " . (floatval($row3["sustraendo"])>0.00 ? "Sustraendo: " . number_format(floatval($row3["sustraendo"]),2 ,".", ",") : ""), 0, 0, 'L');
				$pdf->Cell(20, 4, number_format($row3["gravado"], 2, ".", ","), 0, 0, 'r');
				$pdf->Cell(10, 4, $row3["tipo_islr"] . "%", 0, 0, 'R');
				$pdf->Cell(20, 4, "", 0, 0, 'R');
				$pdf->Cell(20, 4, number_format($row3["ret_islr"], 2, ".", ","), 0, 0, 'R');
				$pdf->Ln(4);
				$creditos += floatval($row3["ret_islr"]);
			}

			$pdf->Cell(10, 4);
			$pdf->Cell(20, 4, $row3["mayor"], 0, 0, 'L');
			$pdf->Cell(20, 4, $row3["banco"], 0, 0, 'L');
			$pdf->Cell(20, 4, "PE " . $row3["referencia"], 0, 0, 'L');
			$pdf->Cell(65, 4, $row3["nombre_proveedor"], 0, 0, 'L');
			$pdf->Cell(20, 4, "", 0, 0, 'r');
			$pdf->Cell(10, 4, "", 0, 0, 'R');
			$pdf->Cell(20, 4, "", 0, 0, 'R');
			$pdf->Cell(20, 4, number_format($row3["monto_a_pagar"], 2, ".", ","), 0, 0, 'R');
			$pdf->Ln(4);
			$creditos += floatval($row3["monto_a_pagar"]);
		} 
		else {
			if($pago_completo == "SI") {
				$pdf->Cell(10, 4);
				$pdf->Cell(20, 4, $row3["mayor"], 0, 0, 'L');
				$pdf->Cell(20, 4, $row3["auxiliar"], 0, 0, 'L');
				$pdf->Cell(20, 4, $row3["documento"], 0, 0, 'L');
				$pdf->Cell(65, 4, substr(utf8_decode($row3["documento"] . " " . $row3["nombre_proveedor"]), 0, 40), 0, 0, 'L');
				$pdf->Cell(20, 4, "", 0, 0, 'r');
				$pdf->Cell(10, 4, "", 0, 0, 'R');
				$pdf->Cell(20, 4, number_format($row3["total"], 2, ".", ","), 0, 0, 'R');
				$pdf->Cell(20, 4, "", 0, 0, 'R');
				$pdf->Ln(4);
				$debitos += floatval($row3["total"]);

				if($row3["ret_iva"] > 0) {
					$pdf->Cell(10, 4);
					$pdf->Cell(20, 4, $row3["mayor"], 0, 0, 'L');
					$pdf->Cell(20, 4, $row3["auxiliar"], 0, 0, 'L');
					$pdf->Cell(20, 4, $row3["documento"], 0, 0, 'L');
					$pdf->Cell(65, 4, "RET. IVA. " . $row3["tipo_iva"] . "%", 0, 0, 'L');
					$pdf->Cell(20, 4, number_format($row3["gravado"], 2, ".", ","), 0, 0, 'r');
					$pdf->Cell(10, 4, $row3["tipo_iva"] . "%", 0, 0, 'R');
					$pdf->Cell(20, 4, "", 0, 0, 'R');
					$pdf->Cell(20, 4, number_format($row3["ret_iva"], 2, ".", ","), 0, 0, 'R');
					$pdf->Ln(4);
					$creditos += floatval($row3["ret_iva"]);
				}

				if($row3["ret_municipal"] > 0) {
					$pdf->Cell(10, 4);
					$pdf->Cell(20, 4, $row3["mayor"], 0, 0, 'L');
					$pdf->Cell(20, 4, $row3["auxiliar"], 0, 0, 'L');
					$pdf->Cell(20, 4, $row3["documento"], 0, 0, 'L');
					$pdf->Cell(65, 4, "RET. MUN. " . $row3["tipo_municipal"] . "%", 0, 0, 'L');
					$pdf->Cell(20, 4, number_format($row3["gravado"], 2, ".", ","), 0, 0, 'r');
					$pdf->Cell(10, 4, $row3["tipo_iva"] . "%", 0, 0, 'R');
					$pdf->Cell(20, 4, "", 0, 0, 'R');
					$pdf->Cell(20, 4, number_format($row3["ret_municipal"], 2, ".", ","), 0, 0, 'R');
					$pdf->Ln(4);
					$creditos += floatval($row3["ret_municipal"]);
				}

				if($row3["ret_islr"] > 0) {
					$pdf->Cell(10, 4);
					$pdf->Cell(20, 4, $row3["mayor"], 0, 0, 'L');
					$pdf->Cell(20, 4, $row3["auxiliar"], 0, 0, 'L');
					$pdf->Cell(20, 4, $row3["documento"], 0, 0, 'L');
					$pdf->Cell(65, 4, "RET. ISLR. " . $row3["tipo_islr"] . "%", 0, 0, 'L');
					$pdf->Cell(20, 4, number_format($row3["gravado"], 2, ".", ","), 0, 0, 'r');
					$pdf->Cell(10, 4, $row3["tipo_islr"] . "%", 0, 0, 'R');
					$pdf->Cell(20, 4, "", 0, 0, 'R');
					$pdf->Cell(20, 4, number_format($row3["ret_islr"], 2, ".", ","), 0, 0, 'R');
					$pdf->Ln(4);
					$creditos += floatval($row3["ret_islr"]);
				}

				$pdf->Cell(10, 4);
				$pdf->Cell(20, 4, $row3["mayor"], 0, 0, 'L');
				$pdf->Cell(20, 4, $row3["banco"], 0, 0, 'L');
				$pdf->Cell(20, 4, "PE " . $row3["referencia"], 0, 0, 'L');
				$pdf->Cell(65, 4, $row3["nombre_proveedor"], 0, 0, 'L');
				$pdf->Cell(20, 4, "", 0, 0, 'r');
				$pdf->Cell(10, 4, "", 0, 0, 'R');
				$pdf->Cell(20, 4, "", 0, 0, 'R');
				$pdf->Cell(20, 4, number_format($row3["monto_a_pagar"], 2, ".", ","), 0, 0, 'R');
				$pdf->Ln(4);
				$creditos += floatval($row3["monto_a_pagar"]);
			} 
			else {
				$pdf->Cell(10, 4);
				$pdf->Cell(20, 4, $row3["mayor"], 0, 0, 'L');
				$pdf->Cell(20, 4, $row3["auxiliar"], 0, 0, 'L');
				$pdf->Cell(20, 4, $row3["documento"], 0, 0, 'L');
				$pdf->Cell(65, 4, substr(utf8_decode($row3["documento"] . " " . $row3["nombre_proveedor"]), 0, 40), 0, 0, 'L');
				$pdf->Cell(20, 4, "", 0, 0, 'r');
				$pdf->Cell(10, 4, "", 0, 0, 'R');
				$pdf->Cell(20, 4, number_format($row3["saldo"], 2, ".", ","), 0, 0, 'R');
				$pdf->Cell(20, 4, "", 0, 0, 'R');
				$pdf->Ln(4);
				$debitos += floatval($row3["total"]);

				$pdf->Cell(10, 4);
				$pdf->Cell(20, 4, $row3["mayor"], 0, 0, 'L');
				$pdf->Cell(20, 4, $row3["banco"], 0, 0, 'L');
				$pdf->Cell(20, 4, "PE " . $row3["referencia"], 0, 0, 'L');
				$pdf->Cell(65, 4, $row3["nombre_proveedor"], 0, 0, 'L');
				$pdf->Cell(20, 4, "", 0, 0, 'r');
				$pdf->Cell(10, 4, "", 0, 0, 'R');
				$pdf->Cell(20, 4, "", 0, 0, 'R');
				$pdf->Cell(20, 4, number_format($row3["saldo"], 2, ".", ","), 0, 0, 'R');
				$pdf->Ln(4);
				$creditos += floatval($row3["monto_a_pagar"]);
			}
		}
		$items++;
	}
	$pdf->Ln(4);
	$pdf->Cell(165, 4);
	$pdf->Cell(20, 4, number_format($debitos, 2, ".", ","), "TB", 0, 'R');
	$pdf->Cell(20, 4, number_format($creditos, 2, ".", ","), "TB", 0, 'R');
	$pdf->Ln(4);
	$creditos += floatval($row3["monto_a_pagar"]);
}

$pdf->SetFont('Arial','',8);

$pdf->EndReport($items);

	
require("../include/desconnect.php");

$pdf->Output();
?>