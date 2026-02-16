<?php 
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=IngresoCaja.xls");
header("Pragma: no-cache");
header("Expires: 0");

include 'connect.php';
$fecha_d = $_REQUEST["xfecha"];
$fecha_h = $_REQUEST["yfecha"];

$fecha = explode("/", $fecha_d);
$fecha_desde = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];

$fecha = explode("/", $fecha_h);
$fecha_hasta = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];

$out = '';	

$out .= '<h4><b>INGRESO DE CAJA </b></h4>';
$out .= '<h4>Desde: ' . $fecha_d . ' Hasta: ' . $fecha_h . '</h4>';

	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">FECHA</th>';
		  $out .= '<th scope="col">TIPO DE PAGO</th>';
		  $out .= '<th scope="col">MONTO Bs.</th>';
		  $out .= '<th scope="col">MONTO USD</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';


	$sql = "SELECT 
			DATE_FORMAT(aa.fecha, '%Y/%m/%d') AS fecha, 
			CONCAT(bb.valor2, ' - ', aa.moneda) AS metodo_pago, 
			SUM(aa.monto_bs) AS monto_bs, 
			SUm(aa.monto_usd) AS monto_usd 
		FROM (
		SELECT 
			'NOTA DE ENTREGA' AS tipo, 
			a.metodo_pago, 
			a.moneda, 
			c.nro_documento AS doc, 
			d.nombre AS cliente,  
			a.monto_bs AS monto_bs, a.monto_usd AS monto_usd, a.referencia, c.fecha   
		FROM 
			cobros_cliente_detalle AS a 
			JOIN cobros_cliente AS b ON b.id = a.cobros_cliente 
			LEFT OUTER JOIN salidas AS c ON c.id = b.id_documento 
			LEFT OUTER JOIN cliente AS d ON d.id = b.cliente 
		WHERE 
			a.metodo_pago NOT IN ('RC', 'PF', 'PC', 'DV', 'NC', 'ND') 
			AND b.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
			AND c.estatus = 'PROCESADO' 
		UNION ALL 
		SELECT 
			'RECIBO' AS TIPO, 
			a.metodo_pago, 
			a.moneda, 
			LPAD(a.nro_recibo, 7, '0') AS doc, 
			b.nombre AS cliente, 
			a.monto_bs AS monto_bs, 
			a.monto_usd AS monto_usd, a.referencia, a.fecha 
		FROM 
			recarga AS a 
			LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
		WHERE 
			a.metodo_pago NOT IN ('RC', 'PF', 'PC', 'DV', 'NC', 'ND') 
			AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
			AND (a.monto_usd > 0 OR a.reverso = 'S') 
		) AS aa 
		LEFT OUTER JOIN parametro AS bb ON bb.valor1 = aa.metodo_pago 
		WHERE bb.codigo = '009' AND aa.metodo_pago NOT IN ('RC', 'PF', 'PC', 'DV', 'NC', 'ND') 
		GROUP BY DATE_FORMAT(aa.fecha, '%Y/%m/%d'), CONCAT(bb.valor2, ' - ', aa.moneda) 
		ORDER BY DATE_FORMAT(aa.fecha, '%Y/%m/%d');";  

	$rs = mysqli_query($link, $sql);
	$TotBs = 0;
	$TotUSD = 0;
  	while($row = mysqli_fetch_array($rs)) {
  		$fecha = $row["fecha"]; 
		$metodo_pago = $row["metodo_pago"]; 
		$monto_bs = $row["monto_bs"]; 
		$monto_usd = $row["monto_usd"]; 


		$out .= '<tr>';
		  $out .= '<td align="center">' . $fecha . '</td>';
		  $out .= '<td>' . $metodo_pago . '</td>';
		  $out .= '<td>' . number_format($monto_bs, 2, ',', '.') . '</td>';
		  $out .= '<td>' . number_format($monto_usd, 2, ',', '.') . '</td>';
		$out .= '</tr>';

		$TotBs += $monto_bs;
		$TotUSD += $monto_usd;
	}
	$out .= '<tr>
				<td colspan="2"></td>
				<td>' . number_format($TotBs, 2, ',', '.') . '</td>
				<td>' . number_format($TotUSD, 2, ',', '.') . '</td>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';

	echo $out;
?>