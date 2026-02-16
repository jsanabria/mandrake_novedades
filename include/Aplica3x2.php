<?php 
session_start();

include "connect.php";

$sql = "SELECT valor1 AS aplica FROM parametro WHERE codigo = '050';";
$rs = mysqli_query($link, $sql);
$out = "N";
if($row = mysqli_fetch_array($rs)) {
	$out = $row["aplica"];
}

echo $out;
?>
