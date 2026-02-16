<?php

namespace PHPMaker2021\mandrake;

// Page object
$ArticuloUnidadMedidaView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var farticulo_unidad_medidaview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    farticulo_unidad_medidaview = currentForm = new ew.Form("farticulo_unidad_medidaview", "view");
    loadjs.done("farticulo_unidad_medidaview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.articulo_unidad_medida) ew.vars.tables.articulo_unidad_medida = <?= JsonEncode(GetClientVar("tables", "articulo_unidad_medida")) ?>;
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
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="farticulo_unidad_medidaview" id="farticulo_unidad_medidaview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="articulo_unidad_medida">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->unidad_medida->Visible) { // unidad_medida ?>
    <tr id="r_unidad_medida">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_unidad_medida_unidad_medida"><?= $Page->unidad_medida->caption() ?></span></td>
        <td data-name="unidad_medida" <?= $Page->unidad_medida->cellAttributes() ?>>
<span id="el_articulo_unidad_medida_unidad_medida">
<span<?= $Page->unidad_medida->viewAttributes() ?>>
<?= $Page->unidad_medida->getViewValue() ?></span>
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
