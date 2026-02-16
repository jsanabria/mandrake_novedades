<?php 
session_start();

include "connect.php";

$articulo = $_REQUEST["articulo"]; 
$precio = $_REQUEST["precio"]; 

$sql = "SELECT ABS(IFNULL(precio, 0)) AS precio FROM tarifa_articulo WHERE articulo = $articulo;";
$rs = mysqli_query($link, $sql);
$out = "N";
while($row = mysqli_fetch_array($rs)) {
	if($row["precio"] == $precio) {
		$out = "S";
		break;
	}
}

echo $out;
?>
