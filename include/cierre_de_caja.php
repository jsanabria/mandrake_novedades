<?php
session_start();

$consulta = $_REQUEST["consulta"];
$fecha = $_REQUEST["fecha"];
$abrir = $_REQUEST["abrir"];

include "connect.php";

if($consulta == "S") {
  $sql = "SELECT fecha FROM cierre_de_caja WHERE fecha = '$fecha';";
  $rs = mysqli_query($link, $sql);
  if($row = mysqli_fetch_array($rs)) echo "S";
  else echo "N";
} 
else { 
  if($abrir == "N") {
    $sql = "INSERT INTO cierre_de_caja(id, fecha) VALUES (NULL, '$fecha');";
    mysqli_query($link, $sql);
    echo "Se ha cerrado la caja para la fecha: $fecha";
  } 
  else {
    $sql = "DELETE FROM cierre_de_caja WHERE fecha = '$fecha';";
    mysqli_query($link, $sql);
    echo "Apertura de caja para la fecha: $fecha";
  }
}

?>