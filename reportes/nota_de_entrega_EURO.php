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
$GLOBALS["id"] = $id;

$GLOBALS["CurrentUserName"] = isset($_REQUEST["CurrentUserName"])?$_REQUEST["CurrentUserName"]:"";

$sql = "SELECT 
			id, date_format(fecha, '%d/%m/%Y') as fecha, cliente, nro_documento, tipo_documento, estatus, cliente, username   
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
$GLOBALS["username"] = $row["username"];

$cliente = $row["cliente"];

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
		$GLOBALS["logo"] = $logo;
		
		
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
		
		$this->SetFont('Arial','B',10);
		$this->Cell(200, 6, $row["descripcion"],0,0,'C');
		


		$this->Ln(8);
		
		$this->SetFont('Arial','',8);
		
		$this->Cell(10, 5);
		$this->Cell(50, 5, utf8_decode($cia),0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(140, 5, "ESTATUS: " . $GLOBALS["estatus"] . " / " . "No. Doc.: " . $GLOBALS["nro_documento"],0,0,'R');

		$this->Ln();
		$this->Cell(10, 5);
		$this->SetFont('Arial','',8);
		$this->Cell(100, 5, "R.I.F: $ci_rif", 0, 0, "L");
		$this->SetFont('Arial','',8);
		$this->Cell(50, 5, "CIUDAD: $ciudad",0,0,'R');
		$this->Cell(40, 5, "FECHA: " . $GLOBALS["fecha"],0,0,'R');

		$this->Ln();

		$this->Cell(10, 4);
		$this->Cell(30, 4,"CLIENTE: ",'0','0','L');
		$this->Cell(120, 4, $razon_social,'0','0','L');
		$this->Cell(40, 4, "User: " . $GLOBALS['username'], 0, 0, 'R');
	

		$this->Ln();
		$this->Cell(10, 4);
		$this->Cell(30, 4,'DIRECCION: ','0','0','L');
		$this->MultiCell(160, 4, "$direccion_cliente. $ciudad_cliente", '0', 'L');

		$this->Cell(10, 5);
		$this->Cell(70,5,'R.I.F.: ' . $rif,'0',0,'L');
		$this->Cell(80,5,'Telf:'.  $telf,'0','0','L');
		$this->Cell(50,5,'CONTADO','0',0,'C');

		require("../include/desconnect.php");
		$this->Ln(6);

		$this->Cell(10, 5);
		$this->Cell(20, 5, "CODIGO", 1, 0, 'L');
		$this->Cell(105, 5, "ARTICULO", 1, 0, 'L');
		$this->Cell(15, 5, "CANT", 1, 0, 'C');
		$this->Cell(25, 5, "PRECIO", 1, 0, 'R');
		$this->Cell(25, 5, "TOTAL", 1, 0, 'R');
		$this->Ln(6);

		$this->BodyReport();
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


		$sql = "SELECT 
					a.alicuota_iva, 
					a.iva,
					a.monto_total, 
					a.total, 
					a.nota, a.doc_afectado,  
					a.moneda, 
					a.username, a.id_documento_padre, 
					a.monto_usd, IFNULL(a.tasa_dia, 0) AS tasa_dia, a.descuento, a.monto_sin_descuento, a.unidades, 
					a.nro_despacho, estatus  
				FROM salidas a where a.id = '$id_invoice'"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$alicuota = $row["alicuota_iva"];
		$nota = utf8_decode($row["nota"]);
		$moneda = utf8_decode($row["moneda"]);
		$moneda = ($moneda == "USD" ? "EURO" : $moneda); // Junior EURO
		$username = utf8_decode($row["username"]);
		$monto_total = $row["monto_total"];
		$monto_sin_descuento = $row["monto_sin_descuento"];
		$estatus = $row["estatus"];

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

		if($this->GetY() <= 120)
			$this->Ln(120-$this->GetY());
		else 
			$this->Ln(250-$this->GetY());

		$this->SetFont('Arial','',8);

		if(floatval($row["iva"]) > 0.00) {
				$this->Cell(175, 4, "SUB-TOTAL EXENTO:", 0, 0, 'R');
				$this->SetFont('Arial','',8);
				$this->Cell(25, 4, number_format($row["exento"], 2, ",", "."), 0, 0, 'R');
				//$this->Cell(25, 4, number_format($row["exento"]/$tasa_dia, 2, ",", "."), 0, 0, 'R');
				$this->Ln(5);

				$this->SetFont('Arial','',8);
				$this->Cell(175,4, "SUB-TOTAL GRAVADO:", 0, 0, 'R');
				$this->SetFont('Arial','',8);
				$this->Cell(25, 4, number_format($row["gravado"], 2, ",", "."), 0, 0, 'R');
				// $this->Cell(25, 4, number_format($row["gravado"]/$tasa_dia, 2, ",", "."), 0, 0, 'R');
				$this->Ln(5);

				// Se imprime el descuento si aplica
				if($descuento > 0) {
					$this->SetFont('Arial','',8);
					$this->Cell(101,4, "", 0, 0, 'R');
					$this->SetFont('Arial','',8);
					$this->Cell(175,4, "Descuento " . number_format($descuento, 2, ",", ".") . "%:", 0, 0, 'R');
					$this->SetFont('Arial','',8);
					$this->Cell(25, 4, number_format($monto_total-$monto_sin_descuento, 2, ",", "."), 0, 0, 'R');
					// $this->Cell(25, 4, number_format(($monto_total-$monto_sin_descuento)/$tasa_dia, 2, ",", "."), 0, 0, 'R');
					$this->Ln(5);
				}
				//

				$this->SetFont('Arial','',8);
				$this->Cell(175,4, "IVA:", 0, 0, 'R');
				$this->SetFont('Arial','',8);

				//}
				$this->Cell(25, 4, number_format($row["iva"], 2, ",", "."), 0, 0, 'R');
				$this->Cell(25, 4, number_format($row["iva"]/$tasa_dia, 2, ",", "."), 0, 0, 'R');
				$this->Ln(5);
				$this->SetFont('Arial','',8);
				$this->Cell(90, 4);
				//$this->Cell(80, 4, "User: $username", 0, 0, 'L');
				$this->SetFont('Arial','',8);
				$this->Cell(50, 4, "RECIBE CONFORME", "T", 0, 'C');
				$this->SetFont('Arial','',8);
				$this->Cell(35, 4, "TOTAL $moneda:", 0, 0, 'R');
				$this->SetFont('Arial','',8);
				$this->Cell(25, 4, number_format($row["total"], 2, ",", "."), 0, 0, 'R');
				// $this->Cell(25, 4, number_format($row["total"]/$tasa_dia, 2, ",", "."), 0, 0, 'R');
		} 
		else {
				// Se imprime el descuento si aplica
				if($descuento > 0) {
					$this->SetFont('Arial','',8);
					$this->Cell(175, 4, "SUB-TOTAL:", 0, 0, 'R');
					$this->SetFont('Arial','',8);
					$this->Cell(25, 4, number_format($row["exento"], 2, ",", "."), 0, 0, 'R');
					//$this->Cell(25, 4, number_format($row["exento"]/$tasa_dia, 2, ",", "."), 0, 0, 'R');
					$this->Ln(5);

					$this->Cell(175,4, "Descuento " . number_format($descuento, 2, ",", ".") . "%:", 0, 0, 'R');
					$this->SetFont('Arial','',8);
					$this->Cell(25, 4, number_format($monto_total-$monto_sin_descuento, 2, ",", "."), 0, 0, 'R');
					// $this->Cell(25, 4, number_format(($monto_total-$monto_sin_descuento)/$tasa_dia, 2, ",", "."), 0, 0, 'R');
					$this->Ln(5);
				}
				//
				$this->SetFont('Arial','',8);
				$this->Cell(90, 4);
				//$this->Cell(80, 4, "User: $username", 0, 0, 'L');
				$this->SetFont('Arial','',8);
				$this->Cell(50, 4, "RECIBE CONFORME", "T", 0, 'C');
				$this->SetFont('Arial','',8);
				$this->Cell(35, 4, "TOTAL $moneda:", 0, 0, 'R');
				$this->SetFont('Arial','',8);
				$this->Cell(25, 4, number_format($row["total"], 2, ",", "."), 0, 0, 'R');
		}

		if(trim($nota) != "") {
			$this->Ln();
			$this->SetFont('Arial','B',6);
			$this->Cell(10, 4);
			$nota = substr($nota, 0, 140);
			$this->Cell(190, 4, "Nota: $nota", 0, 0, "L");		
		} 
		else {
			$this->Ln(6);			
		}
		$this->SetFont('Arial','',8);
		//$this->MultiCell(0, 10, $GLOBALS["direccion_cia"], 0, 'C');
		//$this->Cell(60, 4);
		//$this->Cell(70, 6, "RECIBE CONFORME", "T", 0, 'C');

		/*
		if($estatus == "NUEVO") {
			$sql = "UPDATE salidas SET estatus = 'PROCESADO', username = '" . $GLOBALS["CurrentUserName"] . "' 
					WHERE id = " . $id_invoice . ";";
			mysqli_query($link, $sql);
		}
		*/


		require("../include/desconnect.php");		
	}

	function BodyReport() 
	{
		require("../include/connect.php");
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
					a.id_documento = '" . $GLOBALS["id"] . "' AND a.tipo_documento = '" . $GLOBALS["tipo_documento"] . "'
				ORDER BY b.codigo_ims, a.cantidad_articulo, a.id;"; 

		$rs = mysqli_query($link, $sql) or die(mysqli_error());
		$items = 0;
		while($row = mysqli_fetch_array($rs))
		{
			$this->SetFont('Arial', '', 8);
			$this->Cell(10, 4);
			$this->Cell(20, 4, $row["codigo_ims"], 0, 0, 'L');
			$this->Cell(105, 4, substr($row["articulo"], 0, 60), 0, 0, 'L');

			/*
			if(strlen($row["articulo"]) < 55) 
				$pdf->Cell(105, 4, $row["articulo"], 0, 0, 'L');
			else 
				$pdf->Cell(105, 4, substr($row["articulo"], 0, 55), 0, 0, 'L');
			*/

			$this->Cell(15, 4, round($row["cantidad"], 0), 0, 0, 'C');
			$this->Cell(25, 4, number_format($row["precio_unidad"], 2, ",", "."), 0, 0, 'R');
			$this->Cell(25, 4, number_format($row["precio"], 2, ",", "."), 0, 0, 'R');

			/*
			if(strlen($row["articulo"]) >= 55) {
				$pdf->Ln();
				$pdf->Cell(30, 4);
				$pdf->MultiCell(100, 4, substr($row["articulo"], 55, strlen($row["articulo"])), 0, 'L');
			}
			else $pdf->Ln();
			*/
			$this->Ln();

			//if($this->GetY() > 120) $this->AddPage();
			$items++;
		}

		$sql = "SELECT id, saldo FROM recarga WHERE cliente = " . $GLOBALS["cliente"] . " ORDER BY id DESC LIMIT 0, 1;";

		$rs = mysqli_query($link, $sql);
		$saldo_actual = "Saldo actual abonos: ";
		// if($row = mysqli_fetch_array($rs)) $saldo_actual = "\n(Saldo actual abonos: *** USD " . number_format($row["saldo"], 2, ".", ",") . " ***)";
		if($row = mysqli_fetch_array($rs)) $saldo_actual .= "(Bs: * EURO " . number_format($row["saldo"], 2, ".", ",") . " *)"; // Junior EURO

		$sql = "SELECT id, saldo FROM recarga2 WHERE cliente = " . $GLOBALS["cliente"] . " ORDER BY id DESC LIMIT 0, 1;";

		$rs = mysqli_query($link, $sql);
		// if($row = mysqli_fetch_array($rs)) $saldo_actual = "\n(Saldo actual abonos: *** USD " . number_format($row["saldo"], 2, ".", ",") . " ***)";
		if($row = mysqli_fetch_array($rs)) $saldo_actual .= "(EURO: * EURO " . number_format($row["saldo"], 2, ".", ",") . " *)"; // Junior EURO

		$sql = "SELECT 
					b.metodo_pago, IF(b.metodo_pago = 'RC' OR b.metodo_pago = 'RD', '', b.referencia) AS referencia, 
					b.monto_moneda, b.moneda, b.monto_usd 
				FROM 
					cobros_cliente AS a 
					JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
				WHERE a.id_documento = " . $GLOBALS["id"] . ";";
		$rs = mysqli_query($link, $sql) or die(mysqli_error());
		$this->Cell(10, 4);
		$this->Cell(20, 4, "TIPO", "B", 0, 'L');
		$this->Cell(20, 4, "REF. #", "B", 0, 'L');
		$this->Cell(20, 4, "MONTO", "B", 0, 'R');
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(30, 4);
		$this->Cell(100, 4, $saldo_actual, 0, 0, 'C');
		//$pdf->Cell(10, 4);
		$this->Ln();

		$recibo = "";
		$sw = false;
		while($row = mysqli_fetch_array($rs))
		{
			$sql = "SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = '" . $row["metodo_pago"] . "';";
			$rs2 = mysqli_query($link, $sql) or die(mysqli_error());
			$row2 = mysqli_fetch_array($rs2);

			$this->SetFont('Arial', '', 8);
			$this->Cell(10, 4);
			$this->Cell(20, 4, substr($row2["valor2"], 0, 11), 0, 0, 'L');
			$this->Cell(20, 4, $row["referencia"], 0, 0, 'L');
			// $this->Cell(20, 4, $row["moneda"] . " " . $row["monto_moneda"], 0, 0, 'R');
			$this->Cell(20, 4, ($row["moneda"] == "USD" ? "EURO" : $row["moneda"]) . " " . $row["monto_moneda"], 0, 0, 'R');  // Junior EURO

			
			$sql3 = "SELECT 
						b.metodo_pago, IF(b.metodo_pago = 'RC', '', b.referencia) AS referencia, b.monto_moneda, b.moneda, 
						b.monto_usd, c.nro_recibo, c.monto_usd  
					FROM 
						cobros_cliente AS a 
						JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
						LEFT OUTER JOIN recarga AS c ON c.cobro_cliente_reverso = a.id 
					WHERE a.id_documento = " . $GLOBALS["id"] . " 
					UNION SELECT 
						b.metodo_pago, IF(b.metodo_pago = 'RD', '', b.referencia) AS referencia, b.monto_moneda, b.moneda, 
						b.monto_usd, c.nro_recibo, c.monto_usd  
					FROM 
						cobros_cliente AS a 
						JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
						LEFT OUTER JOIN recarga2 AS c ON c.cobro_cliente_reverso = a.id 
					WHERE a.id_documento = " . $GLOBALS["id"] . " ORDER BY nro_recibo DESC LIMIT 0, 1;";
			$rs3 = mysqli_query($link, $sql3) or die(mysqli_error());
			$row3 = mysqli_fetch_array($rs3);
			if(trim($row3["nro_recibo"]) != "") {
				if($sw == false) {
					$this->SetFont('Arial','B',8);
					$recibo = str_pad($row3["nro_recibo"], 7, "0", STR_PAD_LEFT) . " / EURO " . number_format($row3["monto_usd"], 2, ".", ",");
					$this->Cell(60, 4);
					$this->Cell(70, 4, "Nro. RECIBO: " . $recibo, 0, 0, 'C');
					$this->SetFont('Arial','',8);
					$sw = true;
				}
			}
			
			$this->Ln();

		}

		$this->EndReport($GLOBALS["id"]);

			
		require("../include/desconnect.php");
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
		
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

if($pdf->GetY() <= 130) {
	if(trim($GLOBALS["logo"]) != "") {
		$pdf->Image("../carpetacarga/" . $GLOBALS["logo"], 10, 135, 50);
	}
	$pdf->SetY(136);
	$pdf->Header();
}


$pdf->Output();
?>