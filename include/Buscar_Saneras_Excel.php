<?php 
session_start();

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Saneras.xls");
header("Pragma: no-cache");
header("Expires: 0");

include "connect.php";

$cantidad = intval($_REQUEST["cantidad"]); 
// $tarifa = intval($_REQUEST["tarifa"]); 
$fecha = isset($_REQUEST["fecha"]) ? $_REQUEST["fecha"] : date("Y-m-d"); 
$fecha2 = isset($_REQUEST["fecha2"]) ? $_REQUEST["fecha2"] : date("Y-m-d"); 

$fecha = $fecha == "" ? date("Y-m-d") : $fecha; 
$fecha2 = $fecha2 == "" ? date("Y-m-d") : $fecha2; 

// die($fecha . " | " . $fecha2);

$sql = "SELECT 
			a.cliente, SUM(b.cantidad_articulo) AS cantidad, SUM(b.precio) AS monto, c.nombre, c.ci_rif 
		FROM 
			salidas AS a 
			JOIN entradas_salidas AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
			LEFT OUTER JOIN cliente AS c ON c.id = a.cliente 
		WHERE a.tipo_documento = 'TDCNET' AND c.web = 'S' AND a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' AND a.ESTATUS = 'PROCESADO' 
		GROUP BY a.cliente HAVING COUNT(a.cliente) >= $cantidad ORDER BY 3 desc"; 
$rs = mysqli_query($link, $sql);

$out = '<table class="table table-hover table-dark">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">SANERA</th>
      <th scope="col">CEDULA</th>
      <th scope="col">VENTAS</th>
      <th scope="col">MONTO</th>
    </tr>
  </thead>
  <tbody>';
$i = 1;
$id = "VENTAS POR CLIENTE";
while($row = mysqli_fetch_array($rs)) {
	$out .= '<tr>';
		$out .= '<th scope="row">' . $i . '</th>';
		$out .= '<td><a href="ListadoMasterGeneral?id=' . $id . '&codigo=' . $row["cliente"] . '&fecha_desde=' . $fecha . '&fecha_hasta=' . $fecha2 . '" target="_blank">' . $row["nombre"] . '</a></td>';	
		// $out .= '<td>' . $row["nombre"] . '</td>';
		$out .= '<td>' . $row["ci_rif"] . '</td>';
		$out .= '<td>' . $row["cantidad"] . '</td>';
		$out .= '<td>' . $row["monto"] . '</td>';
	$out .= '</tr>';
	$i++;
}
$out .= '</tbody>
</table>';

echo $out;
?>
