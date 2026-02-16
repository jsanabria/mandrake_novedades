<?php 
	if($tipo != "") $where = "AND d.codigo_ims IN ('$tipo')"; 
	else $where = "";

	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '&nbsp; &nbsp; <a href="reportes/canjes_por_articulo.php?fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '" target="_blank" class="btn btn-primary">Imprimir</a>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">ARTICULO</th>';
		  $out .= '<th scope="col">CANTIDAD</th>';
		  $out .= '<th scope="col">COSTO UND.</th>';
		  $out .= '<th scope="col">COSTO TOTAL</th>';
		  $out .= '<th scope="col">PRECIO UND.</th>';
		  $out .= '<th scope="col">PRECIO TOTAL</th>';
		  $out .= '<th scope="col">UTILIDAD % POR ART.</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';
// CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')) AS articulo, 
	$sql = "SELECT 
					d.id,
					d.codigo_ims AS codigo,
					d.principio_activo AS articulo, 
					SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento, 
					d.ultimo_costo AS costo_unidad, SUM(b.cantidad_articulo*d.ultimo_costo) AS costo, 
					b.precio_unidad-(b.precio_unidad*(IFNULL(a.descuento, 0)/100)) AS precio_unidad, 
					SUM(IFNULL(b.precio, 0)-(IFNULL(b.precio, 0)*(IFNULL(a.descuento, 0)/100))) AS precio, 
					(((IFNULL(b.precio_unidad, 0)-(IFNULL(b.precio_unidad, 0)*(IFNULL(a.descuento, 0)/100)))-IFNULL(d.ultimo_costo, 0))/(IFNULL(b.precio_unidad, 0)-(IFNULL(b.precio_unidad, 0)*(IFNULL(a.descuento, 0)/100))))*100 AS utilidad 
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.id_documento = a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					b.tipo_documento IN ('TDCNET') AND a.estatus = 'PROCESADO' AND IFNULL(a.pago_premio, 'N') = 'S' 
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					$where 
				GROUP BY d.id, CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')), d.ultimo_costo, b.precio_unidad, a.descuento    
				ORDER BY d.codigo_ims ASC;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}
	$contar = 0;
	$unidad = 0;
	$costo = 0;
	$precio = 0;
	while( $row = mysqli_fetch_array($rs) ) {
		$out .= '<tr>';
		  $out .= '<td>' . $row["codigo"] . '</td>';
		  $out .= '<td><a href="ListadoMasterGeneral?id=' . $id . '&codigo=' . $row["id"] . '&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '" target="_blank">' . $row["articulo"] . '</a></td>';
		  $out .= '<td class="text-right">' . number_format($row["cantidad_movimiento"], 0, ",", ".") . '</td>';
		  $out .= '<td class="text-right">' . number_format($row["costo_unidad"], 2, ",", ".") . '</td>';
		  $out .= '<td class="text-right">' . number_format($row["costo"], 2, ",", ".") . '</td>';
		  $out .= '<td class="text-right">' . number_format($row["precio_unidad"], 2, ",", ".") . '</td>';
		  $out .= '<td class="text-right">' . number_format($row["precio"], 2, ",", ".") . '</td>';
		  $out .= '<td class="text-right">' . number_format($row["utilidad"], 2, ",", ".") . '</td>';
		$out .= '</tr>';
		$contar++;
		$unidad += intval($row["cantidad_movimiento"]);
		$costo += floatval($row["costo"]);
		$precio += floatval($row["precio"]);
	}
	$out .= '<tr>
				<th colspan="4" class="text-right">Art&iacute;culos: ' . number_format($contar, 0, "", ".") . ' - Total Unidades ' . number_format($unidad, 0, "", ".") . '</th>
				<th class="text-right">' . number_format($costo, 2, ",", ".") . '</th>
				<th class="text-center"></th>
				<th class="text-right">' . number_format($precio, 2, ",", ".") . '</th>
				<th class="text-center"></th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>