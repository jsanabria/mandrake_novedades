<?php

namespace PHPMaker2021\mandrake;

// Page object
$EntradasSalidasView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fentradas_salidasview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fentradas_salidasview = currentForm = new ew.Form("fentradas_salidasview", "view");
    loadjs.done("fentradas_salidasview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.entradas_salidas) ew.vars.tables.entradas_salidas = <?= JsonEncode(GetClientVar("tables", "entradas_salidas")) ?>;
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
<form name="fentradas_salidasview" id="fentradas_salidasview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas_salidas">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->articulo->Visible) { // articulo ?>
    <tr id="r_articulo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_articulo"><?= $Page->articulo->caption() ?></span></td>
        <td data-name="articulo" <?= $Page->articulo->cellAttributes() ?>>
<span id="el_entradas_salidas_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
    <tr id="r_cantidad_articulo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_cantidad_articulo"><?= $Page->cantidad_articulo->caption() ?></span></td>
        <td data-name="cantidad_articulo" <?= $Page->cantidad_articulo->cellAttributes() ?>>
<span id="el_entradas_salidas_cantidad_articulo">
<span<?= $Page->cantidad_articulo->viewAttributes() ?>>
<?= $Page->cantidad_articulo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
    <tr id="r_articulo_unidad_medida">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_articulo_unidad_medida"><?= $Page->articulo_unidad_medida->caption() ?></span></td>
        <td data-name="articulo_unidad_medida" <?= $Page->articulo_unidad_medida->cellAttributes() ?>>
<span id="el_entradas_salidas_articulo_unidad_medida">
<span<?= $Page->articulo_unidad_medida->viewAttributes() ?>>
<?= $Page->articulo_unidad_medida->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
    <tr id="r_costo_unidad">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_costo_unidad"><?= $Page->costo_unidad->caption() ?></span></td>
        <td data-name="costo_unidad" <?= $Page->costo_unidad->cellAttributes() ?>>
<span id="el_entradas_salidas_costo_unidad">
<span<?= $Page->costo_unidad->viewAttributes() ?>>
<?= $Page->costo_unidad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
    <tr id="r_costo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_costo"><?= $Page->costo->caption() ?></span></td>
        <td data-name="costo" <?= $Page->costo->cellAttributes() ?>>
<span id="el_entradas_salidas_costo">
<span<?= $Page->costo->viewAttributes() ?>>
<?= $Page->costo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
    <tr id="r_precio_unidad">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_precio_unidad"><?= $Page->precio_unidad->caption() ?></span></td>
        <td data-name="precio_unidad" <?= $Page->precio_unidad->cellAttributes() ?>>
<span id="el_entradas_salidas_precio_unidad">
<span<?= $Page->precio_unidad->viewAttributes() ?>>
<?= $Page->precio_unidad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <tr id="r_precio">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_precio"><?= $Page->precio->caption() ?></span></td>
        <td data-name="precio" <?= $Page->precio->cellAttributes() ?>>
<span id="el_entradas_salidas_precio">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
    <tr id="r_lote">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_lote"><?= $Page->lote->caption() ?></span></td>
        <td data-name="lote" <?= $Page->lote->cellAttributes() ?>>
<span id="el_entradas_salidas_lote">
<span<?= $Page->lote->viewAttributes() ?>>
<?= $Page->lote->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
<?php } ?>
<?php } ?>
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
