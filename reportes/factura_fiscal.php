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
require('rcs/fpdf.php');
require("../include/connect.php");

//echo exec("FiscalPrinter\FiscalPrinter PR 45 username");
//die();

$id = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";
$GLOBALS["CurrentUserName"] = isset($_REQUEST["username"])?$_REQUEST["username"]:"";

$sql = "SELECT 
			id, date_format(fecha, '%d/%m/%Y') as fecha, cliente, nro_documento, tipo_documento, estatus, cliente, username, documento, monto_base_igtf     
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

$documento = $row["documento"];

$username = $GLOBALS["CurrentUserName"];

// exec("C:\Users\Junior\Documents\Visual Studio 2015\Projects\novedades\bin\Debug\Venezuela Demo VB.NET 2.0.exe");
$message = '<h2 class="visor_imagenes">' . utf8_encode(exec("FiscalPrinter\FiscalPrinter $documento $id $username")) . '</h2>';
echo $message;

if(strpos($message, "Error") > 0)
	echo '<span class="blanco-rojo">FiscalPrinter\FiscalPrinter ' . $documento . ' ' . $id . ' ' . $username;
else 
	echo '<span class="blaco-verde">FiscalPrinter\FiscalPrinter ' . $documento . ' ' . $id . ' ' . $username;

//$MaquinaFiscal = new DOTNET("MaquinaFiscal, Version=1.0.0.1, Culture=Neutral, PublicKeyToken=642221e538850e80", "MaquinaFiscal.Impresora");
//$MaquinaFiscal = new DOTNET("MaquinaFiscal, Version=v4.0_1.0.0.1, Culture=Neutral, PublicKeyToken=642221e538850e80", "MaquinaFiscal.Impresora");
//$MaquinaFiscal = new DOTNET("MaquinaFiscal, Version=1.0.0.0, Culture=Neutral, PublicKeyToken=642221e538850e80", "MaquinaFiscal.Impresora");

//$MaquinaFiscal->imprimirFactura("Hello World y más nada... Hay que hacer que funcione con el Frame Work 4 en adelante");


//liberando el objeto
//$MaquinaFiscal = null;

?>
</body>
</html>