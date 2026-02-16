<?php

namespace PHPMaker2021\mandrake;

// Page object
$CompaniaCuentaView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcompania_cuentaview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcompania_cuentaview = currentForm = new ew.Form("fcompania_cuentaview", "view");
    loadjs.done("fcompania_cuentaview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.compania_cuenta) ew.vars.tables.compania_cuenta = <?= JsonEncode(GetClientVar("tables", "compania_cuenta")) ?>;
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
<form name="fcompania_cuentaview" id="fcompania_cuentaview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="compania_cuenta">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_cuenta_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id" <?= $Page->id->cellAttributes() ?>>
<span id="el_compania_cuenta_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
    <tr id="r_banco">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_cuenta_banco"><?= $Page->banco->caption() ?></span></td>
        <td data-name="banco" <?= $Page->banco->cellAttributes() ?>>
<span id="el_compania_cuenta_banco">
<span<?= $Page->banco->viewAttributes() ?>>
<?= $Page->banco->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->titular->Visible) { // titular ?>
    <tr id="r_titular">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_cuenta_titular"><?= $Page->titular->caption() ?></span></td>
        <td data-name="titular" <?= $Page->titular->cellAttributes() ?>>
<span id="el_compania_cuenta_titular">
<span<?= $Page->titular->viewAttributes() ?>>
<?= $Page->titular->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <tr id="r_tipo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_cuenta_tipo"><?= $Page->tipo->caption() ?></span></td>
        <td data-name="tipo" <?= $Page->tipo->cellAttributes() ?>>
<span id="el_compania_cuenta_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
    <tr id="r_numero">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_cuenta_numero"><?= $Page->numero->caption() ?></span></td>
        <td data-name="numero" <?= $Page->numero->cellAttributes() ?>>
<span id="el_compania_cuenta_numero">
<span<?= $Page->numero->viewAttributes() ?>>
<?= $Page->numero->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mostrar->Visible) { // mostrar ?>
    <tr id="r_mostrar">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_cuenta_mostrar"><?= $Page->mostrar->caption() ?></span></td>
        <td data-name="mostrar" <?= $Page->mostrar->cellAttributes() ?>>
<span id="el_compania_cuenta_mostrar">
<span<?= $Page->mostrar->viewAttributes() ?>>
<?= $Page->mostrar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <tr id="r_cuenta">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_cuenta_cuenta"><?= $Page->cuenta->caption() ?></span></td>
        <td data-name="cuenta" <?= $Page->cuenta->cellAttributes() ?>>
<span id="el_compania_cuenta_cuenta">
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pago_electronico->Visible) { // pago_electronico ?>
    <tr id="r_pago_electronico">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_cuenta_pago_electronico"><?= $Page->pago_electronico->caption() ?></span></td>
        <td data-name="pago_electronico" <?= $Page->pago_electronico->cellAttributes() ?>>
<span id="el_compania_cuenta_pago_electronico">
<span<?= $Page->pago_electronico->viewAttributes() ?>>
<?= $Page->pago_electronico->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <tr id="r_activo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_cuenta_activo"><?= $Page->activo->caption() ?></span></td>
        <td data-name="activo" <?= $Page->activo->cellAttributes() ?>>
<span id="el_compania_cuenta_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->compania->Visible) { // compania ?>
    <tr id="r_compania">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compania_cuenta_compania"><?= $Page->compania->caption() ?></span></td>
        <td data-name="compania" <?= $Page->compania->cellAttributes() ?>>
<span id="el_compania_cuenta_compania">
<span<?= $Page->compania->viewAttributes() ?>>
<?= $Page->compania->getViewValue() ?></span>
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
