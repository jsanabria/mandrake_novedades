<?php 
include "connect.php";
include "rutinas.php";
 
$id = $_POST["id"];
$nota = $_POST["nota"];
$username = $_POST["username"];

$factura = $_POST["factura"];
$ci_rif = $_POST["ci_rif"];
$nombre = $_POST["nombre"];
$direccion = $_POST["direccion"];
$telefono = $_POST["telefono"];


$tipo_documento = 'TDCASA';

$articulo = $_POST["articulo"];
$xlot = explode(",", $_POST["lote"]);
$lot = $xlot[0];
$cnt = $_POST["cantidad"];
$un = $_POST["unidad"];


$sql = "SELECT descuento FROM articulo WHERE id = $articulo;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$descuento = floatval($row["descuento"]);

$sql = "SELECT cantidad FROM unidad_medida WHERE codigo = '$un';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cantidad_um = floatval($row["cantidad"]);

$asignado = $cnt * $cantidad_um;

// Consulto el ultimo costo del artículo
$sql = "SELECT ultimo_costo FROM articulo WHERE id = $articulo;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$costo_unidad = floatval($row["ultimo_costo"]);
$costo = $costo_unidad * $asignado;

/*** Consulto la información del lote a descontar las cantidades ***/
$sql = "SELECT 
			a.fabricante, a.articulo, a.lote, a.fecha_vencimiento, 
			a.alicuota, a.almacen 
		FROM entradas_salidas AS a 
		WHERE a.id = '$lot';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
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
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$alicuota = $row["alicuota"];

$sql = "SELECT b.tarifa FROM salidas AS a JOIN cliente AS b ON b.id = a.cliente WHERE a.id = $id;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tarifa = $row["tarifa"];

/*** Consultar precio según tarifa y cliente ***/
$sql = "SELECT 
			a.precio AS precio_ful, 
			(a.precio - (a.precio * ($descuento/100))) AS precio 
		FROM tarifa_articulo AS a WHERE a.tarifa = $tarifa AND a.articulo = $articulo;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$precio_unidad = $row["precio"];
$precio = $asignado * $precio_unidad;
$precio_ful = $row["precio_ful"];

$asignado *= -1;

$sql = "INSERT INTO entradas_salidas 
			(id, tipo_documento, id_documento, 
			fabricante, articulo, lote, fecha_vencimiento, almacen, cantidad_articulo, costo_unidad, costo, 
			articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, precio_unidad, precio, alicuota, id_compra, descuento, precio_unidad_sin_desc) 
		VALUES 
			(NULL, '$tipo_documento', '$id', 
			'$fabricante', '$articulo', '$lote', '$fecha_vencimiento', '$almacen', '$cnt', $costo_unidad, $costo, 
			'$un', '$cantidad_um', '$asignado', '$precio_unidad', '$precio', '$alicuota', '$lot', $descuento, $precio_ful);"; 
mysqli_query($link, $sql);

$sql = "UPDATE salidas 
		SET 
			estatus = 'PROCESADO', 
			factura = '$factura', 
			ci_rif = '$ci_rif', 
			nombre = '$nombre', 
			direccion = '$direccion', 
			nota = '$nota', 
			telefono = '$telefono', 
			username = '$username'   
		WHERE id = $id;";
mysqli_query($link, $sql);

ActInv($articulo); 

require_once("findme_cabecera_totales.php");

$id_documento = $id; 

require_once("findme_detalle.php");
?>

