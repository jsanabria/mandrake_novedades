<?php
session_start();

/*
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=reporteLibroDiario.xls");
header("Pragma: no-cache");
header("Expires: 0");
} 

*/
header('Content-Type:text/html; charset=UTF-8');
header("Content-Disposition: attachment; filename=paymul.txt");
header("Pragma: no-cache");
header("Expires: 0");

include "connect.php";

$id = $_REQUEST["id"];

$sql = "SELECT valor1 AS agrupa FROM parametro WHERE codigo = '034';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$SI = $row["agrupa"];

$cantidad = 0;
$total = 0.00;

if($SI == "S") {
  $sql = "SELECT 
            proveedor, COUNT(proveedor) AS cantidad, SUM(monto_a_pagar) AS suma 
          FROM 
            cont_lotes_pagos_detalle 
          WHERE cont_lotes_pago = $id GROUP BY proveedor;"; 
} 
else {
  $sql = "SELECT 
            proveedor, monto_a_pagar AS suma 
          FROM 
            cont_lotes_pagos_detalle 
          WHERE cont_lotes_pago = $id;"; 
}
$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) {
  $cantidad++;
  $total += $row["suma"];
}

$sql = "SELECT referencia AS ref, LPAD(referencia, 6, ' ') AS referencia, banco, date_format(fecha, '%Y%m%d') AS fecha FROM cont_lotes_pagos WHERE id = $id;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$referencia = $row["referencia"];
$banco = intval($row["banco"]);
$fecha = $row["fecha"];
$ref = intval($row["ref"]);
$referencia_pad = str_pad($ref, 6, "0", STR_PAD_LEFT);

$fecha_hora_generado = date("YmdHis");

$sql = "SELECT SUBSTRING(RPAD(REPLACE(ci_rif, '-', ''), 10, ' '), 1, 10) AS ci_rif, SUBSTRING(RPAD(LTRIM(RTRIM(nombre)), 35, ' '), 1, 35) AS nombre FROM compania WHERE id = 1;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$ci_rif = $row["ci_rif"];
$nombre = $row["nombre"];

$hspace1 = 22;
$hspace2 = 7;
$hspace3 = 14;
$hspace4 = 4;

$dspace1 = 10;

$total = str_pad(str_replace(".", "", str_replace(",", "", "$total")), 15, "0", STR_PAD_LEFT);

$sql = "SELECT LPAD(numero, 20, '0') AS numero FROM compania_cuenta WHERE id = $banco;";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $cuenta = $row["numero"];
else $cuenta = "00000000000000000000";


echo "HDRBANESCO        ED  95BPAYMULP\n";
echo "01SCV                                9$referencia                               $fecha_hora_generado\n";
echo "0200" . $referencia_pad . str_repeat(" ", $hspace1) . $ci_rif . str_repeat(" ", $hspace2) . $nombre . $total . "VES " . $cuenta . str_repeat(" ", $hspace3) . "BANESCO" . str_repeat(" ", $hspace4) . $fecha . "\n";


if($SI == "S") {
  $sql = "SELECT 
            LPAD(IFNULL(b.cta_bco, '0'), 20, '0') AS cta_bco, RPAD(replace(b.ci_rif, '-', ''), 10, '0') AS ci_rif, RPAD(RTRIM(b.nombre), 271, ' ') AS nombre, SUM(a.monto_a_pagar) AS monto_a_pagar 
          FROM 
            cont_lotes_pagos_detalle AS a 
            LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
          WHERE a.cont_lotes_pago = $id 
          GROUP BY LPAD(IFNULL(b.cta_bco, '0'), 20, '0'), RPAD(replace(b.ci_rif, '-', ''), 10, '0'), RPAD(RTRIM(b.nombre), 271, ' ');";
} 
else {
  $sql = "SELECT 
            LPAD(b.cta_bco, 20, '0') AS cta_bco, replace(b.ci_rif, '-', '') AS ci_rif, RPAD(RTRIM(b.nombre), 271, ' ') AS nombre, a.monto_a_pagar 
          FROM 
            cont_lotes_pagos_detalle AS a 
            LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
          WHERE a.cont_lotes_pago = $id;";

}
$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) {
  $ref++;
  $total_detalle = str_pad(str_replace(".", "", str_replace(",", "", $row["monto_a_pagar"])), 15, "0", STR_PAD_LEFT);
  $cta_bco = $row["cta_bco"];
  $sudeban = substr($cta_bco, 0, 4);
  $ci_rif = $row["ci_rif"];
  $nombre = $row["nombre"];
  $tipo = $sudeban == "0134" ? "42" : "425";
  echo "0300" . str_pad($ref, 6, "0", STR_PAD_LEFT) . str_repeat(" ", $hspace1) . $total_detalle . "VES" . $cta_bco . str_repeat(" ", $dspace1) . $sudeban . str_repeat(" ", $dspace1) . $ci_rif . str_repeat(" ", $hspace2) . $nombre . $tipo . "\n";
}

$fspace1 = "0000000000001";
echo "0600" . $fspace1 . str_pad($cantidad, 15, "0", STR_PAD_LEFT) . $total . "\n";

include "desconnect.php";
?>
