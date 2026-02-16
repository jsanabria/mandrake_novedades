<?php

namespace PHPMaker2021\mandrake;

// Page object
$MasivoAjusteSalidaGuardar = &$Page;
?>
<?php

$cantidad = $_POST["cantidad"];
$nota = $_POST["nota"];
$username = $_POST["username"];

$tipo = "TDCASA";
$cliente = 1;
$estatus = "NUEVO";

$consignacion = "N";

/**** Almacen por defecto ****/
$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$row = ExecuteRow($sql);
$almacen = $row["almacen"];

/**** Se obtiene el consecutivo del tipo de documento ****/
$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS consecutivo FROM salidas WHERE tipo_documento = '$tipo';";
$row = ExecuteRow($sql);
$consecutivo = intval($row["consecutivo"]) + 1; 
$nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

$tipo_documento = $tipo;

/**** Inserto el encabezado de la nota de entrega ****/
$sql = "INSERT INTO salidas
			(id, tipo_documento, username, fecha,
			cliente, nro_documento,
			nota, estatus,
			id_documento_padre, asesor, consignacion)
		VALUES
			(NULL, '$tipo_documento', '$username', NOW(),
			$cliente, '$nro_documento',
			'$nota', '$estatus',
			NULL, NULL, '$consignacion')"; 
Execute($sql);

// Obtengo el id de la nueva factura
$row = ExecuteRow("SELECT LAST_INSERT_ID() AS id;");
$new_id = $row["id"];

$dt = "";
$lot = "";
$cnt = "";
$un = "";

$fabricante = '';
$articulo = "";
$lote = "";
$fecha_vencimiento = "";
$alicuota = "";
for($i = 0; $i < $cantidad; $i++) {	
	$dt = $_POST["id_$i"];
	$lot = $_POST["lote_$i"];
	$cnt = $_POST["cantidad_$i"];
	$un = $_POST["unidad_$i"];

	$sql = "SELECT cantidad FROM unidad_medida WHERE codigo = '$un';";
	$row = ExecuteRow($sql);
	$cantidad_um = floatval($row["cantidad"]);

	$asignado = $cnt * $cantidad_um;

	/*** Consulto la información del lote a descontar las cantidades ***/
	$sql = "SELECT 
				a.fabricante, a.articulo, a.lote, a.fecha_vencimiento, 
				a.alicuota, a.almacen 
			FROM entradas_salidas AS a 
			WHERE a.id = '$lot';"; 
	$row = ExecuteRow($sql);
	$fabricante = $row["fabricante"];
	$articulo = $row["articulo"];
	$lote = $row["lote"];
	$fecha_vencimiento = $row["fecha_vencimiento"];
	$alicuota = $row["alicuota"];
	$almacen = $row["almacen"];

	$sql = "SELECT IFNULL(b.alicuota, 0) as alicuota 
			FROM 
				articulo AS a JOIN alicuota AS b ON b.codigo = a.alicuota 
			WHERE a.id = $articulo AND b.activo = 'S';";
	$row = ExecuteRow($sql);
	$alicuota = $row["alicuota"];

	/*** Consulto la cantidad solicitada por el cliente y precio dado al mismo por el artículo ***/
	$sql = "SELECT 
				a.cantidad_movimiento, a.precio_unidad, a.alicuota,
				a.precio_unidad, a.descuento, a.precio_unidad_sin_desc 
			FROM entradas_salidas AS a 
			WHERE a.id = '$dt';";
	$row = ExecuteRow($sql);
	$solicitado = $row["cantidad_movimiento"];
	$precio_unidad = $row["precio_unidad"];
	$descuento = $row["descuento"];
	$precio_unidad_sin_desc = $row["precio_unidad_sin_desc"];
	

	$precio = $asignado * $precio_unidad;


	$asignado *= -1;

	$sql = "INSERT INTO entradas_salidas 
				(id, tipo_documento, id_documento, 
				fabricante, articulo, lote, almacen, cantidad_articulo, 
				articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento) 
			VALUES 
				(NULL, '$tipo_documento', '$new_id', 
				'$fabricante', '$articulo', '$lote', '$almacen', '$cnt', 
				'$un', '$cantidad_um', '$asignado');";  
	Execute($sql);

	if((abs($solicitado) - abs($asignado)) > 0) {
		$pendiente = abs($solicitado) - abs($asignado);
		$pendiente *= -1;
		
		$precio = $pendiente * $precio_unidad;
		$sql = "INSERT INTO entradas_salidas 
					(id, tipo_documento, id_documento, 
					fabricante, articulo, lote, almacen, cantidad_articulo, 
					articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento) 
				VALUES 
					(NULL, '$tipo_documento', '$new_id', 
					'$fabricante', '$articulo', '$lote', '$almacen', '" . abs($pendiente/$cantidad_um) ."', 
					'$un', '$cantidad_um', NULL);";
		Execute($sql);
	}

	ActualizarExitenciaArticulo($articulo); 
}


/* Se actualizan las cantidades de unidades en el encabezado de la salida */
// 21-01-2021
$sql = "UPDATE 
			salidas AS a 
			JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
		SET 
			a.unidades = b.cantidad 
		WHERE a.id = $new_id;";
Execute($sql);
/**************/

header("Location: MasivoAjusteSalidaUpdate2?id=$new_id");
die();
?>

<?= GetDebugMessage() ?>
