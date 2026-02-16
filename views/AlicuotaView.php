<?php

namespace PHPMaker2021\mandrake;

// Page object
$AlicuotaView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var falicuotaview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    falicuotaview = currentForm = new ew.Form("falicuotaview", "view");
    loadjs.done("falicuotaview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.alicuota) ew.vars.tables.alicuota = <?= JsonEncode(GetClientVar("tables", "alicuota")) ?>;
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
<form name="falicuotaview" id="falicuotaview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="alicuota">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->codigo->Visible) { // codigo ?>
    <tr id="r_codigo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_alicuota_codigo"><?= $Page->codigo->caption() ?></span></td>
        <td data-name="codigo" <?= $Page->codigo->cellAttributes() ?>>
<span id="el_alicuota_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <tr id="r_nombre">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_alicuota_nombre"><?= $Page->nombre->caption() ?></span></td>
        <td data-name="nombre" <?= $Page->nombre->cellAttributes() ?>>
<span id="el_alicuota_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alicuota->Visible) { // alicuota ?>
    <tr id="r_alicuota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_alicuota_alicuota"><?= $Page->alicuota->caption() ?></span></td>
        <td data-name="alicuota" <?= $Page->alicuota->cellAttributes() ?>>
<span id="el_alicuota_alicuota">
<span<?= $Page->alicuota->viewAttributes() ?>>
<?= $Page->alicuota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_alicuota_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_alicuota_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
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
