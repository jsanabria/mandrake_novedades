<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "connect.php";

$id = $_REQUEST["id"];
$username = $_REQUEST["username"];

$sql = "UPDATE salidas SET nombre='$username' WHERE tipo_documento='TDCPDV' AND id = $id;"; 
$rs = mysqli_query($link, $sql);

header("Location: ../SalidasList?tipo=TDCPDV");

include "desconnect.php"; 
?>
