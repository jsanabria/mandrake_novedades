<?php 
	if($tipo != "") $where = ""; // $where = "AND b.ciudad = '$tipo'";
	
	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">SANERA</th>';
		  $out .= '<th scope="col">CLIENTE</th>';
		  $out .= '<th scope="col">FACTURAS</th>';
		  $out .= '<th scope="col">TOTAL</th>';
		  $out .= '<th scope="col">UNIDADES</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT 
				IF(IFNULL(b.web, 'N') = 'S', 'SI', 'NO') AS sanera, 
				b.nombre AS cliente, a.cliente as codcli, 
				COUNT(a.nro_documento) AS facturas, 
				SUM(a.monto_total) AS total, SUM(a.unidades) AS unidades  
			FROM 
				salidas AS a 
				LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
			WHERE 
				a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
				AND a.estatus  = 'PROCESADO' AND a.tipo_documento = 'TDCNET' 
				$where 
			GROUP BY 
				b.nombre, a.cliente  
			ORDER BY 5 DESC;"; 

	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}
	$contar = 0;
	$facturas = 0; 
	$monto = 0.00;
	$unidades = 0;
	while( $row = mysqli_fetch_array($rs) ) {
		$out .= '<tr>';
		  $out .= '<td>' . $row["sanera"] . '</td>';
		  $out .= '<td><a href="ListadoMasterGeneral?id=' . $id . '&codigo=' . $row["codcli"] . '&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '" target="_blank">' . $row["cliente"] . '</a></td>';		
		  // $out .= '<td>' . $row["cliente"] . '</td>';
		  $out .= '<td class="text-right">' . $row["facturas"] . '</td>';
		  $out .= '<td class="text-right">' . number_format($row["total"], 2, ",", ".") . '</td>';
		  $out .= '<td class="text-right">' . number_format($row["unidades"], 0, "", ".") . '</td>';
		$out .= '</tr>';
		$contar++;
		$facturas += intval($row["facturas"]);
		$monto += floatval($row["total"]);
		$unidades += intval($row["unidades"]);
	}
	$out .= '<tr>
				<th colspan="1" class="text-right">Clientes: ' . number_format($contar, 0, "", ".") . '</th>
				<th class="text-right">' . number_format($facturas, 0, "", ".") . '</th>
				<th class="text-right">' . number_format($monto, 2, ",", ".") . '</th>
				<th class="text-right">' . number_format($unidades, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>