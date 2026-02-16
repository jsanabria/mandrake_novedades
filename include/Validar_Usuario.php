<?php 
session_start();

include "connect.php";

$usernama = $_REQUEST["usernama"]; 
$password = $_REQUEST["password"]; 
$nota_entrega = isset($_REQUEST["nota_entrega"]) ? $_REQUEST["nota_entrega"] : "NUEVA";
$articulo = $_REQUEST["articulo"];
$usercaja = $_REQUEST["usercaja"];
$cliente = isset($_REQUEST["cliente"]) ? $_REQUEST["cliente"] : 0;
if(trim($cliente) == "") $cliente = "0";

$sql = "SELECT principio_activo FROM articulo WHERE id = $articulo";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs))
	$articulo = $row["principio_activo"];
else 
	$articulo = " *** APLICA DESCUENTO *** ";

$sql = "SELECT nombre FROM cliente WHERE id = $cliente"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs))
	$cliente = $row["nombre"];


$sql = "SELECT id FROM usuario WHERE username = '$usernama' AND password = '$password';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) {
	$sql = "SELECT valor1 AS usuario FROM parametro WHERE codigo = '045' AND RTRIM(valor1) = '$usernama';";
	$rs = mysqli_query($link, $sql);
	if($row = mysqli_fetch_array($rs)) $resp = "S";
	else $resp = "N";
} 
else {
	$resp = "N";
}

if($articulo == "") $resp = "N";

$sql = "INSERT INTO audittrail
	(id, datetime, script, user, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
	VALUES (NULL, NOW(), 'SOLICITAR AUTORIZACION CAMBIO DE PRECIO', '$usernama', 'NOTA DE ENTREGA: $nota_entrega CLIENTE: $cliente', 'ARTICULO: $articulo', 'USUARIO EN CAJA: $usercaja | AUTORIZADO: $resp', '', '', '')";
mysqli_query($link, $sql);	

echo $resp;
?>
