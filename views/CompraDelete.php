<?php

namespace PHPMaker2021\mandrake;

// Page object
$CompraDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcompradelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fcompradelete = currentForm = new ew.Form("fcompradelete", "delete");
    loadjs.done("fcompradelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.compra) ew.vars.tables.compra = <?= JsonEncode(GetClientVar("tables", "compra")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcompradelete" id="fcompradelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="compra">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_compra_id" class="compra_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <th class="<?= $Page->proveedor->headerCellClass() ?>"><span id="elh_compra_proveedor" class="compra_proveedor"><?= $Page->proveedor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><span id="elh_compra_tipo_documento" class="compra_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
        <th class="<?= $Page->documento->headerCellClass() ?>"><span id="elh_compra_documento" class="compra_documento"><?= $Page->documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_compra_fecha" class="compra_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <th class="<?= $Page->descripcion->headerCellClass() ?>"><span id="elh_compra_descripcion" class="compra_descripcion"><?= $Page->descripcion->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <th class="<?= $Page->monto_total->headerCellClass() ?>"><span id="elh_compra_monto_total" class="compra_monto_total"><?= $Page->monto_total->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_compra_id" class="compra_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <td <?= $Page->proveedor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compra_proveedor" class="compra_proveedor">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <td <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compra_tipo_documento" class="compra_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
        <td <?= $Page->documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compra_documento" class="compra_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compra_fecha" class="compra_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <td <?= $Page->descripcion->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compra_descripcion" class="compra_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <td <?= $Page->monto_total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compra_monto_total" class="compra_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?= $Page->monto_total->getViewValue() ?></span>
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
