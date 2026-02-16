<?php 
session_start();

include "connect.php";

$articulo = $_REQUEST["articulo"]; 
$precio = $_REQUEST["precio"]; 
$username = $_REQUEST["xUserN"]; 

$sql = "SELECT ABS(IFNULL(precio, 0)) AS precio FROM tarifa_articulo WHERE articulo = $articulo;";
$rs = mysqli_query($link, $sql);
$out = "N";
while($row = mysqli_fetch_array($rs)) {
	if($row["precio"] == $precio) {
		$out = "S";
		break;
	}
}

if(strtoupper($username) == "ADMINISTRADOR") $out = "S";
else {
	$sql = "SELECT userlevelid FROM usuario WHERE username = '$username';";
	$rs = mysqli_query($link, $sql);
	if($row = mysqli_fetch_array($rs)) {
		if($row["userlevelid"] == -1) $out = "S";
	}
}

echo $out;
?>
