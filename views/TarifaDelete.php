<?php

namespace PHPMaker2021\mandrake;

// Page object
$TarifaDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var ftarifadelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    ftarifadelete = currentForm = new ew.Form("ftarifadelete", "delete");
    loadjs.done("ftarifadelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.tarifa) ew.vars.tables.tarifa = <?= JsonEncode(GetClientVar("tables", "tarifa")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="ftarifadelete" id="ftarifadelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tarifa">
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
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th class="<?= $Page->nombre->headerCellClass() ?>"><span id="elh_tarifa_nombre" class="tarifa_nombre"><?= $Page->nombre->caption() ?></span></th>
<?php } ?>
<?php if ($Page->patron->Visible) { // patron ?>
        <th class="<?= $Page->patron->headerCellClass() ?>"><span id="elh_tarifa_patron" class="tarifa_patron"><?= $Page->patron->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><span id="elh_tarifa_activo" class="tarifa_activo"><?= $Page->activo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->porcentaje->Visible) { // porcentaje ?>
        <th class="<?= $Page->porcentaje->headerCellClass() ?>"><span id="elh_tarifa_porcentaje" class="tarifa_porcentaje"><?= $Page->porcentaje->caption() ?></span></th>
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
<?php if ($Page->nombre->Visible) { // nombre ?>
        <td <?= $Page->nombre->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_nombre" class="tarifa_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->patron->Visible) { // patron ?>
        <td <?= $Page->patron->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_patron" class="tarifa_patron">
<span<?= $Page->patron->viewAttributes() ?>>
<?= $Page->patron->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <td <?= $Page->activo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_activo" class="tarifa_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->porcentaje->Visible) { // porcentaje ?>
        <td <?= $Page->porcentaje->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_porcentaje" class="tarifa_porcentaje">
<span<?= $Page->porcentaje->viewAttributes() ?>>
<?= $Page->porcentaje->getViewValue() ?></span>
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
