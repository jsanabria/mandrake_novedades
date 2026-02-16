<?php
session_start();

$fecha = $_REQUEST["fecha"];

include "connect.php";

$sql = "SELECT valor1 AS servidor, valor2 AS codigo FROM parametro WHERE codigo = '048';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) { 
  $servidor = $row["servidor"];
  if(substr($servidor, strlen($servidor)-1, strlen($servidor)) != "/") $servidor .= "/";
  $codigo = $row["codigo"]; 

  header("Location: $servidor" . "SincronizarTienda/codigo=$codigo");

  echo "Sincronizaci&oacute;n en curso";
}
else { 
  echo "No hay datos para sincronizar";
} 


?>