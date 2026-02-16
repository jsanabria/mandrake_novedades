<?php 
if($tipo != "") $where = "AND d.codigo_ims IN ('$tipo')"; 

	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">ARTICULO</th>';
		  $out .= '<th scope="col">CANTIDAD</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT 
					d.id,
					d.codigo_ims AS codigo,
					CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')) AS articulo, 
					SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento  
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.id_documento = a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					b.tipo_documento IN ('TDCNET') AND a.estatus = 'PROCESADO'
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					$where 
				GROUP BY d.id, CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' '))   
				ORDER BY d.codigo_ims ASC;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}
	$contar = 0;
	$unidad = 0;
	while( $row = mysqli_fetch_array($rs) ) {
		$out .= '<tr>';
		  $out .= '<td>' . $row["codigo"] . '</td>';
		  $out .= '<td><a href="ListadoMasterGeneral?id=' . str_replace(" CANTIDADES", "", $id) . '&codigo=' . $row["id"] . '&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '" target="_blank">' . $row["articulo"] . '</a></td>';
		  $out .= '<td class="text-right">' . number_format($row["cantidad_movimiento"], 0, ",", ".") . '</td>';
		$out .= '</tr>';
		$contar++;
		$unidad += intval($row["cantidad_movimiento"]);
	}
	$out .= '<tr>
				<th colspan="3" class="text-right">Art&iacute;culos: ' . number_format($contar, 0, "", ".") . ' - Total Unidades ' . number_format($unidad, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>