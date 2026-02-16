<?php 
if(isset($_REQUEST["toexcel"])) {
  if($_REQUEST["toexcel"]=="SI") {
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=DevolucionesoEntreFechas.xls");
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

	if($tipo != "") $where = "AND a.almacen = '$tipo'";
	
	// $out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';


$out .= '<h4><b><a target="_blank" href="include/devoluciones_entre_fecha.php?toexcel=SI&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '&id=' . $id . '&rif=' . $rif . '&cia=' . $cia . '">DEVOLUCIONES ENTRE FECHAS EXPORTAR A EXCEL</a></b></h4>';
$out .= '<h4>' . $cia . ' ' . $rif  . '</h4>';
$out .= '<h4>Desde: ' . $fecha_desde . ' Hasta: ' . $fecha_hasta . '</h4>';

	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">CODIGO</th>';
		  // $out .= '<th scope="col">MARCA</th>';
		  $out .= '<th scope="col">ARTICULO</th>';
		  //$out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">UNIDAD</th>';
		  $out .= '<th scope="col">DEVUELTAS</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$contar = 0;
	$cnt = 0;

	$sql = "SELECT 
				art.id, art.codigo, art.codigo_ims, art.nombre AS laboratorio, 
				'UNIDAD' AS unidad_medida, art.principio_activo, 
				art.presentacion, art.nombre_comercial, 
				IFNULL(ent.cantidad, 0) AS entradas 
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
						0 = 0
				) AS art 
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
						b.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' AND IFNULL(b.nota, '') = 'DEVOLUCION DE ARTICULO' AND IFNULL(cliente, 0) > 0 
						$where 
					GROUP BY a.articulo
				) AS ent ON ent.articulo = art.Id 
			WHERE ent.cantidad >= 0 ORDER BY art.codigo_ims ASC;";  
	$rs = mysqli_query($link, $sql);
	$unidades = 0;
  	while($row = mysqli_fetch_array($rs)) {
  		$idArt = $row["id"]; 
		$laboratorio = $row["laboratorio"]; 
		$codigo = $row["codigo"]; 
		$codigo_ims = $row["codigo_ims"]; 
		$nombre = $row["nombre_comercial"] . ' ' . $row["principio_activo"] . ' ' . $row["presentacion"]; 
		$unidad_medida = $row["unidad_medida"];
		$entradas = $row["entradas"];


		$out .= '<tr>';
		  $out .= '<td align="center">' . $codigo_ims . '</td>';
		  // $out .= '<td>' . $laboratorio . '</td>';
		  $out .= '<td><a href="ListadoMasterGeneral?id=DEVOLUCIONES ENTRE FECHA&codigo=' . $idArt . '&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '" target="_blank">' . $nombre . '</a></td>';
		  // $out .= '<td>' . $codigo . '</td>';
		  $out .= '<td>' . $unidad_medida . '</td>';
		  $out .= '<td>' . number_format($entradas, 0, ',', '.') . '</td>';
		$out .= '</tr>';

		$cnt++;
	}
	$out .= '<tr>
				<th colspan="4" class="text-right">Total Items: ' . number_format($cnt, 0, "", ".") . ' - Total Unidades: ' . number_format($unidades, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';

	$id = $reporte;

	if(isset($_REQUEST["toexcel"])) {
		echo $out;
	}
?>