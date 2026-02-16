<?php 
	if(trim($tipo) != "") $where = " AND a.documento = '$tipo' ";
	if(trim($cliente) != "") $where .= " AND a.cliente = '$cliente' ";
	if(trim($asesor) != "") $where .= " AND c.asesor = '$asesor'" ;


	$sql = "SELECT 
				a.id, 
				a.`tipo_documento`, 
				IF(a.documento = 'NC', '', IF(a.documento = 'ND', CONCAT('ND-', REPLACE(a.nro_documento, 'ND-', '')), a.nro_documento)) AS nro_documento,  
				IF(a.documento = 'NC', a.nro_documento, '') AS nota_credito, 
				a.`documento`, 
				REPLACE(a.doc_afectado, 'FACT-', '') AS afectado, 
				a.`nro_control`, 
				b.`nombre` AS cliente, 
				b.`ci_rif`, 
				date_format(a.fecha, '%d/%m/%Y') AS fecha, 
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
				a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' $where ORDER BY a.nro_control;"; 
	$rs = mysqli_query($link, $sql); 
	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		/*var_dump($row);
		echo "<br><br>" . $row["fecha"];
		
		die();*/

		$desc = floatval($row["descuento"]);

		$sql = "SELECT SUM(IF(IFNULL(alicuota, 0)=0, precio, 0)) AS exenta FROM entradas_salidas 
				WHERE id_documento = " . $row["id"] . " AND tipo_documento = '" . $row["tipo_documento"] . "'";
		$rs3 = mysqli_query($link, $sql) or die(mysqli_error());
		$row2 = mysqli_fetch_array($rs3);
		$exentas = trim($row["estatus"])=="ANULADO" ? "" : ($row2["exenta"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*($row2["exenta"] - ($row2["exenta"]*($desc/100))), 2, ",", "."));

		$sql = "SELECT SUM(IF(alicuota>0, precio, 0)) AS gravable FROM entradas_salidas 
				WHERE id_documento = " . $row["id"] . " AND tipo_documento = '" . $row["tipo_documento"] . "'";
		$rs3 = mysqli_query($link, $sql) or die(mysqli_error());
		$row2 = mysqli_fetch_array($rs3);
		$base = trim($row["estatus"])=="ANULADO" ? "" : ($row2["gravable"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*($row2["gravable"] - ($row2["gravable"]*($desc/100))), 2, ",", "."));

		$sql = "SELECT MAX(alicuota) AS alicuota_iva FROM entradas_salidas 
				WHERE id_documento = " . $row["id"] . " AND tipo_documento = '" . $row["tipo_documento"] . "'";
		$rs3 = mysqli_query($link, $sql) or die(mysqli_error());
		$row2 = mysqli_fetch_array($rs3);
		$porcentaje = (trim($row["estatus"])=="ANULADO" ? " " : $row2["alicuota_iva"]==0) ? "" : number_format($row2["alicuota_iva"], 2, ",", ".");
		$impuesto = trim($row["estatus"])=="ANULADO" ? "" : ($row["iva"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["iva"], 2, ",", "."));

		$array = [
				    "Fecha" => $row["fecha"],
				    "Factura" => str_replace("FACT-", "", $row["nro_documento"]),
				    "NC" => str_replace("NC-", "", $row["nota_credito"]),
				    "Afectado" => $row["afectado"],
				    "Nro_Control" => $row["nro_control"],
				    "Razon_Social" => (trim($row["estatus"])=="ANULADO" ? "ANULADA" : $row["cliente"]),
				    "RIF" => (trim($row["estatus"])=="ANULADO" ? "" : $row["ci_rif"]),
				    "Total_ventas" => trim($row["estatus"])=="ANULADO" ? "" : ($row["total"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["total"], 2, ",", ".")),
				    "Total_Exentas" => $exentas,
				    "Base" => $base,
				    "Alicuota" => $porcentaje,
				    "Impuesto" => $impuesto,
				    "Asesor" => $row["usuario"],
				];
		$developer_records[] = $array;
	}
	
	$filename = "LIBRO_VENTAS_" . date('Ymd') . ".xls";
?>