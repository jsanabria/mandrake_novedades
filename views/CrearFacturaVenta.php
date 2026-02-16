<?php

namespace PHPMaker2021\mandrake;

// Page object
$CrearFacturaVenta = &$Page;
?>
<?php

$id = $_REQUEST["id"];

/*
$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
$tasa = floatval(ExecuteScalar($sql));
*/

$moneda = "Bs.";

$sql = "SELECT tipo_documento, tasa_dia FROM salidas WHERE id = '$id';";
$row = ExecuteRow($sql);
$tipo = $row["tipo_documento"];
$tasa = floatval($row["tasa_dia"]);
$sql = "SELECT valor1 FROM parametro WHERE codigo = '036';";
if(ExecuteScalar($sql) != "S") $tasa = 1;


// Tomo el número de días de crédito por defecto
$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '007';";
$row = ExecuteRow($sql);
$nota = ""; 
$dias_credito = 0; // intval($row["valor1"]);

// Proceso para saber si genero más de una factura para la nota de entrega //
// Consulto la cantidad de lineas por factura
$sql = "SELECT valor1 AS lineas FROM parametro WHERE codigo = '008';";
$LineasFactura = intval(ExecuteScalar($sql));
$LineasFactura = ($LineasFactura == 0 ? 35 : $LineasFactura);

$sql = "SELECT (COUNT(*)/$LineasFactura) AS cantidad FROM entradas_salidas WHERE id_documento = '$id' AND tipo_documento = '$tipo';";
$cantidad = ExecuteScalar($sql);
$deci = abs($cantidad - intval($cantidad));
$cantidad = intval($cantidad);
if($deci > 0) $cantidad++;

$limite = 0;
for($xy = 0; $xy < $cantidad; $xy++) {
	// Tomo el siguiente número de factura
	$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '003';";
	$row = ExecuteRow($sql);
	$numero = intval($row["valor1"]) + 1;
	$prefijo = trim($row["valor2"]);
	$padeo = intval($row["valor3"]);
	$factura = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
	$sql = "UPDATE parametro SET valor1='$numero' 
			WHERE codigo = '003';";
	Execute($sql);

	// Tomo el siguiente número de control de factura
	$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '030';";
	$row = ExecuteRow($sql);
	$numero = intval($row["valor1"]) + 1;
	$prefijo = trim($row["valor2"]);
	$padeo = intval($row["valor3"]);
	$facturaCTRL = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
	$sql = "UPDATE parametro SET valor1='$numero' 
			WHERE codigo = '030';";
	Execute($sql);
	
	/**** Almacen por defecto ****/
	$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
	$almacen = ExecuteScalar($sql);


	/**********************/
	// Inserto el encabezado de la factura
	// VIENE DE NOTA DE ENTREGA No. $id
	$sql = "INSERT INTO salidas
				(id, tipo_documento, username, fecha,
				cliente, nro_documento,
				nota, estatus,
				id_documento_padre, asesor, documento, dias_credito,
				nro_control, moneda, tasa_dia, pagado, nro_despacho)
			SELECT 
				NULL, 'TDCFCV', '" . CurrentUserName() . "', NOW(),
				cliente, '$factura' AS factura,
				'$nota' AS nota, 
				'NUEVO' AS estatus, id, asesor, 'FC', $dias_credito, '$facturaCTRL', '$moneda', $tasa, 'S',
				nro_documento 
			FROM salidas 
			WHERE id = '$id';";
	ExecuteScalar($sql);

	// Obtengo el id de la nueva factura
	$factura_id = ExecuteScalar("SELECT LAST_INSERT_ID();");

	$sql = "SELECT valor1 FROM parametro WHERE codigo = '033';";
	$si = ExecuteScalar($sql);

	// Poblo el detalle de la factura
	if($si == "S") {
		$sql = "INSERT INTO entradas_salidas
				(id, tipo_documento, id_documento, 
				fabricante, articulo, almacen, 
				cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
				cantidad_movimiento, lote, fecha_vencimiento, precio_unidad, precio, alicuota,
				costo_unidad, costo, id_compra,
				descuento, precio_unidad_sin_desc)
			SELECT 
				NULL, 'TDCFCV', '$factura_id', 
				a.fabricante, a.articulo, '$almacen', 
				a.cantidad_articulo, a.articulo_unidad_medida, a.cantidad_unidad_medida, 
				a.cantidad_movimiento, a.lote, a.fecha_vencimiento, a.precio_unidad, a.precio, a.alicuota,
				(SELECT ultimo_costo FROM articulo WHERE id = a.articulo) * $tasa AS costo_unidad,
				((SELECT ultimo_costo FROM articulo WHERE id = a.articulo) * cantidad_articulo) * $tasa AS costo, id_compra, 
				descuento, precio_unidad_sin_desc * $tasa
			FROM entradas_salidas AS a 
			WHERE a.id_documento = '$id' AND a.tipo_documento = '$tipo' LIMIT $limite, $LineasFactura;";
	}
	else {
		$sql = "INSERT INTO entradas_salidas
				(id, tipo_documento, id_documento, 
				fabricante, articulo, almacen, 
				cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
				cantidad_movimiento, lote, fecha_vencimiento, precio_unidad, precio, alicuota,
				costo_unidad, costo, id_compra,
				descuento, precio_unidad_sin_desc)
			SELECT 
				NULL, 'TDCFCV', '$factura_id', 
				a.fabricante, a.articulo, '$almacen', 
				a.cantidad_articulo, a.articulo_unidad_medida, a.cantidad_unidad_medida, 
				a.cantidad_movimiento, a.lote, a.fecha_vencimiento,
				(a.precio_unidad / (1 +((SELECT al2.alicuota FROM articulo AS al1 JOIN alicuota AS al2 ON al2.codigo = al1.alicuota WHERE al1.id = a.articulo AND al2.activo = 'S')/100))) * $tasa AS precio_unidad,
				cantidad_articulo * (a.precio_unidad / (1 +((SELECT al2.alicuota FROM articulo AS al1 JOIN alicuota AS al2 ON al2.codigo = al1.alicuota WHERE al1.id = a.articulo AND al2.activo = 'S')/100))) * $tasa AS precio,
				(SELECT al2.alicuota FROM articulo AS al1 JOIN alicuota AS al2 ON al2.codigo = al1.alicuota WHERE al1.id = a.articulo AND al2.activo = 'S') AS alicuota,
				(SELECT ultimo_costo FROM articulo WHERE id = a.articulo) AS costo_unidad,
				((SELECT ultimo_costo FROM articulo WHERE id = a.articulo) * cantidad_articulo) AS costo, id_compra, 
				descuento, precio_unidad_sin_desc * $tasa 
			FROM entradas_salidas AS a 
			WHERE a.id_documento = '$id' AND a.tipo_documento = '$tipo' LIMIT $limite, $LineasFactura;";
	}
	Execute($sql);

	//$sql = "UPDATE salidas SET estatus = 'PROCESADO', id_documento_padre = '$factura_id' WHERE id = '$id'";
	//$sql = "UPDATE salidas SET estatus = 'NUEVO' WHERE id = '$id'";
	//Execute($sql);

	///////////////////////////////////////////
	//// Se coloca el precio total en el encabezado ////
	$sql = "SELECT
				SUM(precio) AS precio, 
				SUM((precio * (IFNULL(alicuota,0)/100))) AS iva, 
				SUM(precio) + SUM((precio * (IFNULL(alicuota,0)/100))) AS total 
			FROM entradas_salidas
			WHERE tipo_documento = 'TDCFCV' AND 
				id_documento = '$factura_id'"; 
	$row = ExecuteRow($sql); 
	$precio = floatval($row["precio"]);
	$iva = floatval($row["iva"]);
	$total = floatval($row["total"]);

	/*** Indico que alicuota iva se coloca en el encabezado del documento ***/
	$sql = "SELECT 
				COUNT(DISTINCT alicuota ) AS cantidad  
			FROM 
				entradas_salidas
			WHERE 
				tipo_documento = 'TDCFCV' 
				AND id_documento = '$factura_id';";
	$row = ExecuteRow($sql); 
	if(intval($row["cantidad"]) > 1) $alicuota = 0;
	else {
		$sql = "SELECT 
					DISTINCT alicuota 
				FROM 
					entradas_salidas
				WHERE 
					tipo_documento = 'TDCFCV' 
					AND id_documento = '$factura_id';";
		$row = ExecuteRow($sql); 
		$alicuota = floatval($row["alicuota"]);
	}


	$sql = "UPDATE salidas 
			SET
				monto_total = $precio,
				alicuota_iva = $alicuota,
				iva = $iva,
				total = $total,
				monto_usd = $total/$tasa, nro_control = nro_documento  
			WHERE id = '$factura_id'"; 
	Execute($sql); 

	/* Se actualizan las cantidades de unidades en el encabezado de la salida */
	// 28-01-2021
	$sql = "UPDATE 
			salidas AS a 
			JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
		SET 
			a.unidades = b.cantidad 
		WHERE a.id = $factura_id;";
	Execute($sql);
	/**************/

	$limite += $LineasFactura;
}
///////////////////////////////////////////

//header("Location: SalidasEdit?showdetail=entradas_salidas&id=$factura_id&tipo=TDCFCV");
header("Location: SalidasView?showdetail=entradas_salidas&id=$factura_id&tipo=TDCFCV");
die();
?>

<?= GetDebugMessage() ?>
