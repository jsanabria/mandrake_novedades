<?php

namespace PHPMaker2021\mandrake;

// Page object
$TarifaArticuloView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var ftarifa_articuloview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    ftarifa_articuloview = currentForm = new ew.Form("ftarifa_articuloview", "view");
    loadjs.done("ftarifa_articuloview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.tarifa_articulo) ew.vars.tables.tarifa_articulo = <?= JsonEncode(GetClientVar("tables", "tarifa_articulo")) ?>;
</script>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="ftarifa_articuloview" id="ftarifa_articuloview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tarifa_articulo">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <tr id="r_fabricante">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tarifa_articulo_fabricante"><?= $Page->fabricante->caption() ?></span></td>
        <td data-name="fabricante" <?= $Page->fabricante->cellAttributes() ?>>
<span id="el_tarifa_articulo_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <tr id="r_articulo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tarifa_articulo_articulo"><?= $Page->articulo->caption() ?></span></td>
        <td data-name="articulo" <?= $Page->articulo->cellAttributes() ?>>
<span id="el_tarifa_articulo_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <tr id="r_precio">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tarifa_articulo_precio"><?= $Page->precio->caption() ?></span></td>
        <td data-name="precio" <?= $Page->precio->cellAttributes() ?>>
<span id="el_tarifa_articulo_precio">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->precio2->Visible) { // precio2 ?>
    <tr id="r_precio2">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tarifa_articulo_precio2"><?= $Page->precio2->caption() ?></span></td>
        <td data-name="precio2" <?= $Page->precio2->cellAttributes() ?>>
<span id="el_tarifa_articulo_precio2">
<span<?= $Page->precio2->viewAttributes() ?>>
<?= $Page->precio2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
