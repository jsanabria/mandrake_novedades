<?php 
if(isset($_REQUEST["toexcel"])) {
  if($_REQUEST["toexcel"]=="SI") {
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=InventarioEntreFechas.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
  }
} 

if(!isset($id)) {
	include 'connect.php';
	$id = $_REQUEST["id"];
	$fecha_desde = $_REQUEST["fecha_desde"];
	$fecha_hasta = $_REQUEST["fecha_hasta"];
	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : "";
	$cia = $_REQUEST["cia"];
	$rif = $_REQUEST["rif"];

	$out = '';	
	$where = "";
}

	$reporte = $id; 

	// if($tipo != "") $where = "AND a.almacen = '$tipo'";
	if($tipo != "") $where = "RTRIM(a.codigo_ims) = RTRIM('$tipo')";
	else $where = "1";
	
	// $out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';


$out .= '<h4><b><a target="_blank" href="include/inventario_entre_fecha.php?toexcel=SI&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '&id=' . $id . '&rif=' . $rif . '&cia=' . $cia . '&tipo=' . $tipo . '">INVENTARIO ENTRE FECHAS EXPORTAR A EXCEL</a></b></h4>';
$out .= '<h4>' . $cia . ' ' . $rif  . '</h4>';
$out .= '<h4>Desde: ' . $fecha_desde . ' Hasta: ' . $fecha_hasta . '&nbsp; &nbsp; <a href="reportes/inventario_entre_fechas.php?fecha=' . $fecha_hasta . '" target="_blank" class="btn btn-primary">Imprimir</a></h4>';

	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">CODIGO</th>';
		  // $out .= '<th scope="col">MARCA</th>';
		  $out .= '<th scope="col">ARTICULO</th>';
		  //$out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">UNIDAD</th>';
		  $out .= '<th scope="col">ENTRADA</th>';
		  $out .= '<th scope="col">SALIDA</th>';
		  $out .= '<th scope="col">EXIST.</th>';
		  $out .= '<th scope="col">COSTO C/U</th>';
		  $out .= '<th scope="col">PRECIO C/U</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$contar = 0;
	$cnt = 0;

	$sql = "SELECT 
				art.id, art.codigo, art.codigo_ims, art.nombre AS laboratorio, 
				'UNIDAD' AS unidad_medida, art.principio_activo, 
				art.presentacion, art.nombre_comercial, 
				IFNULL(dev.cantidad, 0) AS devoluciones, 
				IFNULL(ent.cantidad, 0) AS entradas, ABS(IFNULL(sal.cantidad, 0)) AS salidas, 
				(IFNULL(ent.cantidad, 0) - ABS(IFNULL(sal.cantidad, 0))) AS existencia 
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
						b.fecha < '$fecha_hasta 23:59:59' AND SUBSTRING(RTRIM(IFNULL(b.nro_documento, '')), 1, 8) <> 'ABONO - '  AND IFNULL(cliente, 0) = 0 -- AND IFNULL(b.nota, '') <> 'DEVOLUCION DE ARTICULO' AND IFNULL(cliente, 0) = 0 
					GROUP BY a.articulo
				) AS ent ON ent.articulo = art.Id 
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
						((a.tipo_documento IN ('TDCNRP') 
						AND b.estatus = 'PROCESADO') OR 
						(a.tipo_documento IN ('TDCNRP') 
						AND b.estatus <> 'ANULADO') AND b.consignacion = 'S') AND 
						b.fecha < '$fecha_hasta 23:59:59' AND SUBSTRING(RTRIM(IFNULL(b.nro_documento, '')), 1, 8) = 'ABONO - '  -- AND IFNULL(b.nota, '') = 'DEVOLUCION DE ARTICULO' 
					GROUP BY a.articulo
				) AS dev ON dev.articulo = art.Id 
			ORDER BY art.codigo_ims ASC;";  
				// WHERE ent.cantidad >= 0 OR ABS(sal.cantidad) > 0 ORDER BY art.codigo_ims ASC;";  
	$rs = mysqli_query($link, $sql);
	$unidades = 0;
  	while($row = mysqli_fetch_array($rs)) {
  		$idArt = $row["id"]; 
		$laboratorio = $row["laboratorio"]; 
		$codigo = $row["codigo"]; 
		$codigo_ims = $row["codigo_ims"]; 
		$nombre = $row["nombre_comercial"] . ' ' . $row["principio_activo"] . ' ' . $row["presentacion"]; 
		$unidad_medida = $row["unidad_medida"];
		$devoluciones = $row["devoluciones"];
		$entradas = $row["entradas"];
		$salidas = $row["salidas"];
		$existencia = intval($row["existencia"])+intval($row["devoluciones"]); // $row["existencia"]==0 ? $devoluciones+$entradas-$salidas : $row["devoluciones"]+$row["existencia"];
		$unidades += intval($row["existencia"])+intval($row["devoluciones"]); // $row["existencia"]==0 ? $devoluciones+$entradas-$salidas : $row["devoluciones"]+$row["existencia"];

		$sql = "SELECT ultimo_costo, precio FROM articulo WHERE id = $idArt";
		$rs2 = mysqli_query($link, $sql);
		$row2 = mysqli_fetch_array($rs2);
		$costo = $row2["ultimo_costo"];
		$precio = $row2["precio"];


		$out .= '<tr>';
		  $out .= '<td align="center">' . $codigo_ims . '</td>';
		  // $out .= '<td>' . $laboratorio . '</td>';
		  $out .= '<td>' . $nombre . '</td>';
		  // $out .= '<td>' . $codigo . '</td>';
		  $out .= '<td>' . $unidad_medida . '</td>';
		  $out .= '<td>' . number_format($devoluciones+$entradas, 0, ',', '.') . '</td>';
		  $out .= '<td>' . number_format($salidas, 0, '.', '.') . '</td>';
		  $out .= '<td>' . number_format($existencia, 0, ',', '.') . '</td>';
		  $out .= '<td>' . number_format($costo, 2, ',', '.') . '</td>';
		  $out .= '<td>' . number_format($precio, 2, ',', '.') . '</td>';
		$out .= '</tr>';

		$cnt++;
	}
	$out .= '<tr>
				<th colspan="8" class="text-right">Total Items: ' . number_format($cnt, 0, "", ".") . ' - Total Unidades: ' . number_format($unidades, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';

	$id = $reporte;

	if(isset($_REQUEST["toexcel"])) {
		echo $out;
	}
?>