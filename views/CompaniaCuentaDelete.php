<?php

namespace PHPMaker2021\mandrake;

// Page object
$CompaniaCuentaDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcompania_cuentadelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fcompania_cuentadelete = currentForm = new ew.Form("fcompania_cuentadelete", "delete");
    loadjs.done("fcompania_cuentadelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.compania_cuenta) ew.vars.tables.compania_cuenta = <?= JsonEncode(GetClientVar("tables", "compania_cuenta")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcompania_cuentadelete" id="fcompania_cuentadelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="compania_cuenta">
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
<?php if ($Page->banco->Visible) { // banco ?>
        <th class="<?= $Page->banco->headerCellClass() ?>"><span id="elh_compania_cuenta_banco" class="compania_cuenta_banco"><?= $Page->banco->caption() ?></span></th>
<?php } ?>
<?php if ($Page->titular->Visible) { // titular ?>
        <th class="<?= $Page->titular->headerCellClass() ?>"><span id="elh_compania_cuenta_titular" class="compania_cuenta_titular"><?= $Page->titular->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <th class="<?= $Page->tipo->headerCellClass() ?>"><span id="elh_compania_cuenta_tipo" class="compania_cuenta_tipo"><?= $Page->tipo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
        <th class="<?= $Page->numero->headerCellClass() ?>"><span id="elh_compania_cuenta_numero" class="compania_cuenta_numero"><?= $Page->numero->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mostrar->Visible) { // mostrar ?>
        <th class="<?= $Page->mostrar->headerCellClass() ?>"><span id="elh_compania_cuenta_mostrar" class="compania_cuenta_mostrar"><?= $Page->mostrar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><span id="elh_compania_cuenta_cuenta" class="compania_cuenta_cuenta"><?= $Page->cuenta->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pago_electronico->Visible) { // pago_electronico ?>
        <th class="<?= $Page->pago_electronico->headerCellClass() ?>"><span id="elh_compania_cuenta_pago_electronico" class="compania_cuenta_pago_electronico"><?= $Page->pago_electronico->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><span id="elh_compania_cuenta_activo" class="compania_cuenta_activo"><?= $Page->activo->caption() ?></span></th>
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
<?php if ($Page->banco->Visible) { // banco ?>
        <td <?= $Page->banco->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_banco" class="compania_cuenta_banco">
<span<?= $Page->banco->viewAttributes() ?>>
<?= $Page->banco->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->titular->Visible) { // titular ?>
        <td <?= $Page->titular->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_titular" class="compania_cuenta_titular">
<span<?= $Page->titular->viewAttributes() ?>>
<?= $Page->titular->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <td <?= $Page->tipo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_tipo" class="compania_cuenta_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
        <td <?= $Page->numero->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_numero" class="compania_cuenta_numero">
<span<?= $Page->numero->viewAttributes() ?>>
<?= $Page->numero->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mostrar->Visible) { // mostrar ?>
        <td <?= $Page->mostrar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_mostrar" class="compania_cuenta_mostrar">
<span<?= $Page->mostrar->viewAttributes() ?>>
<?= $Page->mostrar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <td <?= $Page->cuenta->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_cuenta" class="compania_cuenta_cuenta">
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pago_electronico->Visible) { // pago_electronico ?>
        <td <?= $Page->pago_electronico->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_pago_electronico" class="compania_cuenta_pago_electronico">
<span<?= $Page->pago_electronico->viewAttributes() ?>>
<?= $Page->pago_electronico->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <td <?= $Page->activo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_activo" class="compania_cuenta_activo">
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
