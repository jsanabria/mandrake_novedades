<?php 
if($tipo != "") $where = "AND d.codigo_ims IN ('$tipo')"; 
	$sql = "SELECT 
					d.id,
					d.codigo_ims AS codigo, 
					SUBSTRING(RPAD(LTRIM(REPLACE(REPLACE(CONCAT(REPLACE(IFNULL(d.principio_activo, ' '), '\t', ''), ' ', REPLACE(IFNULL(d.presentacion, ' '), '\n', '')), '\n', ''), '\r', '')), 32, ' '), 1, 32) AS nombre, 
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
					b.tipo_documento IN ('TDCNET') AND a.estatus = 'PROCESADO' AND IFNULL(a.pago_premio, 'N') = 'N' 
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					$where 
				GROUP BY d.id, CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')), d.ultimo_costo, b.precio_unidad, a.descuento 
				ORDER BY d.codigo_ims ASC;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
	}

	$filename = "VENTAS_ARTICULO_" . date('Ymd') . ".xls";

?>