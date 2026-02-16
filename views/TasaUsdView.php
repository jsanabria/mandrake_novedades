<?php

namespace PHPMaker2021\mandrake;

// Page object
$TasaUsdView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var ftasa_usdview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    ftasa_usdview = currentForm = new ew.Form("ftasa_usdview", "view");
    loadjs.done("ftasa_usdview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.tasa_usd) ew.vars.tables.tasa_usd = <?= JsonEncode(GetClientVar("tables", "tasa_usd")) ?>;
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
<form name="ftasa_usdview" id="ftasa_usdview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tasa_usd">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tasa_usd_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda" <?= $Page->moneda->cellAttributes() ?>>
<span id="el_tasa_usd_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa->Visible) { // tasa ?>
    <tr id="r_tasa">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tasa_usd_tasa"><?= $Page->tasa->caption() ?></span></td>
        <td data-name="tasa" <?= $Page->tasa->cellAttributes() ?>>
<span id="el_tasa_usd_tasa">
<span<?= $Page->tasa->viewAttributes() ?>>
<?= $Page->tasa->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tasa_usd_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_tasa_usd_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->hora->Visible) { // hora ?>
    <tr id="r_hora">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tasa_usd_hora"><?= $Page->hora->caption() ?></span></td>
        <td data-name="hora" <?= $Page->hora->cellAttributes() ?>>
<span id="el_tasa_usd_hora">
<span<?= $Page->hora->viewAttributes() ?>>
<?= $Page->hora->getViewValue() ?></span>
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
