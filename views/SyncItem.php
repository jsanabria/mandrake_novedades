<?php

namespace PHPMaker2021\mandrake;

// Page object
$SyncItem = &$Page;
?>
<?php
$id = $_REQUEST["id"];

$sql = "INSERT INTO tarifa_articulo
	(id, tarifa, fabricante, articulo, precio)
SELECT 
	NULL, $id AS tarifa, a.fabricante, a.id AS articulo, a.precio 
FROM 
	articulo AS a 
	LEFT OUTER JOIN tarifa_articulo AS b ON b.articulo = a.id AND b.tarifa = $id 
WHERE 
	b.articulo IS NULL AND a.activo = 'S';";
Execute($sql);

header("Location: TarifaList");
exit();
?>

<?= GetDebugMessage() ?>
