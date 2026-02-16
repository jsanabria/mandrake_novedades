<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContPeriodoContableView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_periodo_contableview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcont_periodo_contableview = currentForm = new ew.Form("fcont_periodo_contableview", "view");
    loadjs.done("fcont_periodo_contableview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.cont_periodo_contable) ew.vars.tables.cont_periodo_contable = <?= JsonEncode(GetClientVar("tables", "cont_periodo_contable")) ?>;
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
<form name="fcont_periodo_contableview" id="fcont_periodo_contableview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_periodo_contable">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_periodo_contable_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id" <?= $Page->id->cellAttributes() ?>>
<span id="el_cont_periodo_contable_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_inicio->Visible) { // fecha_inicio ?>
    <tr id="r_fecha_inicio">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_periodo_contable_fecha_inicio"><?= $Page->fecha_inicio->caption() ?></span></td>
        <td data-name="fecha_inicio" <?= $Page->fecha_inicio->cellAttributes() ?>>
<span id="el_cont_periodo_contable_fecha_inicio">
<span<?= $Page->fecha_inicio->viewAttributes() ?>>
<?= $Page->fecha_inicio->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_fin->Visible) { // fecha_fin ?>
    <tr id="r_fecha_fin">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_periodo_contable_fecha_fin"><?= $Page->fecha_fin->caption() ?></span></td>
        <td data-name="fecha_fin" <?= $Page->fecha_fin->cellAttributes() ?>>
<span id="el_cont_periodo_contable_fecha_fin">
<span<?= $Page->fecha_fin->viewAttributes() ?>>
<?= $Page->fecha_fin->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cerrado->Visible) { // cerrado ?>
    <tr id="r_cerrado">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_periodo_contable_cerrado"><?= $Page->cerrado->caption() ?></span></td>
        <td data-name="cerrado" <?= $Page->cerrado->cellAttributes() ?>>
<span id="el_cont_periodo_contable_cerrado">
<span<?= $Page->cerrado->viewAttributes() ?>>
<?= $Page->cerrado->getViewValue() ?></span>
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
