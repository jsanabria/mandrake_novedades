<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContLotesView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_lotesview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcont_lotesview = currentForm = new ew.Form("fcont_lotesview", "view");
    loadjs.done("fcont_lotesview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.cont_lotes) ew.vars.tables.cont_lotes = <?= JsonEncode(GetClientVar("tables", "cont_lotes")) ?>;
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
<form name="fcont_lotesview" id="fcont_lotesview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_lotes">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id" <?= $Page->id->cellAttributes() ?>>
<span id="el_cont_lotes_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_cont_lotes_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <tr id="r_comprobante">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_comprobante"><?= $Page->comprobante->caption() ?></span></td>
        <td data-name="comprobante" <?= $Page->comprobante->cellAttributes() ?>>
<span id="el_cont_lotes_comprobante">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Page->comprobante->getViewValue()) && $Page->comprobante->linkAttributes() != "") { ?>
<a<?= $Page->comprobante->linkAttributes() ?>><?= $Page->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->comprobante->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota" <?= $Page->nota->cellAttributes() ?>>
<span id="el_cont_lotes_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_registro->Visible) { // fecha_registro ?>
    <tr id="r_fecha_registro">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_fecha_registro"><?= $Page->fecha_registro->caption() ?></span></td>
        <td data-name="fecha_registro" <?= $Page->fecha_registro->cellAttributes() ?>>
<span id="el_cont_lotes_fecha_registro">
<span<?= $Page->fecha_registro->viewAttributes() ?>>
<?= $Page->fecha_registro->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
    <tr id="r_usuario">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_usuario"><?= $Page->usuario->caption() ?></span></td>
        <td data-name="usuario" <?= $Page->usuario->cellAttributes() ?>>
<span id="el_cont_lotes_usuario">
<span<?= $Page->usuario->viewAttributes() ?>>
<?= $Page->usuario->getViewValue() ?></span>
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
