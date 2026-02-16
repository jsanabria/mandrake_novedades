<?php

namespace PHPMaker2021\mandrake;

// Page object
$TablaRetencionesDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var ftabla_retencionesdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    ftabla_retencionesdelete = currentForm = new ew.Form("ftabla_retencionesdelete", "delete");
    loadjs.done("ftabla_retencionesdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.tabla_retenciones) ew.vars.tables.tabla_retenciones = <?= JsonEncode(GetClientVar("tables", "tabla_retenciones")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="ftabla_retencionesdelete" id="ftabla_retencionesdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tabla_retenciones">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->id->Visible) { // id ?>
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_tabla_retenciones_id" class="tabla_retenciones_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
        <th class="<?= $Page->codigo->headerCellClass() ?>"><span id="elh_tabla_retenciones_codigo" class="tabla_retenciones_codigo"><?= $Page->codigo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <th class="<?= $Page->tipo->headerCellClass() ?>"><span id="elh_tabla_retenciones_tipo" class="tabla_retenciones_tipo"><?= $Page->tipo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->base_imponible->Visible) { // base_imponible ?>
        <th class="<?= $Page->base_imponible->headerCellClass() ?>"><span id="elh_tabla_retenciones_base_imponible" class="tabla_retenciones_base_imponible"><?= $Page->base_imponible->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
        <th class="<?= $Page->tarifa->headerCellClass() ?>"><span id="elh_tabla_retenciones_tarifa" class="tabla_retenciones_tarifa"><?= $Page->tarifa->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sustraendo->Visible) { // sustraendo ?>
        <th class="<?= $Page->sustraendo->headerCellClass() ?>"><span id="elh_tabla_retenciones_sustraendo" class="tabla_retenciones_sustraendo"><?= $Page->sustraendo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pagos_mayores->Visible) { // pagos_mayores ?>
        <th class="<?= $Page->pagos_mayores->headerCellClass() ?>"><span id="elh_tabla_retenciones_pagos_mayores" class="tabla_retenciones_pagos_mayores"><?= $Page->pagos_mayores->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><span id="elh_tabla_retenciones_activo" class="tabla_retenciones_activo"><?= $Page->activo->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->id->Visible) { // id ?>
        <td <?= $Page->id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_id" class="tabla_retenciones_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
        <td <?= $Page->codigo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_codigo" class="tabla_retenciones_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <td <?= $Page->tipo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_tipo" class="tabla_retenciones_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->base_imponible->Visible) { // base_imponible ?>
        <td <?= $Page->base_imponible->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_base_imponible" class="tabla_retenciones_base_imponible">
<span<?= $Page->base_imponible->viewAttributes() ?>>
<?= $Page->base_imponible->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
        <td <?= $Page->tarifa->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_tarifa" class="tabla_retenciones_tarifa">
<span<?= $Page->tarifa->viewAttributes() ?>>
<?= $Page->tarifa->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->sustraendo->Visible) { // sustraendo ?>
        <td <?= $Page->sustraendo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_sustraendo" class="tabla_retenciones_sustraendo">
<span<?= $Page->sustraendo->viewAttributes() ?>>
<?= $Page->sustraendo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pagos_mayores->Visible) { // pagos_mayores ?>
        <td <?= $Page->pagos_mayores->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_pagos_mayores" class="tabla_retenciones_pagos_mayores">
<span<?= $Page->pagos_mayores->viewAttributes() ?>>
<?= $Page->pagos_mayores->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <td <?= $Page->activo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_activo" class="tabla_retenciones_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
