<?php

namespace PHPMaker2021\mandrake;

// Page object
$Sincronizar = &$Page;
?>
<?php
// echo "ES " . getcwd();
// echo dirname ( __FILE__ );

$sql = "SELECT valor1 AS url, valor2 AS db, valor4 as ruta_backup FROM parametro WHERE codigo = '048';";
$url = "";
$dbName = "";
if($row = ExecuteRow($sql)) { 
	$url = $row["url"];
	$dbName = $row["db"];
	$ruta = $row["ruta_backup"];
	$sw = true;
}
else {
	echo '<div class="alert alert-danger">
  			<strong>Faltan datos de la tabla par&aacute;metros! (CODIGO 048)</strong> ... para definir ruta de webservices del servidor para bajar la data (valor1), c&oacute;digo de tienda (valor2) en el programa administrativo y carpeta local para alojar los backups (valor4). 
  		</div>';
  	$sw = false;
}

if($sw) {
	// 1) Backup de la base de datos antes de proceso
	include("include/connect.php");
	$path = $ruta . $dbName . '_' . $strcon . '_' . date("YmdHis") . '.sql'; // /Ruta/Hacia/archivo_dump.SQL
	$command='mysqldump --user=' . $user . ' --password=' . $password . ' ' . $strcon . ' > ' . $path . ''; 
	exec($command);

	echo '<div class="alert alert-success">
	  			La base de datos <b>' . $strcon .'</b> se ha respaldado correctamente... en dispositivo ' . $path . '<br>
	  	</div>';


	$tasa_json = file_get_contents("$url/wssync_servidor/syncAPI.php?user=365&dbName=$dbName&app=tasa");

	$decoded_json = json_decode($tasa_json, true);
	$tasa = $decoded_json["listaTasa"];

	foreach ($tasa as $key => $value) {
		$moneda = $value["moneda"];
		$tasa = $value["tasa"];
		$fecha = $value["fecha"];
		$hora = $value["hora"];

		$sql = "INSERT INTO 
					tasa_usd 
				SET 
					moneda = \"$moneda\", 
					tasa = \"$tasa\", 
					fecha = \"$fecha\", 
					hora = \"$hora\";"; 
		Execute($sql); 
		// echo "$sql <br>";
	}

	// Descargo los artículos y los actalizo
	$articulo_json = file_get_contents("$url/wssync_servidor/syncAPI.php?user=365&dbName=$dbName&app=articulo");

	$decoded_json = json_decode($articulo_json, true);
	$articulo = $decoded_json["listaArticulos"];

	foreach ($articulo as $key => $value) {
		$id = $value["id"];
		$codigo = $value["codigo"];
		$nombre_comercial = str_replace('"', '\"', $value["nombre_comercial"]);
		$principio_activo = str_replace('"', '\"', $value["principio_activo"]);
		$presentacion = str_replace('"', '\"', $value["presentacion"]);
		$fabricante = $value["fabricante"];
		$codigo_de_barra = str_replace('"', '\"', $value["codigo_de_barra"]);
		$ultimo_costo = floatval($value["ultimo_costo"]);
		$alicuota = $value["alicuota"];
		$articulo_inventario = $value["articulo_inventario"];
		$codigo_ims = str_replace('"', '\"', $value["codigo_ims"]);
		$activo = $value["activo"];
		$precio = floatval($value["precio"]);
		$descuento = floatval($value["descuento"]);
		$puntos_ventas = floatval($value["puntos_ventas"]);
		$puntos_premio = floatval($value["puntos_premio"]);

		$sql = "SELECT id, sincroniza FROM articulo WHERE codigo_ims = '$codigo_ims'";

		if($row = ExecuteRow($sql)) {
			if($row["sincroniza"] == "S") {
				$sql = "UPDATE 
						articulo
					SET 
						codigo = \"$codigo\", 
						nombre_comercial = \"$nombre_comercial\", 
						principio_activo = \"$principio_activo\", 
						presentacion = \"$presentacion\", 
						fabricante = $fabricante, 
						codigo_de_barra = \"$codigo_de_barra\", 
						ultimo_costo = $ultimo_costo, 
						alicuota = \"$alicuota\", 
						articulo_inventario = \"$articulo_inventario\", 
						codigo_ims = \"$codigo_ims\", 
						activo = \"$activo\", 
						precio = $precio,
						descuento = $descuento,
						puntos_ventas = $puntos_ventas,
						puntos_premio = $puntos_premio 
					WHERE id = " . $row["id"] . ";";
				$art100 = $row["id"];
				Execute($sql);
			}
		}
		else { 
			$sql = "INSERT INTO 
						articulo
					SET 
						id = NULL, 
						codigo = \"$codigo\", 
						nombre_comercial = \"$nombre_comercial\", 
						principio_activo = \"$principio_activo\", 
						presentacion = \"$presentacion\", 
						fabricante = $fabricante, 
						codigo_de_barra = \"$codigo_de_barra\", 
						ultimo_costo = $ultimo_costo, 
						alicuota = \"$alicuota\", 
						articulo_inventario = \"$articulo_inventario\", 
						codigo_ims = \"$codigo_ims\", 
						activo = \"$activo\", 
						precio = $precio,
						descuento = $descuento,
						puntos_ventas = $puntos_ventas,
						puntos_premio = $puntos_premio;";
			Execute($sql);
			$sql = "SELECT LAST_INSERT_ID();";
			$art100 = intval(ExecuteScalar($sql));
		}
	}

	/****** Sincronizo los articulos en las tarifas ********/
	$sql = "TRUNCATE TABLE tarifa_articulo;";
	Execute($sql);

	$sql = "SELECT id FROM tarifa WHERE activo = 'S';";
	$rows = ExecuteRows($sql);
	foreach ($rows as $key => $value) { 
		$sql = "INSERT INTO tarifa_articulo
				(id, tarifa, fabricante, articulo, precio)
			SELECT 
				NULL, " . $value["id"] . " AS tarifa, a.fabricante, a.id AS articulo, a.precio 
			FROM 
				articulo AS a 
				LEFT OUTER JOIN tarifa_articulo AS b ON b.articulo = a.id
				AND b.tarifa = " . $value["id"] . " 
			WHERE 
				b.articulo IS NULL AND a.activo = 'S';"; 
		Execute($sql);
	}


	/**** Actualizo los Precios en Todas las Tarifas ****/
	$sql = "SELECT id FROM tarifa WHERE patron = 'S' LIMIT 0,1";
	$patron = ExecuteScalar($sql);
	
	$sql = "SELECT id, porcentaje FROM tarifa WHERE patron <> 'S' AND activo = 'S';";
	$rows = ExecuteRows($sql);
	foreach ($rows as $key => $value) { 
		$id = $value["id"];
		$porc = floatval($value["porcentaje"]);

		$sql = "UPDATE 
					tarifa_articulo AS a 
					JOIN 
					(SELECT fabricante, articulo, precio FROM tarifa_articulo
						WHERE tarifa = $patron) AS b 
					ON b.fabricante = a.fabricante AND b.articulo = a.articulo 
				SET 
					a.precio = ROUND((b.precio + (b.precio * ($porc/100))), 2) 
				WHERE a.tarifa = $id;";
		Execute($sql);
	}

	$nota = urlencode("SE BAJA ACTUALIZACION DE COSTO Y PRECIO DE ARICULOS. SE RECALCULAN LAS TARIFA.");
	$usuario = "";
	$sql = "SELECT nombre FROM usuario WHERE username = '" . CurrentUserName() . "';";
	if($row = ExecuteRow($sql)) $usuario = urlencode($row["nombre"]);
	else $usuario = CurrentUserName();

	$url = "$url/wssync_servidor/syncAPI.php?user=365&dbName=$dbName&app=bitacora&_nota=$nota&usuario=$usuario";
	$bitacora_json = file_get_contents($url);

	$decoded_json = json_decode($bitacora_json, true);
	$bitacora = $decoded_json["listaBitacora"];

	echo '<div class="alert alert-success">
  			<strong>Proceso de sincronizaci&oacute;n finalizado!</strong> ' . $bitacora[0] . '...
  		</div>';
}
///////////////////////////////

?>


<?= GetDebugMessage() ?>
