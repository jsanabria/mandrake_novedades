<?php
session_start();
die("PASO");
require("../include/connect.php");

$articulo_json = file_get_contents("http://localhost:9090/mandrake_novedades/wssync/syncAPI.php?user=365&dbName=mandrake_novedades&app=articulo");

$decoded_json = json_decode($articulo_json, true);
$articulo = $decoded_json["listalistaArticulose"];

foreach ($articulo as $key => $value) {
	$id = $value["id"];
	$codigo = $value["codigo"];
	$nombre_comercial = $value["nombre_comercial"];
	$principio_activo = $value["principio_activo"];
	$presentacion = $value["presentacion"];
	$fabricante = $value["fabricante"];
	$codigo_de_barra = $value["codigo_de_barra"];
	$ultimo_costo = $value["ultimo_costo"];
	$alicuota = $row["alicuota"];
	$articulo_inventario = $value["articulo_inventario"];
	$codigo_ims = $value["codigo_ims"];
	$activo = $value["activo"];
	$precio = $value["precio"];


	$sql = "INSERT INTO 
				articulo
			SET 
				id = NULL, 
				codigo = \"$codigo\", 
				nombre_comercial = $nombre_comercial, 
				principio_activo = $principio_activo, 
				presentacion = $presentacion, 
				fabricante = $fabricante, 
				codigo_de_barra = $codigo_de_barra, 
				ultimo_costo = $ultimo_costo, 
				alicuota = $alicuota, 
				articulo_inventario = $articulo_inventario, 
				codigo_ims = $codigo_ims, 
				activo = $activo, 
				precio = $precio;";
	// mysqli_query($link, $sql); 
	echo "$sql <br>";
}
?>