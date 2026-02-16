<?php 
	if($tipo != "") $where = "RTRIM(a.codigo_ims) = RTRIM('$tipo')";
	else $where = "1";
	// LPAD(a.nro_documento, 12, '0') AS codigo,  
	$sql = "SELECT 
					art.codigo_ims AS CODIGO, art.principio_activo AS ARTICULO, 
					'UNIDAD' AS UNIDAD_MEDIDA, 
					-- art.id, art.codigo, art.nombre AS laboratorio, 
					-- art.presentacion, art.nombre_comercial, 
					ent.cantidad AS entradas, ABS(sal.cantidad) AS salidas, 
					(ent.cantidad - ABS(sal.cantidad)) AS existencia 
				FROM 
					(
						SELECT 
							a.id, a.codigo, a.codigo_ims, b.nombre, 
							'UNIDAD' AS unidad_medida, a.principio_activo, 
							a.presentacion, a.nombre_comercial 
						FROM 
							articulo AS a 
							LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante  
						WHERE 
							$where 
					) AS art 
					LEFT OUTER JOIN 
					(
						SELECT 
							a.articulo, SUM(a.cantidad_movimiento) AS cantidad  
						FROM 
							entradas_salidas AS a 
							JOIN salidas AS b ON
								b.tipo_documento = a.tipo_documento
								AND b.id = a.id_documento 
							JOIN almacen AS c ON
								c.codigo = a.almacen AND c.movimiento = 'S' 
						WHERE
							a.tipo_documento IN ('TDCNET', 'TDCASA') 
							AND b.estatus <> 'ANULADO' AND b.activo = 'S' AND 
							b.fecha < '$fecha_hasta 23:59:59' 
						GROUP BY a.articulo
					) AS sal ON sal.articulo = art.Id 
					LEFT OUTER JOIN 
					(
						SELECT 
							a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
						FROM 
							entradas_salidas AS a 
							JOIN entradas AS b ON
								b.tipo_documento = a.tipo_documento
								AND b.id = a.id_documento 
							JOIN almacen AS c ON
								c.codigo = a.almacen AND c.movimiento = 'S'
						WHERE
							((a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
							AND b.estatus = 'PROCESADO') OR 
							(a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
							AND b.estatus <> 'ANULADO') AND b.consignacion = 'S') AND 
							b.fecha < '$fecha_hasta 23:59:59' 
						GROUP BY a.articulo
					) AS ent ON ent.articulo = art.Id 
				WHERE ent.cantidad >= 0 OR ABS(sal.cantidad) > 0 
				ORDER BY art.codigo_ims;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
	}

	$filename = "INVENTARIO_" . date('Ymd') . ".xls";
?>