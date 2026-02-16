<?php 
	if($tipo != "") $where = "AND b.fabricante IN ($tipo)";
	$sql = "SELECT
					f.descripcion AS almacen, c.nombre AS fabricante, 
					d.codigo, d.principio_activo, d.presentacion, 
					d.nombre_comercial, 
					e.descripcion AS unidad_medida, 
					ABS(SUM(b.cantidad_movimiento)) AS cantidad 
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.id_documento = a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN unidad_medida AS e ON e.codigo = b.articulo_unidad_medida 
					LEFT OUTER JOIN almacen AS f ON f.codigo = b.almacen 
				WHERE 
					a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					AND a.estatus  = 'PROCESADO' AND b.tipo_documento IN ('TDCFCV','TDCASA') 
					AND IFNULL(a.documento, '') = 'FC' 
					$where 
				GROUP BY 
					d.codigo, f.descripcion, c.nombre, d.principio_activo, 
					d.presentacion, d.nombre_comercial, e.descripcion 
				ORDER BY fabricante, cantidad DESC;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}


	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
	}

	$filename = "VENTAS_LABORATORIO_" . date('Ymd') . ".xls";

?>