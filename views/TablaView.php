<?php

namespace PHPMaker2021\mandrake;

// Page object
$TablaView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var ftablaview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    ftablaview = currentForm = new ew.Form("ftablaview", "view");
    loadjs.done("ftablaview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.tabla) ew.vars.tables.tabla = <?= JsonEncode(GetClientVar("tables", "tabla")) ?>;
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
<form name="ftablaview" id="ftablaview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tabla">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id" <?= $Page->id->cellAttributes() ?>>
<span id="el_tabla_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tabla->Visible) { // tabla ?>
    <tr id="r_tabla">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_tabla"><?= $Page->tabla->caption() ?></span></td>
        <td data-name="tabla" <?= $Page->tabla->cellAttributes() ?>>
<span id="el_tabla_tabla">
<span<?= $Page->tabla->viewAttributes() ?>>
<?= $Page->tabla->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->campo_codigo->Visible) { // campo_codigo ?>
    <tr id="r_campo_codigo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_campo_codigo"><?= $Page->campo_codigo->caption() ?></span></td>
        <td data-name="campo_codigo" <?= $Page->campo_codigo->cellAttributes() ?>>
<span id="el_tabla_campo_codigo">
<span<?= $Page->campo_codigo->viewAttributes() ?>>
<?= $Page->campo_codigo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->campo_descripcion->Visible) { // campo_descripcion ?>
    <tr id="r_campo_descripcion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_campo_descripcion"><?= $Page->campo_descripcion->caption() ?></span></td>
        <td data-name="campo_descripcion" <?= $Page->campo_descripcion->cellAttributes() ?>>
<span id="el_tabla_campo_descripcion">
<span<?= $Page->campo_descripcion->viewAttributes() ?>>
<?= $Page->campo_descripcion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->campo_dato->Visible) { // campo_dato ?>
    <tr id="r_campo_dato">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_campo_dato"><?= $Page->campo_dato->caption() ?></span></td>
        <td data-name="campo_dato" <?= $Page->campo_dato->cellAttributes() ?>>
<span id="el_tabla_campo_dato">
<span<?= $Page->campo_dato->viewAttributes() ?>>
<?= $Page->campo_dato->getViewValue() ?></span>
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
