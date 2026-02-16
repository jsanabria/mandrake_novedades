<?php

namespace PHPMaker2021\mandrake;

// Page object
$UserlevelsView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fuserlevelsview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fuserlevelsview = currentForm = new ew.Form("fuserlevelsview", "view");
    loadjs.done("fuserlevelsview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.userlevels) ew.vars.tables.userlevels = <?= JsonEncode(GetClientVar("tables", "userlevels")) ?>;
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
<form name="fuserlevelsview" id="fuserlevelsview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="userlevels">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->userlevelid->Visible) { // userlevelid ?>
    <tr id="r_userlevelid">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_userlevels_userlevelid"><?= $Page->userlevelid->caption() ?></span></td>
        <td data-name="userlevelid" <?= $Page->userlevelid->cellAttributes() ?>>
<span id="el_userlevels_userlevelid">
<span<?= $Page->userlevelid->viewAttributes() ?>>
<?= $Page->userlevelid->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->userlevelname->Visible) { // userlevelname ?>
    <tr id="r_userlevelname">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_userlevels_userlevelname"><?= $Page->userlevelname->caption() ?></span></td>
        <td data-name="userlevelname" <?= $Page->userlevelname->cellAttributes() ?>>
<span id="el_userlevels_userlevelname">
<span<?= $Page->userlevelname->viewAttributes() ?>>
<?= $Page->userlevelname->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_acceso->Visible) { // tipo_acceso ?>
    <tr id="r_tipo_acceso">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_userlevels_tipo_acceso"><?= $Page->tipo_acceso->caption() ?></span></td>
        <td data-name="tipo_acceso" <?= $Page->tipo_acceso->cellAttributes() ?>>
<span id="el_userlevels_tipo_acceso">
<span<?= $Page->tipo_acceso->viewAttributes() ?>>
<?= $Page->tipo_acceso->getViewValue() ?></span>
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
<?php
    if (in_array("grupo_funciones", explode(",", $Page->getCurrentDetailTable())) && $grupo_funciones->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("grupo_funciones", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "GrupoFuncionesGrid.php" ?>
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
