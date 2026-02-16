<?php 
$host="localhost";
$user="novedd4d_sujoya";
$password="Tomj@vas001";
$data="novedd4d_db001";
//$user="root";
//$password="";
//$data="mandrake";
$enlace = mysqli_connect($host,$user,$password) or die(mysql_error());
$link = $enlace;
mysqli_select_db($link, $data);
mysqli_query($link, "SET NAMES 'utf8'");
ini_set('date.timezone', 'America/Caracas'); 
?>