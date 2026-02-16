<?php 
session_start();

include "connect.php";

$cliente = intval($_REQUEST["id"]); 

// $sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga WHERE cliente = $cliente;"; 
$sql = "SELECT id, saldo FROM recarga WHERE cliente = $cliente ORDER BY id DESC LIMIT 0, 1;";
$rs = mysqli_query($link, $sql);
$saldo = 0;
$saldoBS = 0;
$saldoUSD = 0;
$sw = false;
if($row = mysqli_fetch_array($rs)) {
	$saldo += floatval($row["saldo"]);
	$saldoBS = floatval($row["saldo"]);
	$sw = true;
}

$sql = "SELECT id, saldo FROM recarga2 WHERE cliente = $cliente ORDER BY id DESC LIMIT 0, 1;";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) {
	$saldo += floatval($row["saldo"]);
	$saldoUSD = floatval($row["saldo"]);
	$sw = true;
}

if($sw) {
	if($saldo > 0.00) {
		echo '<span class="text-success"><strong>Saldo: </strong>$ ' . number_format($saldo, 2, ".", ",") . '<br>( $ ' . $saldoUSD . ' | Bs. ' . $saldoBS . ' )</span>';
	}
	else {
		echo '<span class="text-danger"><strong>Saldo: </strong>$ ' . number_format($saldo, 2, ".", ",") . '</span>';
	}
} 
else {
	echo 'Cliente <span class="text-danger">*</span>';
}
?>
