<?php 
session_start();

include "connect.php";

$articulo = $_REQUEST["articulo"]; 

$sql = "SELECT ABS(IFNULL(precio, 0)) AS precio FROM tarifa_articulo WHERE articulo = $articulo ORDER BY tarifa LIMIT 0,1;";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$cnt = floatval($row["cantidad_en_mano"]);
else 
	$cnt = 0;

echo $cnt;
?>
