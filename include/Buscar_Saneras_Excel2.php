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
			d.id, 
			CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' ')) AS articulo, 
			g.id AS codigo, a.nro_documento, DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
			g.nombre AS cliente, 
			SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento, SUM(ABS(b.precio)) AS monto  
		FROM 
			salidas AS a 
			JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
			LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
			LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
			LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
		WHERE 
			a.tipo_documento = 'TDCNET' AND a.estatus = 'PROCESADO' 
			AND g.web = 'S'
			AND a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' AND a.ESTATUS = 'PROCESADO' 
						AND a.cliente IN (SELECT 
												a.cliente 
											FROM 
												salidas AS a 
												JOIN entradas_salidas AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
												LEFT OUTER JOIN cliente AS c ON c.id = a.cliente 
											WHERE a.tipo_documento = 'TDCNET' AND c.tarifa = 3 
											AND a.fecha BETWEEN '$fecha 00:00:00' AND '$fecha2 23:59:59' AND a.ESTATUS = 'PROCESADO' 
											GROUP BY a.cliente HAVING COUNT(a.cliente) >= $cantidad )
		GROUP BY d.id, g.id, a.nro_documento, DATE_FORMAT(a.fecha, '%d/%m/%Y'), g.nombre 
		ORDER BY g.nombre, a.nro_documento, articulo;"; 
$rs = mysqli_query($link, $sql);

$out = '<table class="table table-hover table-dark">
  <thead>
    <tr>
      <th scope="col">CLIENTE</th>
      <th scope="col">FECHA</th>
      <th scope="col">DOCUMENTO</th>
      <th scope="col">ARTICULO</th>
      <th scope="col">CANTIDAD</th>
      <th scope="col">MONTO</th>
    </tr>
  </thead>
  <tbody>';
$i = 1;
$id = "VENTAS POR CLIENTE";
while($row = mysqli_fetch_array($rs)) {
	$out .= '<tr>';
		$out .= '<th scope="row">' . $row["cliente"]  . '</th>';
		$out .= '<td>' . $row["fecha"] . '</td>';
		$out .= '<td>' . $row["nro_documento"] . '</td>';
		$out .= '<td>' . $row["articulo"] . '</td>';
		$out .= '<td>' . $row["cantidad_movimiento"] . '</td>';
		$out .= '<td>' . $row["monto"] . '</td>';
	$out .= '</tr>';
	$i++;
}
$out .= '</tbody>
</table>';

echo $out;
?>
