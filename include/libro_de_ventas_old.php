<?php  
	if(trim($tipo) != "") $where = " AND a.documento = '$tipo' ";
	if(trim($cliente) != "") $where .= " AND a.cliente = '$cliente' ";
	if(trim($asesor) != "") $where .= " AND c.asesor = '$asesor' ";

	$contar = 0; 

	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button> <a class="btn btn-primary" href="reportes/libro_de_ventas.php?xfecha=' . $fecha_desde . '&yfecha=' . $fecha_hasta . '" target="_blank">Imprimir Libro de Ventas</a>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">FACTURA</th>';
		  $out .= '<th scope="col">NOTA CREDITO</th>';
		  $out .= '<th scope="col">NRO CONTROL</th>';
		  $out .= '<th scope="col">FECHA</th>';
		  $out .= '<th scope="col">NOMBRE O RAZON SOCIAL</th>';
		  $out .= '<th scope="col">RIF NRO</th>';
		  $out .= '<th scope="col">TOTAL VENTAS</th>';
		  $out .= '<th scope="col">VENTAS EXENTAS</th>';
		  $out .= '<th scope="col">BASE</th>';
		  $out .= '<th scope="col">%</th>';
		  $out .= '<th scope="col">IMPUESTO</th>';
		  $out .= '<th scope="col">IVA RETENIDO 75%</th>';
		  $out .= '<th scope="col">ASESOR</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT 
				a.id, 
				a.`tipo_documento`, 
				IF(a.documento = 'NC', '', IF(a.documento = 'ND', CONCAT('ND-', REPLACE(a.nro_documento, 'ND-', '')), a.nro_documento)) AS nro_documento,  
				IF(a.documento = 'NC', a.nro_documento, '') AS nota_credito, 
				REPLACE(a.doc_afectado, 'FACT-', '') AS afectado, 
				a.`documento`, 
				a.`nro_control`, 
				b.`nombre` AS cliente, 
				b.`ci_rif`, 
				DATE_FORMAT(a.`fecha`, '%d/%m/%Y') AS fecha, 
				a.`total`, 
				a.`iva`, 
				a.`estatus`, a.descuento, 
				c.nombre AS usuario, d.nombre AS asesor  
		FROM 
			salidas AS a 
			LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
			LEFT OUTER JOIN usuario AS c ON c.username = a.asesor 
			LEFT OUTER JOIN asesor AS d ON d.id = c.asesor 
		WHERE 
			a.tipo_documento = 'TDCFCV' AND 
			a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
			$where ORDER BY a.nro_control;"; 
	$rs = mysqli_query($link, $sql);

	$exenta = 0;
	$gravable = 0;
	$alicuota_iva = 0;
	while($row = mysqli_fetch_array($rs)) {
  		/*
					(SELECT SUM(IF(alicuota=0, precio, 0)) 
					FROM entradas_salidas WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS exenta, 
					(SELECT SUM(IF(alicuota>0, precio, 0)) 
					FROM entradas_salidas WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS gravable, 
					(SELECT MAX(alicuota) 
					FROM entradas_salidas WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS alicuota_iva,
		*/
		$desc = floatval($row["descuento"]);


		$out .= '<tr>';
		  $out .= '<td scope="col">' . $row["nro_documento"] . '</td>';
		  $out .= '<td scope="col">' . $row["nota_credito"] . '</td>';
		  $out .= '<td scope="col">' . $row["nro_control"] . '</td>';
		  $out .= '<td scope="col">' . $row["fecha"] . '</td>';
		  $out .= '<td scope="col">' . (trim($row["estatus"])=="ANULADO" ? "ANULADA" : $row["cliente"]) . '</td>';
		  $out .= '<td scope="col">' . (trim($row["estatus"])=="ANULADO" ? "" : $row["ci_rif"]) . '</td>';
		  $out .= '<td scope="col">' . number_format((trim($row["estatus"])=="ANULADO" ? 0 : $row["total"]), 2, ".", ",") . '</td>';

		$sql = "SELECT SUM(IF(IFNULL(alicuota, 0)=0, precio, 0)) AS exenta FROM entradas_salidas 
				WHERE id_documento = " . $row["id"] . " AND tipo_documento = '" . $row["tipo_documento"] . "'";
		$rs2 = mysqli_query($link, $sql);
		$row2 = mysqli_fetch_array($rs2);
		$exenta = $row2["exenta"]; 

			  $out .= '<td scope="col">' . number_format((trim($row["estatus"])=="ANULADO" ? 0 : $exenta - ($exenta*($desc/100))), 2, ".", ",") . '</td>';

		$sql = "SELECT SUM(IF(alicuota>0, precio, 0)) AS gravable FROM entradas_salidas 
				WHERE id_documento = " . $row["id"] . " AND tipo_documento = '" . $row["tipo_documento"] . "'";
		$rs2 = mysqli_query($link, $sql);
		$row2 = mysqli_fetch_array($rs2);
		$gravable = $row2["gravable"]; 

			  $out .= '<td scope="col">' . number_format((trim($row["estatus"])=="ANULADO" ? 0 : $gravable - ($gravable*($desc/100))), 2, ".", ",") . '</td>';

		$sql = "SELECT MAX(alicuota) AS alicuota_iva FROM entradas_salidas 
				WHERE id_documento = " . $row["id"] . " AND tipo_documento = '" . $row["tipo_documento"] . "';";
		$rs2 = mysqli_query($link, $sql);
		$row2 = mysqli_fetch_array($rs2);
		$alicuota_iva = $row2["alicuota_iva"]; 

		  $out .= '<td scope="col">' . $alicuota_iva . '</td>';
		  $out .= '<td scope="col">' . number_format((trim($row["estatus"])=="ANULADO" ? 0 : $row["iva"]), 2, ".", ",") . '</td>';
		  $out .= '<td scope="col">0.00</td>';
		  $out .= '<td scope="col">' . $row["usuario"] . '</td>';
		$out .= '</tr>';

		$contar++;
		if($contar >= 20) {
			$out .= '<tr>
				<th colspan="13" class="text-right">Se visualizan ' . $contar . ' registros - Exportar para ver todos los registros...</th>
			</tr>';
			break;
		}
	}
	$out .= '<tr>
				<th colspan="13" class="text-right">
					<a class="btn btn-primary" href="reportes/libro_de_ventas.php?xfecha=' . $fecha_desde . '&yfecha=' . $fecha_hasta . '" target="_blank">Imprimir Libro de Ventas</a> 
					Items: ' . number_format($contar, 0, "", ".") . '
				</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>