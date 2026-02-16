<?php

namespace PHPMaker2021\mandrake;

// Page object
$SubirPorDescArticuloGuardar = &$Page;
?>
<?php
Execute("SET NAMES 'utf8';");
Execute("SET CHARACTER SET utf8;");

if (!isset($_FILES['uploadedFile']) or !$_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK or trim($_FILES['uploadedFile']['name']) == "") {
	$_SESSION['error'] = "Debe seleccionar un archivo. " . $_FILES['uploadedFile']['error'];
	header("Location: SubirPorDescArticulo");
	die();
}

// get details of the uploaded file
$fichero = $_FILES["uploadedFile"];
$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
$fileName = $_FILES['uploadedFile']['name'];
$fileSize = $_FILES['uploadedFile']['size'];
$fileType = $_FILES['uploadedFile']['type'];
$fileNameCmps = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameCmps));

$newFileName = md5(time() . $fileName) . '.' . $fileExtension;

$allowedfileExtensions = array('csv');

if (!in_array($fileExtension, $allowedfileExtensions)) {
	$_SESSION['error'] = "El archivo debe ser tipo .csv";
	header("Location: SubirPorDescArticulo");
	die();
}

// directory in which the uploaded file will be moved
$uploadFileDir = 'carpetacarga/';
$dest_path = $uploadFileDir . $newFileName;

if(move_uploaded_file($fileTmpPath, $dest_path)) {
	$_SESSION['message'] = 'El archivo subio exitosamente.';

	$sql = "TRUNCATE TABLE articulo_porcentaje_descuento_temp;";
	Execute($sql);

	$linea = 0;
	//Abrimos nuestro archivo
	$archivo = fopen($dest_path, "r");
	//Lo recorremos
	$codigo = "";
	$nombre = "";
	$costo = 0.00;
	$porcentaje = 0.00;
	$precio = 0.00;
	while (($datos = fgetcsv($archivo, 0, ";")) == true) {
		$num = count($datos);
		//Recorremos las columnas de esa linea
		if($num == 5) {
			for($columna = 0; $columna < $num; $columna++) {
				switch($columna) {
				case 0:	
					$codigo = $datos[$columna];
					break;
				case 1:	
					$nombre = $datos[$columna];
					break;
				case 2:	
					$costo = floatval($datos[$columna]);
					break;
				case 3:	
					$porcentaje = floatval($datos[$columna]);
					break;
				case 4:	
					$precio = floatval($datos[$columna]);
					break;
				}
			}

			$sql = "SELECT codigo FROM articulo_porcentaje_descuento_temp WHERE codigo = '$codigo'";
			if(!$row = ExecuteRow($sql)) {
				$sql = "INSERT INTO articulo_porcentaje_descuento_temp
						(codigo, nombre, costo, porcentaje, precio)
					VALUES 
						('$codigo', '$nombre', $costo, $porcentaje, $precio)";
				Execute($sql);
			}

			/* ** Valido si Existe; si no existe se agregar el artículo ** */
			$sql = "SELECT id FROM articulo WHERE codigo_ims = '$codigo'";
			if(!$row = ExecuteRow($sql)) {
				$sql = "INSERT INTO 
						articulo
					SET 
						id = NULL, 
						codigo = \"$codigo\", 
						nombre_comercial = SUBSTRING(\"$nombre\", 1, 50), 
						principio_activo = \"$nombre\", 
						fabricante = 1, 
						alicuota = 'GEN', 
						articulo_inventario = 'S', 
						codigo_ims = \"$codigo\", 
						activo = 'S';";
				Execute($sql);
			}
			/* **  ** */
		}
		$codigo = "";
		$nombre = "";
		$costo = 0.00;
		$porcentaje = 0.00;
		$precio = 0.00;
	}
	//Cerramos el archivo
	fclose($archivo);

	$sql = "UPDATE 
				articulo AS a 
				JOIN articulo_porcentaje_descuento_temp AS b ON b.codigo = a.codigo_ims  
			SET
				a.ultimo_costo = b.costo, 
				a.descuento = b.porcentaje,
				a.precio = b.precio 
			WHERE 0 = 0;";
	Execute($sql);

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
}
else {
  $_SESSION['error'] = 'Hay alg&uacute;n error en la copia del archivo al directorio de carga.';
}

header("Location: SubirPorDescArticulo");
die();
?>

<?= GetDebugMessage() ?>
