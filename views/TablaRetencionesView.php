<?php

namespace PHPMaker2021\mandrake;

// Page object
$TablaRetencionesView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var ftabla_retencionesview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    ftabla_retencionesview = currentForm = new ew.Form("ftabla_retencionesview", "view");
    loadjs.done("ftabla_retencionesview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.tabla_retenciones) ew.vars.tables.tabla_retenciones = <?= JsonEncode(GetClientVar("tables", "tabla_retenciones")) ?>;
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
<form name="ftabla_retencionesview" id="ftabla_retencionesview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tabla_retenciones">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id" <?= $Page->id->cellAttributes() ?>>
<span id="el_tabla_retenciones_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
    <tr id="r_codigo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_codigo"><?= $Page->codigo->caption() ?></span></td>
        <td data-name="codigo" <?= $Page->codigo->cellAttributes() ?>>
<span id="el_tabla_retenciones_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <tr id="r_tipo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_tipo"><?= $Page->tipo->caption() ?></span></td>
        <td data-name="tipo" <?= $Page->tipo->cellAttributes() ?>>
<span id="el_tabla_retenciones_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->base_imponible->Visible) { // base_imponible ?>
    <tr id="r_base_imponible">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_base_imponible"><?= $Page->base_imponible->caption() ?></span></td>
        <td data-name="base_imponible" <?= $Page->base_imponible->cellAttributes() ?>>
<span id="el_tabla_retenciones_base_imponible">
<span<?= $Page->base_imponible->viewAttributes() ?>>
<?= $Page->base_imponible->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
    <tr id="r_tarifa">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_tarifa"><?= $Page->tarifa->caption() ?></span></td>
        <td data-name="tarifa" <?= $Page->tarifa->cellAttributes() ?>>
<span id="el_tabla_retenciones_tarifa">
<span<?= $Page->tarifa->viewAttributes() ?>>
<?= $Page->tarifa->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sustraendo->Visible) { // sustraendo ?>
    <tr id="r_sustraendo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_sustraendo"><?= $Page->sustraendo->caption() ?></span></td>
        <td data-name="sustraendo" <?= $Page->sustraendo->cellAttributes() ?>>
<span id="el_tabla_retenciones_sustraendo">
<span<?= $Page->sustraendo->viewAttributes() ?>>
<?= $Page->sustraendo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pagos_mayores->Visible) { // pagos_mayores ?>
    <tr id="r_pagos_mayores">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_pagos_mayores"><?= $Page->pagos_mayores->caption() ?></span></td>
        <td data-name="pagos_mayores" <?= $Page->pagos_mayores->cellAttributes() ?>>
<span id="el_tabla_retenciones_pagos_mayores">
<span<?= $Page->pagos_mayores->viewAttributes() ?>>
<?= $Page->pagos_mayores->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <tr id="r_activo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_activo"><?= $Page->activo->caption() ?></span></td>
        <td data-name="activo" <?= $Page->activo->cellAttributes() ?>>
<span id="el_tabla_retenciones_activo">
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
