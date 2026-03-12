<?php 
session_start();

include "connect.php";

$articulo = intval($_REQUEST["articulo"]); 
$cliente = intval($_REQUEST["cliente"]); 
$pago_divisa = $_REQUEST["pago_divisa"] ?? "N";

/*
$sql = "SELECT valor2 AS tarifa_por_defecto FROM parametro WHERE codigo = '051';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$tarifa = intval($row["tarifa_por_defecto"]);
else 
	$tarifa = 3;
*/

//$sql = "SELECT precio FROM tarifa_articulo WHERE tarifa = $tarifa AND articulo = $articulo;"; 
$sql = "SELECT tarifa FROM cliente WHERE id = $cliente;";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$tarifa = $row["tarifa"];
else 
	$tarifa = 0;

$sql = "SELECT 
			ABS(IFNULL(a.precio, 0))-(ABS(IFNULL(a.precio, 0))*(b.descuento/100)) AS precio, 
			ABS(IFNULL(a.precio2, 0))-(ABS(IFNULL(a.precio2, 0))*(b.descuento/100)) AS precio2, 
			b.descuento 
		FROM tarifa_articulo AS a JOIN articulo AS b ON b.id = a.articulo 
		WHERE a.articulo = $articulo AND a.tarifa = $tarifa;"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$precio = $pago_divisa == "S" ? floatval($row["precio2"]) : floatval($row["precio"]);
else 
	$precio = 0;

echo $precio;
?>
