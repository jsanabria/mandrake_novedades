<?php 
include "connect.php";

$tipo_pago = $_REQUEST["tipo_pago"];
$referencia = $_REQUEST["referencia"];

$sql = "SELECT metodo_pago, referencia  FROM cobros_cliente_detalle WHERE metodo_pago = '$tipo_pago' AND referencia = '$referencia' 
		UNION ALL SELECT metodo_pago, referencia FROM recarga WHERE metodo_pago = '$tipo_pago' AND referencia = '$referencia';";
$rs = mysqli_query($link, $sql);

if($row = mysqli_fetch_array($rs)) $out = "1";
else $out = "0";

echo $out;
?>
