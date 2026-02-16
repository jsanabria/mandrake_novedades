<?php

namespace PHPMaker2021\mandrake;

// Page object
$ProveedorView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fproveedorview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fproveedorview = currentForm = new ew.Form("fproveedorview", "view");
    loadjs.done("fproveedorview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.proveedor) ew.vars.tables.proveedor = <?= JsonEncode(GetClientVar("tables", "proveedor")) ?>;
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
<form name="fproveedorview" id="fproveedorview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="proveedor">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <tr id="r_ci_rif">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_ci_rif"><?= $Page->ci_rif->caption() ?></span></td>
        <td data-name="ci_rif" <?= $Page->ci_rif->cellAttributes() ?>>
<span id="el_proveedor_ci_rif">
<span<?= $Page->ci_rif->viewAttributes() ?>>
<?= $Page->ci_rif->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <tr id="r_nombre">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_nombre"><?= $Page->nombre->caption() ?></span></td>
        <td data-name="nombre" <?= $Page->nombre->cellAttributes() ?>>
<span id="el_proveedor_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
    <tr id="r_ciudad">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_ciudad"><?= $Page->ciudad->caption() ?></span></td>
        <td data-name="ciudad" <?= $Page->ciudad->cellAttributes() ?>>
<span id="el_proveedor_ciudad">
<span<?= $Page->ciudad->viewAttributes() ?>>
<?= $Page->ciudad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <tr id="r_direccion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_direccion"><?= $Page->direccion->caption() ?></span></td>
        <td data-name="direccion" <?= $Page->direccion->cellAttributes() ?>>
<span id="el_proveedor_direccion">
<span<?= $Page->direccion->viewAttributes() ?>>
<?= $Page->direccion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->telefono1->Visible) { // telefono1 ?>
    <tr id="r_telefono1">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_telefono1"><?= $Page->telefono1->caption() ?></span></td>
        <td data-name="telefono1" <?= $Page->telefono1->cellAttributes() ?>>
<span id="el_proveedor_telefono1">
<span<?= $Page->telefono1->viewAttributes() ?>>
<?= $Page->telefono1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->telefono2->Visible) { // telefono2 ?>
    <tr id="r_telefono2">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_telefono2"><?= $Page->telefono2->caption() ?></span></td>
        <td data-name="telefono2" <?= $Page->telefono2->cellAttributes() ?>>
<span id="el_proveedor_telefono2">
<span<?= $Page->telefono2->viewAttributes() ?>>
<?= $Page->telefono2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->email1->Visible) { // email1 ?>
    <tr id="r_email1">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_email1"><?= $Page->email1->caption() ?></span></td>
        <td data-name="email1" <?= $Page->email1->cellAttributes() ?>>
<span id="el_proveedor_email1">
<span<?= $Page->email1->viewAttributes() ?>>
<?= $Page->email1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->email2->Visible) { // email2 ?>
    <tr id="r_email2">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_email2"><?= $Page->email2->caption() ?></span></td>
        <td data-name="email2" <?= $Page->email2->cellAttributes() ?>>
<span id="el_proveedor_email2">
<span<?= $Page->email2->viewAttributes() ?>>
<?= $Page->email2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cta_bco->Visible) { // cta_bco ?>
    <tr id="r_cta_bco">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_cta_bco"><?= $Page->cta_bco->caption() ?></span></td>
        <td data-name="cta_bco" <?= $Page->cta_bco->cellAttributes() ?>>
<span id="el_proveedor_cta_bco">
<span<?= $Page->cta_bco->viewAttributes() ?>>
<?= $Page->cta_bco->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <tr id="r_activo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_activo"><?= $Page->activo->caption() ?></span></td>
        <td data-name="activo" <?= $Page->activo->cellAttributes() ?>>
<span id="el_proveedor_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
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
    if (in_array("proveedor_articulo", explode(",", $Page->getCurrentDetailTable())) && $proveedor_articulo->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("proveedor_articulo", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ProveedorArticuloGrid.php" ?>
<?php } ?>
<?php
    if (in_array("adjunto", explode(",", $Page->getCurrentDetailTable())) && $adjunto->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("adjunto", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "AdjuntoGrid.php" ?>
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
