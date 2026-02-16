<?php

namespace PHPMaker2021\mandrake;

// Page object
$EliminarLinea = &$Page;
?>
<?php 

$id = $_REQUEST["id"];

$sql = "DELETE FROM entradas_salidas WHERE id = '$id'"; 
Execute($sql);

echo "1";
die();
?>

<?= GetDebugMessage() ?>
