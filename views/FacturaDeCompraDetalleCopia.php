<?php

namespace PHPMaker2021\mandrake;

// Page object
$FacturaDeCompraDetalleCopia = &$Page;
?>
<?php
$id = $_REQUEST["id"];
$tipo_documento = "TDCFCC";
	
$consecutivo = "";

$username = CurrentUserName();
$estatus = "NUEVO";

$sql = "INSERT INTO entradas
			(id, tipo_documento, username,
			fecha, proveedor, nro_documento,
			almacen, monto_total, alicuota_iva,
			iva, total, nota,
			estatus, id_documento_padre, moneda)
		SELECT
			NULL, tipo_documento, '$username',
			NOW(), proveedor, '$nro_documento',
			almacen, monto_total, alicuota_iva,
			iva, total, NULL,
			'$estatus', id_documento_padre, moneda
		FROM entradas
		WHERE
			id = '$id';";

Execute($sql);

$sql = "SELECT LAST_INSERT_ID();";
$newid = ExecuteScalar($sql);

$sql = "INSERT INTO entradas_salidas
			(id, tipo_documento, id_documento, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, alicuota, costo_unidad, costo)
		SELECT
			NULL, tipo_documento, $newid, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, alicuota, costo_unidad, costo 
		FROM
			entradas_salidas
		WHERE
			tipo_documento = '$tipo_documento' AND id_documento = '$id';";
Execute($sql);

/* ------- Actualizo cantidad en mano, en pedido y en transito  ------- */
ActualizarExitencia();

header("Location: EntradasEdit?showdetail=entradas_salidas&id=$newid&tipo=$tipo_documento");
exit();
?>

<?= GetDebugMessage() ?>
