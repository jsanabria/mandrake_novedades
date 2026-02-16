<?php 
session_start();

include "connect.php";

$articulo = intval($_REQUEST["articulo"]); 

$sql = "SELECT IFNULL(ultimo_costo, 0) AS costo FROM articulo WHERE id = $articulo;";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$costo = floatval($row["costo"]);
else 
	$costo = 0;


$sql = "SELECT valor2 AS tarifa_por_defecto FROM parametro WHERE codigo = '051';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$tarifa = intval($row["valor2"]);
else 
	$tarifa = 3;

$sql = "SELECT precio FROM tarifa_articulo WHERE tarifa = $tarifa AND articulo = $articulo;";
if($row = mysqli_fetch_array($rs)) 
	$precio = floatval($row["precio"]);
else 
	$precio = 3;

echo "$costo|$precio;
?>
