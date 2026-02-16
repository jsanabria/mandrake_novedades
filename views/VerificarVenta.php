<?php

namespace PHPMaker2021\mandrake;

// Page object
$VerificarVenta = &$Page;
?>
<?php
$id = $_GET["id"];

$sql = "UPDATE venta SET estatus = 'VERIFICADO' WHERE id = '$id';";
Execute($sql);

header("VerificarVenta?showdetail=view_venta_detalle&id=$id");
?>

<?= GetDebugMessage() ?>
