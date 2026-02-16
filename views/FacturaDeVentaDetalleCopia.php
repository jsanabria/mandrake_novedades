<?php

namespace PHPMaker2021\mandrake;

// Page object
$FacturaDeVentaDetalleCopia = &$Page;
?>
<?php
$id = $_REQUEST["id"];
$tipo_documento = $_REQUEST["tipo_documento"];

$documento = substr($_REQUEST["documento"], 0, 2);
$codigo = substr($_REQUEST["documento"], 2, 3);

$username = CurrentUserName();
$estatus = "NUEVO";

// *** Ajuste - falta generar y probar. Nota Revisar el reporte ** //
if($documento == "FC") {
	$sql = "INSERT INTO salidas
			(id, tipo_documento, username, nro_documento, 
			fecha, cliente, monto_total, alicuota_iva, iva, total,
			nota, estatus, asesor, moneda,
			id_documento_padre, tasa_dia, monto_usd, dias_credito, fecha_bultos, fecha_despacho)
		SELECT
			NULL, '$tipo_documento', '$username', 'NULL', 
			NOW(), cliente, 0, 0, 0, 0,
			nota, '$estatus', asesor, moneda,
			id_documento_padre, tasa_dia, monto_usd, dias_credito, NOW(), NOW() 
		FROM
			salidas 
		WHERE
			id = '$id';";
}
else {
	$sql = "INSERT INTO salidas
			(id, tipo_documento, username, nro_documento, 
			fecha, cliente, monto_total, alicuota_iva, iva, total,
			nota, estatus, asesor, moneda,
			tasa_dia, monto_usd, fecha_bultos, fecha_despacho) 
		SELECT
			NULL, '$tipo_documento', '$username', 'NULL', 
			NOW(), cliente, 0, 0, 0, 0,
			nota, '$estatus', asesor, moneda,
			tasa_dia, monto_usd, NOW(), NOW() 
		FROM
			salidas 
		WHERE
			id = '$id';";
}
Execute($sql);
/****************/

$sql = "SELECT LAST_INSERT_ID();";
$newid = ExecuteScalar($sql);

//////////////////////////////////////////

		$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '$codigo';";
		$row = ExecuteRow($sql);
		$numero = intval($row["valor1"]) + 1;
		$prefijo = trim($row["valor2"]);
		$padeo = intval($row["valor3"]);
		$factura = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
		$sql = "UPDATE parametro SET valor1='$numero' 
			WHERE codigo = '$codigo';";
		Execute($sql);

		//// Nro Ctrol ////
		// Tomo el siguiente número de control de factura
		// Pregunto si el consecutivo del Nro de Control de factura es el mismo
		// Para Notas de Débito y Nota de Crédito
		$sql = "SELECT valor1 FROM parametro WHERE codigo = '035';";
		if(ExecuteScalar($sql) == "S") {
			$codigoCRTL = "030";
		}
		else {
			switch($codigo) {
			case "003":
				$codigoCRTL = "030";
				break;
			case "010":
				$codigoCRTL = "031";
				break;
			case "011":
				$codigoCRTL = "032";
				break;
			}
		}

		$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '$codigoCRTL';";
		$row = ExecuteRow($sql);
		$numero = intval($row["valor1"]) + 1;
		$prefijo = trim($row["valor2"]);
		$padeo = intval($row["valor3"]);
		$facturaCTRL = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
		$sql = "UPDATE parametro SET valor1='$numero' 
				WHERE codigo = '$codigoCRTL';";
				Execute($sql);
		///////////////////
		
		$sql = "SELECT nro_documento FROM salidas WHERE id = $id";
		$NF = ExecuteScalar($sql);

		if($documento == "FC") {
			$sql = "UPDATE salidas SET nro_documento='$factura', documento = '$documento', nro_control = '$facturaCTRL' 
					WHERE id = '$newid';";
		}
		else {
			$sql = "UPDATE salidas SET nro_documento='$factura', nota='Documento Asociado: $NF', documento = '$documento', doc_afectado = '$NF', nro_control = '$facturaCTRL'  
					WHERE id = '$newid';";
		}
		Execute($sql);

		
//////////////////////////////////////////

$sql = "INSERT INTO entradas_salidas
			(id, tipo_documento, id_documento, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, alicuota, precio_unidad, precio, lote, fecha_vencimiento,
			descuento, precio_unidad_sin_desc, costo_unidad, costo, id_compra) 
		SELECT
			NULL, tipo_documento, $newid, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, alicuota, precio_unidad, precio, lote, fecha_vencimiento,
			descuento, precio_unidad_sin_desc, costo_unidad, costo, id_compra 
		FROM
			entradas_salidas
		WHERE
			tipo_documento = '$tipo_documento' AND id_documento = '$id';";
Execute($sql);

/* ------- Actualizo cantidad en mano, en pedido y en transito  ------- */
// ActualizarExitencia();

header("Location: SalidasEdit?showdetail=entradas_salidas&id=$newid&tipo=$tipo_documento");
exit();
?>

<?= GetDebugMessage() ?>
