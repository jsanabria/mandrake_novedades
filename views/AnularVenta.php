<?php

namespace PHPMaker2021\mandrake;

// Page object
$AnularVenta = &$Page;
?>
<?php
$id = $_GET["id"];

$sql = "UPDATE venta SET estatus = 'ANULADO' WHERE id = '$id';";
Execute($sql);

header("ventaview.php?showdetail=view_venta_detalle&id=$id");
?>

<?= GetDebugMessage() ?>
