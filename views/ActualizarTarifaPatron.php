<?php

namespace PHPMaker2021\mandrake;

// Page object
$ActualizarTarifaPatron = &$Page;
?>
<?php
$id = $_REQUEST["id"];
$patron = $_REQUEST["patron"];

//$sql = "SELECT valor1 FROM parametro WHERE codigo = '005';";
$sql = "SELECT porcentaje FROM tarifa WHERE id = $id;";
$porc = floatval(ExecuteScalar($sql));

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

header("Location: TarifaList");
exit();
?>

<?= GetDebugMessage() ?>
