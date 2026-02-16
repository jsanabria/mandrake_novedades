<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('rcs/fpdf.php');

$GLOBALS["id"] = trim(isset($_REQUEST["id"])?$_REQUEST["id"]:"0");

class PDF extends FPDF
{
	// Cabecera de pαgina
	function Header()
	{
		// Consulto datos de la compaρνa 
		require("../include/connect.php");

		$id = $GLOBALS["id"];

		$sql = "SELECT codigo_ims AS codigo_qr FROM articulo WHERE id = '$id';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		$codigo_qr = $row["codigo_qr"];

		$file = "qrSherin_$codigo_qr.png";
		$contenido = $codigo_qr; 

		$dir = '../codigo_qr/temp/';
		$filename = $dir .  $file;
		$qr = $filename;
			
		// if(file_exists($filename)) unlink($filename);
		if(!file_exists($filename))
			crear_qr($dir, $filename, $contenido, $filename2, $contenido2);

		$this->Ln(13);

		$this->Image($qr, 10, 5, 40);

		require("../include/desconnect.php");
	}
	
	// Pie de pαgina
	function Footer()
	{
		// Posiciσn: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Courier','I',8);
		// Nϊmero de pαgina
		//$this->Cell(0,10,'Pag '.$this->PageNo().'/{nb}',0,0,'R');
	}
	
	/*
	function EndReport($id)
	{
		//$this->AddPage();
	}
	*/
}

require("../include/connect.php");

//$pdf = new PDF('L', 'mm', array(155,105));
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();

$pdf->AddPage();

// $pdf->EndReport($id);

	
$pdf->Output();

function crear_qr($dir, $filename, $contenido, $filename2, $contenido2) { 
	include_once('../codigo_qr/phpqrcode/qrlib.php');

	if(!file_exists($dir)) 
		mkdir($dir);

	$tamanio = 6;
	$level = 'Q';
	$frameSize = 3;
	//$contenido = 'http://lagunita.clublagunita.com/autogestion/encuesta_enviar.php?Nafiliado=Xafiliado&Nencuesta=2';


	QRcode::png($contenido, $filename, $level, $tamanio, $frameSize);

	$tamanio = 8;
	$level = 'Q';
	$frameSize = 1;
	//$contenido = 'http://lagunita.clublagunita.com/autogestion/encuesta_enviar.php?Nafiliado=Xafiliado&Nencuesta=2';

	QRcode::png($contenido2, $filename2, $level, $tamanio, $frameSize);

}

?>