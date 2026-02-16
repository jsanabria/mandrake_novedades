<?php

namespace PHPMaker2021\mandrake;

// Page object
$FacturaConsignacionGuardar = &$Page;
?>
<?php

$id = $_REQUEST["id"];

$sql = "SELECT tipo_documento FROM salidas WHERE id = '$id';";
$tipo = ExecuteScalar($sql);

// Tomo el número de días de crédito por defecto
$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '007';";
$row = ExecuteRow($sql);
$nota = "Crédito a " . $row["valor1"] . " " . $row["valor2"];
$dias_credito = intval($row["valor1"]);

// Proceso para saber si genero más de una factura para la nota de entrega //
// Consulto la cantidad de lineas por factura
$sql = "SELECT valor1 AS lineas FROM parametro WHERE codigo = '008';";
$LineasFactura = intval(ExecuteScalar($sql));
$LineasFactura = ($LineasFactura == 0 ? 35 : $LineasFactura);

$cant = 0;
foreach ($_POST as $key => $value) {
	if(substr($key, 0, 9) == "cantidad_") {
	 	//echo "$key => $value<br>";
	 	if(intval($value) > 0) 
	 		$cant++;
	}
} 

$cantidad = $cant/$LineasFactura;
$deci = abs($cantidad - intval($cantidad));
$cantidad = intval($cantidad);
if($deci > 0) $cantidad++;

for($xy = 0; $xy < $cantidad; $xy++) {
	//////echo "Pagina $xy de ($cantidad-1) <br>";
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

	// Inserto el encabezado de la factura
	// VIENE DE NOTA DE ENTREGA No. $id
	$sql = "INSERT INTO salidas
				(id, tipo_documento, username, fecha,
				cliente, nro_documento,
				nota, estatus,
				id_documento_padre, asesor, documento, dias_credito, consignacion)
			SELECT 
				NULL, 'TDCFCV', '" . CurrentUserName() . "', NOW(),
				cliente, '$factura' AS factura,
				'$nota' AS nota, 
				'NUEVO' AS estatus, id, asesor, 'FC', $dias_credito, 'S' 
			FROM salidas 
			WHERE id = '$id';";
	ExecuteScalar($sql);

	// Obtengo el id de la nueva factura
	$factura_id = ExecuteScalar("SELECT LAST_INSERT_ID();");


	$idd = 0;
	$cantd = 0;
	$ctrl = 0;
	$i = 0;
	$contador = 0;
	foreach ($_POST as $key => $value) {
		if(substr($key, 0, 9) == "cantidad_") {
		 	if(intval($value) > 0) { 
		 		if($i >= ($xy*$LineasFactura)) {
			 		$idd = substr($key, 9, strlen($key));
			 		$cantd = (-1) * intval($value);

			 		//$contador++;
		 			//echo "$contador) $key !! $i >=  ($xy*$LineasFactura) <------> $ctrl >= $LineasFactura<br>";

					// Poblo el detalle de la factura
					$sql = "INSERT INTO entradas_salidas
								(id, tipo_documento, id_documento, 
								fabricante, articulo, almacen, 
								cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
								cantidad_movimiento, lote, fecha_vencimiento, precio_unidad, precio, alicuota,
								costo_unidad, costo, cantidad_movimiento_consignacion, id_consignacion,
								id_compra, descuento, precio_unidad_sin_desc)
							SELECT 
								NULL, 'TDCFCV', '$factura_id', 
								a.fabricante, a.articulo, a.almacen, 
								ABS($cantd), a.articulo_unidad_medida, a.cantidad_unidad_medida, 
								$cantd, a.lote, a.fecha_vencimiento, a.precio_unidad, ABS($cantd)*a.precio_unidad, a.alicuota,
								(SELECT ultimo_costo FROM articulo WHERE id = a.articulo) AS costo_unidad,
								((SELECT ultimo_costo FROM articulo WHERE id = a.articulo) * ABS($cantd)) AS costo, 
								0, a.id, id_compra, descuento, precio_unidad_sin_desc  
							FROM entradas_salidas AS a 
							WHERE a.id = $idd;";
					Execute($sql);

					$sql = "UPDATE entradas_salidas 
							SET 
								cantidad_movimiento_consignacion = IFNULL(cantidad_movimiento_consignacion, 0) + ABS($cantd) 
							WHERE id = $idd;";
					Execute($sql);

			 		if($ctrl >= ($LineasFactura-1)) break;
					$ctrl++;
		 		}
		 		$i++;
		 	}
		}
	} 


	/// Marco como procesada la Nota de Entrega si no hay items con cantidades pendientes ///
	$sql = "SELECT 
				COUNT(id) AS cantidad 
			FROM 
				entradas_salidas 
			WHERE 
				id_documento = $id AND tipo_documento = '$tipo' 
				AND (ABS(cantidad_movimiento) - IFNULL(cantidad_movimiento_consignacion, 0)) > 0;";
	$xCant = intval(ExecuteScalar($sql)); 
	if($xCant == 0) {
		$sql = "UPDATE salidas SET estatus = 'PROCESADO' WHERE id = '$id'";
		Execute($sql);
	}

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
				total = $total
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
}
///////////////////////////////////////////

header("Location: salidaslist.php?tipo=TDCFCV");
?>

<?= GetDebugMessage() ?>
