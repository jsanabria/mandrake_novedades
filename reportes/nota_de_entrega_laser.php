<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect.php");

$id = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";

$sql = "SELECT 
			id, date_format(fecha, '%d/%m/%Y') as fecha, cliente, nro_documento, tipo_documento, estatus 
		FROM salidas where id = '$id'";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["cliente"] = $row["cliente"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus"] = $row["estatus"];
$GLOBALS["direccion_cia"] = "";


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
		$GLOBALS["direccion_cia"] = $direccion;
		$cia =  $row["nombre"];
		$logo =  $row["logo"];
		$ci_rif = $row["ci_rif"];

		
		
		$sql = "SELECT 
					a.id, a.ci_rif, a.nombre, 
					a.email1, a.direccion, b.campo_descripcion AS ciudad, 
					CONCAT(ifnull(a.telefono1,''), ' ', ifnull(a.telefono2,'')) as telf 
				FROM cliente AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '" . $GLOBALS["cliente"] . "';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		
		$rif = $row["ci_rif"];
		$razon_social = $row["nombre"];
		$rif = $row["ci_rif"];
		$direccion_cliente = $row["direccion"]; 
		$ciudad_cliente = $row["ciudad"]; 
		$telf = $row["telf"]; 

		$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '" . $GLOBALS["tipo_documento"] . "';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 50);
		}
		
		$this->Ln(15);
		
		$this->SetFont('Arial','',12);
		$this->Cell(200, 6, $row["descripcion"],0,0,'C');
		


		$this->Ln(8);
		
		$this->SetFont('Arial','B',8);
		
		$this->Cell(10, 5);
		$this->Cell(50, 5, utf8_decode($cia),0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(140, 5, "ESTATUS: " . $GLOBALS["estatus"] . " / " . "No. Doc.: " . $GLOBALS["nro_documento"],0,0,'R');

		$this->Ln();
		$this->Cell(10, 5);
		$this->SetFont('Arial','B',8);
		$this->Cell(100, 5, "R.I.F: $ci_rif", 0, 0, "L");
		$this->SetFont('Arial','',8);
		$this->Cell(50, 5, "CIUDAD: $ciudad",0,0,'R');
		$this->Cell(40, 5, "FECHA: " . $GLOBALS["fecha"],0,0,'R');

		$this->Ln();

		$this->Cell(10, 5);
		$this->Cell(30, 5,"CLIENTE: ",'0','0','L');
		$this->Cell(130, 5, $razon_social,'0','0','L');
	

		$this->Ln();
		$this->Cell(10, 5);
		$this->Cell(30, 5,'DIRECCION: ','0','0','L');
		$this->MultiCell(160, 5, "$direccion_cliente. $ciudad_cliente", '0', 'L');

		$this->Cell(10, 5);
		$this->Cell(70,5,'R.I.F.: ' . $rif,'0',0,'L');
		$this->Cell(80,5,'Telf:'.  $telf,'0','0','L');
		$this->Cell(50,5,'CONTADO','0',0,'C');

		require("../include/desconnect.php");
		$this->Ln(6);

		$this->Cell(10, 6);
		$this->Cell(20, 6, "CODIGO", 1, 0, 'L');
		$this->Cell(105, 6, "ARTICULO", 1, 0, 'L');
		$this->Cell(15, 6, "CANT", 1, 0, 'C');
		$this->Cell(25, 6, "PRECIO", 1, 0, 'R');
		$this->Cell(25, 6, "TOTAL", 1, 0, 'R');
		$this->Ln(6);
	}
	
	// Pie de página
	function Footer()
	{
		// Posición: a 1,5 cm del final
		//////$this->SetY(-15);
		// Arial italic 8
		//////$this->SetFont('Arial','I',8);
		// Número de página
		//////$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($id_invoice)
	{
		//$this->AddPage();
		//require("../connect.php");
		//////$this->Ln();
		//////$this->Cell(200, 6, "TOTAL ITEMS: "  . $items, 0, 0, 'R');
		//require("../include/desconnect.php");

		require("../include/connect.php");
		$doc = "";

		$sql = "SELECT 
					a.alicuota_iva, 
					a.iva,
					a.monto_total, 
					a.total, 
					a.nota, a.doc_afectado,  
					a.moneda, 
					a.username, a.id_documento_padre, 
					a.monto_usd, IFNULL(a.tasa_dia, 0) AS tasa_dia, a.descuento, a.monto_sin_descuento, a.unidades, 
					a.nro_despacho  
				FROM salidas a where a.id = '$id_invoice'"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$alicuota = $row["alicuota_iva"];
		$nota = utf8_decode($row["nota"]);
		$moneda = utf8_decode($row["moneda"]);
		$username = utf8_decode($row["username"]);
		$monto_total = $row["monto_total"];
		$monto_sin_descuento = $row["monto_sin_descuento"];

		$monto_usd = $row["monto_usd"];
		$tasa_dia = $row["tasa_dia"];
		if($tasa_dia == 0) $tasa_dia = 1;

		$descuento = floatval($row["descuento"]);

		$unidades = $row["unidades"];
		$nro_despacho = $row["nro_despacho"];

		$sql = "SELECT
					SUM(precio) AS precio, 
					SUM(IF(IFNULL(alicuota,0)=0, precio, 0)) AS exento, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio)) AS gravado,
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) AS exento_2, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) AS gravado_2, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100))) AS total 
				FROM entradas_salidas
				WHERE tipo_documento = '" . $GLOBALS["tipo_documento"] . "' AND 
					id_documento = '$id_invoice'"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		$this->Ln(100-$this->GetY());

		$this->SetFont('Arial','B',8);
		$this->Cell(175, 4, "SUB-TOTAL EXENTO:", 0, 0, 'R');
		$this->SetFont('Arial','',8);
		$this->Cell(25, 4, number_format($row["exento"], 2, ",", "."), 0, 0, 'R');
		//$this->Cell(25, 4, number_format($row["exento"]/$tasa_dia, 2, ",", "."), 0, 0, 'R');
		$this->Ln(5);

		$this->SetFont('Arial','B',8);
		$this->Cell(175,4, "SUB-TOTAL GRAVADO:", 0, 0, 'R');
		$this->SetFont('Arial','',8);
		$this->Cell(25, 4, number_format($row["gravado"], 2, ",", "."), 0, 0, 'R');
		// $this->Cell(25, 4, number_format($row["gravado"]/$tasa_dia, 2, ",", "."), 0, 0, 'R');
		$this->Ln(5);

		// Se imprime el descuento si aplica
		if($descuento > 0) {
			$this->SetFont('Arial','BI',10);
			$this->Cell(101,4, "", 0, 0, 'R');
			$this->SetFont('Arial','B',8);
			$this->Cell(175,4, "Descuento " . number_format($descuento, 2, ",", ".") . "%:", 0, 0, 'R');
			$this->SetFont('Arial','',8);
			$this->Cell(25, 4, number_format($monto_total-$monto_sin_descuento, 2, ",", "."), 0, 0, 'R');
			// $this->Cell(25, 4, number_format(($monto_total-$monto_sin_descuento)/$tasa_dia, 2, ",", "."), 0, 0, 'R');
			$this->Ln(5);
		}
		//

		$this->SetFont('Arial','B',8);
		$this->Cell(175,4, "IVA:", 0, 0, 'R');
		$this->SetFont('Arial','',8);

		//}
		$this->Cell(25, 4, number_format($row["iva"], 2, ",", "."), 0, 0, 'R');
		$this->Cell(25, 4, number_format($row["iva"]/$tasa_dia, 2, ",", "."), 0, 0, 'R');
		$this->Ln(5);
		$this->SetFont('Arial','B',8);
		$this->Cell(10, 4);
		$this->Cell(20, 4, "User: $username", 0, 0, 'L');
		$this->SetFont('Arial','',8);
		$this->Cell(50, 4, strtoupper($nota), 0, 0, 'R');
		$this->SetFont('Arial','B',8);
		$this->Cell(95, 4, "TOTAL $moneda:", 0, 0, 'R');
		$this->SetFont('Arial','',8);
		$this->Cell(25, 4, number_format($row["total"], 2, ",", "."), 0, 0, 'R');
		// $this->Cell(25, 4, number_format($row["total"]/$tasa_dia, 2, ",", "."), 0, 0, 'R');

		$this->Ln(6);
		$this->SetFont('Arial','I',6);
		$this->MultiCell(0, 10, $GLOBALS["direccion_cia"], 0, 'C');

		require("../include/desconnect.php");		
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);


// CONCAT(IFNULL(c.nombre, ''), ' - ', IFNULL(b.principio_activo, ''), ' ', IFNULL(b.presentacion, ''), ' ', IFNULL(b.nombre_comercial, '')) AS articulo, 
$sql = "SELECT 
			b.codigo,  
			CONCAT(IFNULL(b.principio_activo, ''), ' ', IFNULL(b.presentacion, ''), ' ', IFNULL(b.nombre_comercial, '')) AS articulo, 
			a.cantidad_articulo AS cantidad, 
			(SELECT descripcion FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida, 
			(SELECT alicuota FROM alicuota WHERE codigo = b.alicuota AND activo = 'S') alicuota, 
			a.costo_unidad, 
			a.costo, 
			a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, 
			a.precio_unidad, a.precio, b.codigo_ims 
		FROM 
			entradas_salidas AS a 
			LEFT OUTER JOIN articulo AS b ON b.id = a.articulo 
			LEFT OUTER JOIN fabricante AS c ON c.Id = a.fabricante 
		WHERE 
			a.id_documento = '$id' AND a.tipo_documento = '" . $GLOBALS["tipo_documento"] . "'
		ORDER BY b.principio_activo, b.presentacion;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(10, 4);
	$pdf->Cell(20, 4, $row["codigo_ims"], 0, 0, 'L');
	if(strlen($row["articulo"]) < 55) 
		$pdf->Cell(105, 4, $row["articulo"], 0, 0, 'L');
	else 
		$pdf->Cell(105, 4, substr($row["articulo"], 0, 55), 0, 0, 'L');
	$pdf->Cell(15, 4, round($row["cantidad"], 0), 0, 0, 'C');
	$pdf->Cell(25, 4, number_format($row["precio_unidad"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(25, 4, number_format($row["precio"], 2, ",", "."), 0, 0, 'R');

	if(strlen($row["articulo"]) >= 55) {
		$pdf->Ln();
		$pdf->Cell(30, 4);
		$pdf->MultiCell(100, 4, substr($row["articulo"], 55, strlen($row["articulo"])), 0, 'L');
	}
	else $pdf->Ln();

	//if($pdf->GetY() > 250) $pdf->AddPage();
	$items++;
}

$pdf->EndReport($id);

	
require("../include/desconnect.php");

$pdf->Output();
?>