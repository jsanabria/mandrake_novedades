<?php

namespace PHPMaker2021\mandrake;

// Page object
$ClienteView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fclienteview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fclienteview = currentForm = new ew.Form("fclienteview", "view");
    loadjs.done("fclienteview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.cliente) ew.vars.tables.cliente = <?= JsonEncode(GetClientVar("tables", "cliente")) ?>;
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
<form name="fclienteview" id="fclienteview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cliente">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->codigo->Visible) { // codigo ?>
    <tr id="r_codigo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_codigo"><?= $Page->codigo->caption() ?></span></td>
        <td data-name="codigo" <?= $Page->codigo->cellAttributes() ?>>
<span id="el_cliente_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <tr id="r_ci_rif">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_ci_rif"><?= $Page->ci_rif->caption() ?></span></td>
        <td data-name="ci_rif" <?= $Page->ci_rif->cellAttributes() ?>>
<span id="el_cliente_ci_rif">
<span<?= $Page->ci_rif->viewAttributes() ?>>
<?= $Page->ci_rif->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <tr id="r_nombre">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_nombre"><?= $Page->nombre->caption() ?></span></td>
        <td data-name="nombre" <?= $Page->nombre->cellAttributes() ?>>
<span id="el_cliente_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sucursal->Visible) { // sucursal ?>
    <tr id="r_sucursal">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_sucursal"><?= $Page->sucursal->caption() ?></span></td>
        <td data-name="sucursal" <?= $Page->sucursal->cellAttributes() ?>>
<span id="el_cliente_sucursal">
<span<?= $Page->sucursal->viewAttributes() ?>>
<?= $Page->sucursal->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->contacto->Visible) { // contacto ?>
    <tr id="r_contacto">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_contacto"><?= $Page->contacto->caption() ?></span></td>
        <td data-name="contacto" <?= $Page->contacto->cellAttributes() ?>>
<span id="el_cliente_contacto">
<span<?= $Page->contacto->viewAttributes() ?>>
<?= $Page->contacto->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
    <tr id="r_ciudad">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_ciudad"><?= $Page->ciudad->caption() ?></span></td>
        <td data-name="ciudad" <?= $Page->ciudad->cellAttributes() ?>>
<span id="el_cliente_ciudad">
<span<?= $Page->ciudad->viewAttributes() ?>>
<?= $Page->ciudad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <tr id="r_direccion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_direccion"><?= $Page->direccion->caption() ?></span></td>
        <td data-name="direccion" <?= $Page->direccion->cellAttributes() ?>>
<span id="el_cliente_direccion">
<span<?= $Page->direccion->viewAttributes() ?>>
<?= $Page->direccion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->telefono1->Visible) { // telefono1 ?>
    <tr id="r_telefono1">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_telefono1"><?= $Page->telefono1->caption() ?></span></td>
        <td data-name="telefono1" <?= $Page->telefono1->cellAttributes() ?>>
<span id="el_cliente_telefono1">
<span<?= $Page->telefono1->viewAttributes() ?>>
<?= $Page->telefono1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->telefono2->Visible) { // telefono2 ?>
    <tr id="r_telefono2">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_telefono2"><?= $Page->telefono2->caption() ?></span></td>
        <td data-name="telefono2" <?= $Page->telefono2->cellAttributes() ?>>
<span id="el_cliente_telefono2">
<span<?= $Page->telefono2->viewAttributes() ?>>
<?= $Page->telefono2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->email1->Visible) { // email1 ?>
    <tr id="r_email1">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_email1"><?= $Page->email1->caption() ?></span></td>
        <td data-name="email1" <?= $Page->email1->cellAttributes() ?>>
<span id="el_cliente_email1">
<span<?= $Page->email1->viewAttributes() ?>>
<?= $Page->email1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->email2->Visible) { // email2 ?>
    <tr id="r_email2">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_email2"><?= $Page->email2->caption() ?></span></td>
        <td data-name="email2" <?= $Page->email2->cellAttributes() ?>>
<span id="el_cliente_email2">
<span<?= $Page->email2->viewAttributes() ?>>
<?= $Page->email2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->web->Visible) { // web ?>
    <tr id="r_web">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_web"><?= $Page->web->caption() ?></span></td>
        <td data-name="web" <?= $Page->web->cellAttributes() ?>>
<span id="el_cliente_web">
<span<?= $Page->web->viewAttributes() ?>>
<?= $Page->web->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_cliente->Visible) { // tipo_cliente ?>
    <tr id="r_tipo_cliente">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_tipo_cliente"><?= $Page->tipo_cliente->caption() ?></span></td>
        <td data-name="tipo_cliente" <?= $Page->tipo_cliente->cellAttributes() ?>>
<span id="el_cliente_tipo_cliente">
<span<?= $Page->tipo_cliente->viewAttributes() ?>>
<?= $Page->tipo_cliente->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
    <tr id="r_tarifa">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_tarifa"><?= $Page->tarifa->caption() ?></span></td>
        <td data-name="tarifa" <?= $Page->tarifa->cellAttributes() ?>>
<span id="el_cliente_tarifa">
<span<?= $Page->tarifa->viewAttributes() ?>>
<?= $Page->tarifa->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <tr id="r_cuenta">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_cuenta"><?= $Page->cuenta->caption() ?></span></td>
        <td data-name="cuenta" <?= $Page->cuenta->cellAttributes() ?>>
<span id="el_cliente_cuenta">
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <tr id="r_activo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_activo"><?= $Page->activo->caption() ?></span></td>
        <td data-name="activo" <?= $Page->activo->cellAttributes() ?>>
<span id="el_cliente_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->consignacion->Visible) { // consignacion ?>
    <tr id="r_consignacion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_consignacion"><?= $Page->consignacion->caption() ?></span></td>
        <td data-name="consignacion" <?= $Page->consignacion->cellAttributes() ?>>
<span id="el_cliente_consignacion">
<span<?= $Page->consignacion->viewAttributes() ?>>
<?= $Page->consignacion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->refiere->Visible) { // refiere ?>
    <tr id="r_refiere">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_refiere"><?= $Page->refiere->caption() ?></span></td>
        <td data-name="refiere" <?= $Page->refiere->cellAttributes() ?>>
<span id="el_cliente_refiere">
<span<?= $Page->refiere->viewAttributes() ?>>
<?= $Page->refiere->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->puntos_refiere->Visible) { // puntos_refiere ?>
    <tr id="r_puntos_refiere">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cliente_puntos_refiere"><?= $Page->puntos_refiere->caption() ?></span></td>
        <td data-name="puntos_refiere" <?= $Page->puntos_refiere->cellAttributes() ?>>
<span id="el_cliente_puntos_refiere">
<span<?= $Page->puntos_refiere->viewAttributes() ?>>
<?= $Page->puntos_refiere->getViewValue() ?></span>
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
