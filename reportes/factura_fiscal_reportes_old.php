<?php 
session_start(); 

$id = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";
$username = isset($_REQUEST["username"])?$_REQUEST["username"]:"";
$documento = isset($_REQUEST["documento"])?$_REQUEST["documento"]:"";

/*echo exec("FiscalPrinter\FiscalPrinter PR $id $username");
die();*/

echo exec("FiscalPrinter\FiscalPrinter $documento $id $username");

die("FiscalPrinter\FiscalPrinter $documento $id $username");
?>