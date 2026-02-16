<?php

namespace PHPMaker2021\mandrake;

// Page object
$CompaniaView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcompaniaview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcompaniaview = currentForm = new ew.Form("fcompaniaview", "view");
    loadjs.done("fcompaniaview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.compania) ew.vars.tables.compania = <?= JsonEncode(GetClientVar("tables", "compania")) ?>;
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
<form name="fcompaniaview" id="fcompaniaview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="compania">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <tr id="r_ci_rif">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_ci_rif"><?= $Page->ci_rif->caption() ?></span></td>
        <td data-name="ci_rif" <?= $Page->ci_rif->cellAttributes() ?>>
<span id="el_compania_ci_rif">
<span<?= $Page->ci_rif->viewAttributes() ?>>
<?= $Page->ci_rif->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <tr id="r_nombre">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_nombre"><?= $Page->nombre->caption() ?></span></td>
        <td data-name="nombre" <?= $Page->nombre->cellAttributes() ?>>
<span id="el_compania_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
    <tr id="r_ciudad">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_ciudad"><?= $Page->ciudad->caption() ?></span></td>
        <td data-name="ciudad" <?= $Page->ciudad->cellAttributes() ?>>
<span id="el_compania_ciudad">
<span<?= $Page->ciudad->viewAttributes() ?>>
<?= $Page->ciudad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <tr id="r_direccion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_direccion"><?= $Page->direccion->caption() ?></span></td>
        <td data-name="direccion" <?= $Page->direccion->cellAttributes() ?>>
<span id="el_compania_direccion">
<span<?= $Page->direccion->viewAttributes() ?>>
<?= $Page->direccion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->telefono1->Visible) { // telefono1 ?>
    <tr id="r_telefono1">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_telefono1"><?= $Page->telefono1->caption() ?></span></td>
        <td data-name="telefono1" <?= $Page->telefono1->cellAttributes() ?>>
<span id="el_compania_telefono1">
<span<?= $Page->telefono1->viewAttributes() ?>>
<?= $Page->telefono1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->telefono2->Visible) { // telefono2 ?>
    <tr id="r_telefono2">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_telefono2"><?= $Page->telefono2->caption() ?></span></td>
        <td data-name="telefono2" <?= $Page->telefono2->cellAttributes() ?>>
<span id="el_compania_telefono2">
<span<?= $Page->telefono2->viewAttributes() ?>>
<?= $Page->telefono2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->email1->Visible) { // email1 ?>
    <tr id="r_email1">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_email1"><?= $Page->email1->caption() ?></span></td>
        <td data-name="email1" <?= $Page->email1->cellAttributes() ?>>
<span id="el_compania_email1">
<span<?= $Page->email1->viewAttributes() ?>>
<?= $Page->email1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->email2->Visible) { // email2 ?>
    <tr id="r_email2">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_email2"><?= $Page->email2->caption() ?></span></td>
        <td data-name="email2" <?= $Page->email2->cellAttributes() ?>>
<span id="el_compania_email2">
<span<?= $Page->email2->viewAttributes() ?>>
<?= $Page->email2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->agente_retencion->Visible) { // agente_retencion ?>
    <tr id="r_agente_retencion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_agente_retencion"><?= $Page->agente_retencion->caption() ?></span></td>
        <td data-name="agente_retencion" <?= $Page->agente_retencion->cellAttributes() ?>>
<span id="el_compania_agente_retencion">
<span<?= $Page->agente_retencion->viewAttributes() ?>>
<?= $Page->agente_retencion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->logo->Visible) { // logo ?>
    <tr id="r_logo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_logo"><?= $Page->logo->caption() ?></span></td>
        <td data-name="logo" <?= $Page->logo->cellAttributes() ?>>
<span id="el_compania_logo">
<span>
<?= GetFileViewTag($Page->logo, $Page->logo->getViewValue(), false) ?>
</span>
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
    if (in_array("compania_cuenta", explode(",", $Page->getCurrentDetailTable())) && $compania_cuenta->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("compania_cuenta", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "CompaniaCuentaGrid.php" ?>
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
