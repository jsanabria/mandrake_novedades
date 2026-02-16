<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContPlanctaView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_planctaview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcont_planctaview = currentForm = new ew.Form("fcont_planctaview", "view");
    loadjs.done("fcont_planctaview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.cont_plancta) ew.vars.tables.cont_plancta = <?= JsonEncode(GetClientVar("tables", "cont_plancta")) ?>;
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
<form name="fcont_planctaview" id="fcont_planctaview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_plancta">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->clase->Visible) { // clase ?>
    <tr id="r_clase">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_plancta_clase"><?= $Page->clase->caption() ?></span></td>
        <td data-name="clase" <?= $Page->clase->cellAttributes() ?>>
<span id="el_cont_plancta_clase">
<span<?= $Page->clase->viewAttributes() ?>>
<?= $Page->clase->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->grupo->Visible) { // grupo ?>
    <tr id="r_grupo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_plancta_grupo"><?= $Page->grupo->caption() ?></span></td>
        <td data-name="grupo" <?= $Page->grupo->cellAttributes() ?>>
<span id="el_cont_plancta_grupo">
<span<?= $Page->grupo->viewAttributes() ?>>
<?= $Page->grupo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <tr id="r_cuenta">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_plancta_cuenta"><?= $Page->cuenta->caption() ?></span></td>
        <td data-name="cuenta" <?= $Page->cuenta->cellAttributes() ?>>
<span id="el_cont_plancta_cuenta">
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->subcuenta->Visible) { // subcuenta ?>
    <tr id="r_subcuenta">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_plancta_subcuenta"><?= $Page->subcuenta->caption() ?></span></td>
        <td data-name="subcuenta" <?= $Page->subcuenta->cellAttributes() ?>>
<span id="el_cont_plancta_subcuenta">
<span<?= $Page->subcuenta->viewAttributes() ?>>
<?= $Page->subcuenta->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <tr id="r_descripcion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_plancta_descripcion"><?= $Page->descripcion->caption() ?></span></td>
        <td data-name="descripcion" <?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_plancta_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->clasificacion->Visible) { // clasificacion ?>
    <tr id="r_clasificacion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_plancta_clasificacion"><?= $Page->clasificacion->caption() ?></span></td>
        <td data-name="clasificacion" <?= $Page->clasificacion->cellAttributes() ?>>
<span id="el_cont_plancta_clasificacion">
<span<?= $Page->clasificacion->viewAttributes() ?>>
<?= $Page->clasificacion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->naturaleza->Visible) { // naturaleza ?>
    <tr id="r_naturaleza">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_plancta_naturaleza"><?= $Page->naturaleza->caption() ?></span></td>
        <td data-name="naturaleza" <?= $Page->naturaleza->cellAttributes() ?>>
<span id="el_cont_plancta_naturaleza">
<span<?= $Page->naturaleza->viewAttributes() ?>>
<?= $Page->naturaleza->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <tr id="r_tipo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_plancta_tipo"><?= $Page->tipo->caption() ?></span></td>
        <td data-name="tipo" <?= $Page->tipo->cellAttributes() ?>>
<span id="el_cont_plancta_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_plancta_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda" <?= $Page->moneda->cellAttributes() ?>>
<span id="el_cont_plancta_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->activa->Visible) { // activa ?>
    <tr id="r_activa">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_plancta_activa"><?= $Page->activa->caption() ?></span></td>
        <td data-name="activa" <?= $Page->activa->cellAttributes() ?>>
<span id="el_cont_plancta_activa">
<span<?= $Page->activa->viewAttributes() ?>>
<?= $Page->activa->getViewValue() ?></span>
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
