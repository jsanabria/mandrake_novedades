<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--<meta charset="utf-8">-->
<title>Impresora Fiscal</title>
</head>
<body>
	<style type="text/css">
		#visor_imagenes {
		  text-align: center;
		  color: #fff;
		  background-color: #000;
		}		

		.blaco-verde {
		  color: #FFFFFF;
		  background-color: green;
		}		

		.blanco-rojo {
		  color: #FFFFFF;
		  background-color: red;
		}		
	</style>
<?php

$id = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";
$username = "NA.NA"; // isset($_REQUEST["username"])?$_REQUEST["username"]:"";
$documento = isset($_REQUEST["documento"])?$_REQUEST["documento"]:"";

/*echo exec("FiscalPrinter\FiscalPrinter PR $id $username");
die();*/

$message = '<h2 class="visor_imagenes">' . utf8_encode(exec("FiscalPrinter\FiscalPrinter $documento $id $username")) . '</h2>';
echo $message;

if(strpos($message, "Error") > 0)
	echo '<span class="blanco-rojo">FiscalPrinter\FiscalPrinter ' . $documento . ' ' . $id . ' ' . $username;
else 
	echo '<span class="blaco-verde">FiscalPrinter\FiscalPrinter ' . $documento . ' ' . $id . ' ' . $username;
?>
</body>
</html>