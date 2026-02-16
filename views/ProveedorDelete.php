<?php

namespace PHPMaker2021\mandrake;

// Page object
$ProveedorDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fproveedordelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fproveedordelete = currentForm = new ew.Form("fproveedordelete", "delete");
    loadjs.done("fproveedordelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.proveedor) ew.vars.tables.proveedor = <?= JsonEncode(GetClientVar("tables", "proveedor")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fproveedordelete" id="fproveedordelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="proveedor">
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
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
        <th class="<?= $Page->ci_rif->headerCellClass() ?>"><span id="elh_proveedor_ci_rif" class="proveedor_ci_rif"><?= $Page->ci_rif->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th class="<?= $Page->nombre->headerCellClass() ?>"><span id="elh_proveedor_nombre" class="proveedor_nombre"><?= $Page->nombre->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><span id="elh_proveedor_activo" class="proveedor_activo"><?= $Page->activo->caption() ?></span></th>
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
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
        <td <?= $Page->ci_rif->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_proveedor_ci_rif" class="proveedor_ci_rif">
<span<?= $Page->ci_rif->viewAttributes() ?>>
<?= $Page->ci_rif->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <td <?= $Page->nombre->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_proveedor_nombre" class="proveedor_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <td <?= $Page->activo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_proveedor_activo" class="proveedor_activo">
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
