<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect.php");

$fecha_desde = isset($_REQUEST["fecha_desde"])?$_REQUEST["fecha_desde"]:"0";
$xFD = explode("-", $fecha_desde);
$fecha_hasta = isset($_REQUEST["fecha_hasta"])?$_REQUEST["fecha_hasta"]:"0";
$xFH = explode("-", $fecha_hasta);

$GLOBALS["titulo"] = "CANJES POR ARTICULO DESDE " . $xFD[2] . "/" . $xFD[1] . "/" . $xFD[0] . " HASTA " . $xFH[2] . "/" . $xFH[1] . "/" . $xFH[0];

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
		
		$this->Ln(25);
		
		$this->SetFont('Arial','',12);
		$this->Cell(200, 6, $GLOBALS["titulo"],0,0,'C');
		


		$this->Ln(8);
		
		$this->SetFont('Arial','',8);
		$this->Cell(10, 5);
		$this->Cell(50, 5, utf8_decode($cia),0,0,'L');

		$this->Ln(8);
		$this->Cell(10, 5);
		$this->SetFont('Arial','B',8);
		$this->Cell(150, 5, "CIUDAD: $ciudad",0,0,'R');
		$this->Cell(40, 5, "FECHA: " . date("d/m/Y"),0,0,'R');


		require("../include/desconnect.php");

		$this->Ln();
		$this->Cell(10, 6);
		$this->Cell(15, 6, "COD.", 1, 0, 'L');
		$this->Cell(80, 6, "ARTICULO", 1, 0, 'L');
		$this->Cell(10, 6, "CANT", 1, 0, 'R');
		$this->Cell(15, 6, "COSTO U", 1, 0, 'R');
		$this->Cell(20, 6, "COSTO T", 1, 0, 'R');
		$this->Cell(15, 6, "PRECIO U", 1, 0, 'R');
		$this->Cell(20, 6, "PRECIO T", 1, 0, 'R');
		$this->Cell(15, 6, "UTIL %", 1, 0, 'R');
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
	
	function EndReport($items, $unidades, $totalcostos, $totalprecios)
	{
		//$this->AddPage();
		//require("../connect.php");
		$this->SetFont('Arial', 'BI', 8);
		$this->Ln();
		$this->Cell(50, 4);
		$this->Cell(50, 6, "TOTAL ITEMS: "  . $items .  " - TOTAL UNIDADES: "  . $unidades, 0, 0, 'R');
		$this->Cell(50, 6, number_format($totalcostos, 2, ",", "."), 0, 0, 'R');
		$this->Cell(35, 6, number_format($totalprecios, 2, ",", "."), 0, 0, 'R');
		$this->Cell(15, 6, number_format((($totalprecios-$totalcostos)/$totalprecios)*100, 2, ",", "."), 0, 0, 'R');

		$this->Ln();
		$this->Cell(50, 4);
		$this->Cell(125, 6, "Utilidad: " . number_format($totalprecios-$totalcostos, 2, ",", "."), 0, 0, 'R');
		$this->SetFont('Arial', '', 8);
		//require("../desconnect.php");
	}
}

// Creaci?n del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);


$sql = "SELECT 
					d.id,
					d.codigo_ims AS CODIGO,
					CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')) AS ARTICULO, 
					SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento, 
					d.ultimo_costo AS costo_unidad, SUM(b.cantidad_articulo*d.ultimo_costo) AS costo, 
					b.precio_unidad-(b.precio_unidad*(IFNULL(a.descuento, 0)/100)) AS precio_unidad, 
					SUM(IFNULL(b.precio, 0)-(IFNULL(b.precio, 0)*(IFNULL(a.descuento, 0)/100))) AS precio, 
					(((IFNULL(b.precio_unidad, 0)-(IFNULL(b.precio_unidad, 0)*(IFNULL(a.descuento, 0)/100)))-IFNULL(d.ultimo_costo, 0))/(IFNULL(b.precio_unidad, 0)-(IFNULL(b.precio_unidad, 0)*(IFNULL(a.descuento, 0)/100))))*100 AS utilidad 
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.id_documento = a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					b.tipo_documento IN ('TDCNET') AND a.estatus = 'PROCESADO' AND IFNULL(a.pago_premio, 'N') = 'S' 
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
				GROUP BY d.id, CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')), d.ultimo_costo, b.precio_unidad, a.descuento     
				ORDER BY d.codigo_ims ASC;"; 
$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$unidades = 0;
$totalcostos = 0.00;
$totalprecios = 0.00;
while($row = mysqli_fetch_array($rs))
{
	$idArt = $row["id"];
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(10, 4);
	$pdf->Cell(15, 4, substr($row["CODIGO"], 0, 8), 0, 0, 'L');
	if(strlen(utf8_encode($row["ARTICULO"])) < 45) 
		$pdf->Cell(80, 4, utf8_encode($row["ARTICULO"]), 0, 0, 'L');
	else 
		$pdf->Cell(80, 4, substr(utf8_encode($row["ARTICULO"]), 0, 45), 0, 0, 'L');
	$pdf->Cell(10, 4, intval($row["cantidad_movimiento"]), 0, 0, 'R');
	$pdf->Cell(15, 4, number_format(floatval($row["costo_unidad"]), 2, ",","."), 0, 0, 'R');
	$pdf->Cell(20, 4, number_format(floatval($row["costo"]), 2, ",","."), 0, 0, 'R');

	$pdf->Cell(15, 4, number_format(floatval($row["precio_unidad"]), 2, ",","."), 0, 0, 'R');
	$pdf->Cell(20, 4, number_format(floatval($row["precio"]), 2, ",","."), 0, 0, 'R');
	$pdf->Cell(15, 4, number_format(floatval($row["utilidad"]), 2, ",","."), 0, 0, 'R');

	if(strlen(utf8_encode($row["ARTICULO"])) >= 45) {
		$pdf->Ln();
		$pdf->Cell(25, 4);
		$pdf->MultiCell(130, 4, substr(utf8_encode($row["ARTICULO"]), 45, strlen(utf8_encode($row["ARTICULO"]))), 0, 'L');
	}
	else $pdf->Ln();

	//if($pdf->GetY() > 250) $pdf->AddPage();
	$unidades += intval($row["cantidad_movimiento"]);
	$totalcostos += floatval($row["costo"]);
	$totalprecios += floatval($row["precio"]);
	$items++;
}

$pdf->EndReport($items, $unidades, $totalcostos, $totalprecios);

	
require("../include/desconnect.php");

$pdf->Output();
?>