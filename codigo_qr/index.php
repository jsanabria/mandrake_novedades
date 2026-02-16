<?php 
require 'phpqrcode/qrlib.php';

$dir = 'temp/';

if(!file_exists($dir)) 
	mkdir($dir);

$file = "QRSusana.png";

$filename = $dir .  $file;

$tamanio = 8;
$level = 'Q';
$frameSize = 1;
$contenido = 'https://decodibo.com.ve/Mue2107120032--SUSANA-ALONSO.pdf';
//$contenido = 'Junior Enrique Sanabria Rubio';

QRcode::png($contenido, $filename, $level, $tamanio,$frameSize);
?>