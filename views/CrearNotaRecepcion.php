<?php

namespace PHPMaker2021\mandrake;

// Page object
$CrearNotaRecepcion = &$Page;
?>
<?php
$id = $_REQUEST["id"];

$sql = "SELECT tipo_documento, consignacion FROM entradas WHERE id = '$id';";
$row = ExecuteRow($sql);
$tipo = $row["tipo_documento"];
$consignacion = strtoupper($row["consignacion"]);

/**** Almacen por defecto ****/
$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$almacen = ExecuteScalar($sql);

/**** Si es compra a consignación cambio el almacen ****/
if($consignacion == "S") {
	$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '014';";
	$almacen = ExecuteScalar($sql);
}

$sql = "INSERT INTO entradas
			(id, tipo_documento, username, fecha, 
			proveedor, nro_documento, almacen, estatus, 
			id_documento_padre, consignacion, descuento, moneda)
		SELECT 
			NULL, 'TDCNRP', '" . CurrentUserName() . "', NOW(), 
			proveedor, '', '$almacen', 'NUEVO', 
			'$id', consignacion, descuento, moneda 
		FROM entradas 
		WHERE id = '$id';";
Execute($sql);

$newid = ExecuteScalar("SELECT LAST_INSERT_ID();");

$sql = "INSERT INTO entradas_salidas
			(id, tipo_documento, id_documento, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, lote, fecha_vencimiento,
			costo_unidad, costo, precio_unidad_sin_desc, descuento) 
		SELECT 
			NULL, 'TDCNRP', '$newid', 
			fabricante, articulo, '$almacen', 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, lote, fecha_vencimiento, costo_unidad, costo,
			precio_unidad_sin_desc, descuento 
		FROM entradas_salidas
		WHERE id_documento = '$id' AND tipo_documento = '$tipo'";
Execute($sql);

$sql = "UPDATE entradas SET estatus = 'PROCESADO', id_documento_padre = '$newid'  WHERE id = '$id'";
Execute($sql);

//header("Location: EntradasEdit?showdetail=entradas_salidas&id=$newid&tipo=TDCNRP");
header("Location: EntradasView/$newid?showdetail=entradas_salidas&tipo=TDCNRP");
//header("Location: EntradasList?tipo=TDCNRP");
die();
?>

<?= GetDebugMessage() ?>
