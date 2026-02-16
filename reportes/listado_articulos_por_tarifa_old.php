<?php
require('rcs/fpdf.php');
require("../connect.php");

$codcliente = trim($_REQUEST["codcliente"]);
$tarifa = trim($_REQUEST["tarifa"]);

if($tarifa == "") {
	$sql = "SELECT tarifa FROM cliente WHERE id = $codcliente"; 
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$tarifa = intval($row["tarifa"]);
}

$sql = "SELECT nombre FROM tarifa WHERE id = $tarifa;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);

$GLOBALS["titulo"] = "ARTICULOS TARIFA " . $row["nombre"];


class PDF extends FPDF
{
	// Cabecera de pαgina
	function Header()
	{
		// Consulto datos de la compaρνa 
		require("../connect.php");
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
		
		$this->Ln(8);
		$this->SetFont('Arial','',8);
		$this->Cell(200, 5, "Fecha: " . date("d/m/Y"),0,0,'R');
		$this->Ln();
		$this->Cell(200, 5, "Hora: " . date("H:i:s"),0,0,'R');

		$this->Ln(10);
		
		$this->SetFont('Arial','B',14);
		$this->Cell(200, 6, utf8_decode($GLOBALS["titulo"]),0,0,'C');
		$this->SetFont('Arial','B',8);


		$this->Ln(5);
		

		require("../desconnect.php");
		$this->Ln(6);

		$this->Cell(10, 6);
		$this->Cell(25, 6, "LABORATORIO", 1, 0, 'L');
		$this->Cell(60, 6, "MEDICAMENTO", 1, 0, 'L');
		$this->Cell(50, 6, "PRESENTACION", 1, 0, 'L');
		$this->Cell(25, 6, "PRECIO", 1, 0, 'R');
		$this->Cell(15, 6, "CANT", 1, 0, 'C');
		$this->Cell(15, 6, "U.M.", 1, 0, 'C');
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
		$this->SetFont('Arial','B',8);
		$this->Ln();
		$this->Cell(200, 5, "TOTAL ARTICULOS: "  . $items, 0, 0, 'R');
	}
}

// Creaciσn del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

$sql = "SELECT 
        a.id, 
        a.foto, a.nombre_comercial, b.nombre AS fabricante, 
        a.principio_activo, a.presentacion, c.precio AS precio, 
        (a.cantidad_en_mano+a.cantidad_en_pedido)-a.cantidad_en_transito AS cantidad_en_mano, 
        d.descripcion AS unidad_medida  
      FROM 
        articulo AS a 
        LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
        INNER JOIN tarifa_articulo AS c ON c.articulo = a.id AND c.tarifa = $tarifa 
        INNER JOIN unidad_medida AS d ON d.codigo = a.unidad_medida_defecto 
      WHERE 
        a.activo = 'S' AND a.articulo_inventario = 'S' AND a.cantidad_en_mano > 0 
      ORDER BY a.principio_activo, a.presentacion;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 8);

	$pdf->Cell(10, 5);
	$pdf->Cell(25, 5, $row["fabricante"], 1, 0, 'L');
	$pdf->Cell(60, 5, substr((trim($row["nombre_comercial"]) == "" ? "" : $row["nombre_comercial"] . " ") . $row["principio_activo"], 0, 30), 1, 0, 'L');
	$pdf->Cell(50, 5, substr($row["presentacion"], 0, 30), 1, 0, 'L');
	$pdf->Cell(25, 5, number_format($row["precio"], 2, ".", ","), 1, 0, 'R');
	$pdf->Cell(15, 5, "", 1, 0, 'C');
	$pdf->Cell(15, 5, $row["unidad_medida"], 1, 0, 'C');
	$pdf->Ln();
	$items++;
}

$pdf->EndReport($items);

	
require("../desconnect.php");

$pdf->Output();
?>