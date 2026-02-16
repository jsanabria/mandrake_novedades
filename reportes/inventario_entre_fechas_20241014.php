<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect.php");


$fecha_hasta = isset($_REQUEST["fecha"])?$_REQUEST["fecha"]:"0";

$xF = explode("-", $fecha_hasta);

$GLOBALS["titulo"] = "INVENTARIO ENTRE FECHA AL " . $xF[2] . "/" . $xF[1] . "/" . $xF[0];

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
		$this->Cell(20, 6, "COD.", 1, 0, 'L');
		$this->Cell(90, 6, "ARTICULO", 1, 0, 'L');
		$this->Cell(10, 6, "ENT", 1, 0, 'R');
		$this->Cell(10, 6, "SAL", 1, 0, 'R');
		$this->Cell(10, 6, "EXI", 1, 0, 'R');
		$this->Cell(15, 6, "COSTO U", 1, 0, 'R');
		$this->Cell(15, 6, "PRECIO U", 1, 0, 'R');
		$this->Cell(20, 6, "COSTO E", 1, 0, 'R');
		$this->Ln(6);

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
	
	function EndReport($items, $unidades, $totalcostos)
	{
		//$this->AddPage();
		//require("../connect.php");
		$this->SetFont('Arial', 'BI', 8);
		$this->Ln();
		$this->Cell(200, 6, "TOTAL ITEMS: "  . $items .  " - TOTAL UNIDADES: "  . $unidades .  " - TOTAL COSTO: "  . number_format($totalcostos, 2, ",", "."), 0, 0, 'R');
		$this->SetFont('Arial', '', 8);
		//require("../desconnect.php");
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);


$sql = "SELECT 
					art.id, 
					art.codigo_ims AS CODIGO, art.principio_activo AS ARTICULO, 
					'UNIDAD' AS UNIDAD_MEDIDA, 
					IFNULL(dev.cantidad, 0) AS devoluciones, 
					ent.cantidad AS entradas, ABS(sal.cantidad) AS salidas, 
					(ent.cantidad - ABS(sal.cantidad)) AS existencia2, 
					(IFNULL(ent.cantidad, 0) - ABS(IFNULL(sal.cantidad, 0))) AS existencia  
			FROM 
				(
					SELECT 
						a.id, a.codigo, a.codigo_ims, b.nombre, 
						'UNIDAD' AS unidad_medida, a.principio_activo, 
						a.presentacion, a.nombre_comercial 
					FROM 
						articulo AS a 
						LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante  
					WHERE 
						1 
				) AS art 
				LEFT OUTER JOIN 
				(
					SELECT 
						a.articulo, SUM(a.cantidad_movimiento) AS cantidad  
					FROM 
						entradas_salidas AS a 
						JOIN salidas AS b ON
							b.tipo_documento = a.tipo_documento
							AND b.id = a.id_documento 
						JOIN almacen AS c ON
							c.codigo = a.almacen AND c.movimiento = 'S' 
					WHERE
						a.tipo_documento IN ('TDCNET', 'TDCASA') 
						AND b.estatus <> 'ANULADO' AND b.activo = 'S' AND 
						b.fecha < '$fecha_hasta 23:59:59' 
					GROUP BY a.articulo
				) AS sal ON sal.articulo = art.Id 
				LEFT OUTER JOIN 
				(
					SELECT 
						a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
					FROM 
						entradas_salidas AS a 
						JOIN entradas AS b ON
							b.tipo_documento = a.tipo_documento
							AND b.id = a.id_documento 
						JOIN almacen AS c ON
							c.codigo = a.almacen AND c.movimiento = 'S'
					WHERE
						((a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
						AND b.estatus = 'PROCESADO') OR 
						(a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
						AND b.estatus <> 'ANULADO') AND b.consignacion = 'S') AND 
						b.fecha < '$fecha_hasta 23:59:59' AND IFNULL(b.nota, '') <> 'DEVOLUCION DE ARTICULO' AND IFNULL(cliente, 0) = 0 
					GROUP BY a.articulo
				) AS ent ON ent.articulo = art.Id 
				LEFT OUTER JOIN 
				(
					SELECT 
						a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
					FROM 
						entradas_salidas AS a 
						JOIN entradas AS b ON
							b.tipo_documento = a.tipo_documento
							AND b.id = a.id_documento 
						JOIN almacen AS c ON
							c.codigo = a.almacen AND c.movimiento = 'S'
					WHERE
						((a.tipo_documento IN ('TDCNRP') 
						AND b.estatus = 'PROCESADO') OR 
						(a.tipo_documento IN ('TDCNRP') 
						AND b.estatus <> 'ANULADO') AND b.consignacion = 'S') AND 
						b.fecha < '$fecha_hasta 23:59:59' AND IFNULL(b.nota, '') = 'DEVOLUCION DE ARTICULO' 
					GROUP BY a.articulo
				) AS dev ON dev.articulo = art.Id 
			ORDER BY art.codigo_ims ASC;";  // die($sql);

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$unidades = 0;
$totalcostos = 0.00;
while($row = mysqli_fetch_array($rs))
{
	$idArt = $row["id"];
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(10, 4);
	$pdf->Cell(20, 4, substr($row["CODIGO"], 0, 8), 0, 0, 'L');
	$existencia = $row["existencia"]; // $row["existencia"]==0 ? $row["devoluciones"]+$row["entradas"]-$row["salidas"] : $row["devoluciones"]+$row["existencia"];
	if(strlen(utf8_encode($row["ARTICULO"])) < 45) 
		$pdf->Cell(90, 4, utf8_encode($row["ARTICULO"]), 0, 0, 'L');
	else 
		$pdf->Cell(90, 4, substr(utf8_encode($row["ARTICULO"]), 0, 45), 0, 0, 'L');
	$pdf->Cell(10, 4, intval($row["devoluciones"]+$row["entradas"]), 0, 0, 'R');
	$pdf->Cell(10, 4, intval($row["salidas"]), 0, 0, 'R');
	// $pdf->Cell(10, 4, intval($row["existencia"]), 0, 0, 'R');
	$pdf->Cell(10, 4, intval($existencia), 0, 0, 'R');

	$sql = "SELECT ultimo_costo, precio FROM articulo WHERE id = $idArt";
	$rs2 = mysqli_query($link, $sql);
	$row2 = mysqli_fetch_array($rs2);
	$costo = $row2["ultimo_costo"];
	$precio = $row2["precio"];
	$pdf->Cell(15, 4, number_format($costo, 2, ",","."), 0, 0, 'R');
	$pdf->Cell(15, 4, number_format($precio, 2, ",","."), 0, 0, 'R');
	// $pdf->Cell(20, 4, number_format($costo * $row["existencia"], 2, ",","."), 0, 0, 'R');
	$pdf->Cell(20, 4, number_format($costo * $existencia, 2, ",","."), 0, 0, 'R');

	if(strlen(utf8_encode($row["ARTICULO"])) >= 45) {
		$pdf->Ln();
		$pdf->Cell(30, 4);
		$pdf->MultiCell(130, 4, substr(utf8_encode($row["ARTICULO"]), 45, strlen(utf8_encode($row["ARTICULO"]))), 0, 'L');
	}
	else $pdf->Ln();

	//if($pdf->GetY() > 250) $pdf->AddPage();
	//$unidades += intval($row["existencia"]);
	$totalcostos += floatval($costo * $row["existencia"]);
	$unidades += $row["existencia"]; // $row["existencia"]==0 ? $row["devoluciones"]+$row["entradas"]-$row["salidas"] : $row["devoluciones"]+$row["existencia"];
	$items++;
}

$pdf->EndReport($items, $unidades, $totalcostos);

	
require("../include/desconnect.php");

$pdf->Output();
?>