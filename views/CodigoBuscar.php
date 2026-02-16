<?php

namespace PHPMaker2021\mandrake;

// Page object
$CodigoBuscar = &$Page;
?>
<?php
$codigo = $_GET["codigo"];
$accion = $_GET["accion"];

$sql = "SELECT COUNT(codigo) AS cantidad FROM articulo WHERE codigo = '$codigo';";
$cantidad = ExecuteScalar($sql);

if($cantidad > ($accion == "I" ? 0 : 1)) $cantidad = 1;

$out = '<div id="outtext">' . $cantidad . '</div>';
echo $out;
?>

<?= GetDebugMessage() ?>
