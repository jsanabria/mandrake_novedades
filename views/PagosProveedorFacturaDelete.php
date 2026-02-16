<?php

namespace PHPMaker2021\mandrake;

// Page object
$PagosProveedorFacturaDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpagos_proveedor_facturadelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fpagos_proveedor_facturadelete = currentForm = new ew.Form("fpagos_proveedor_facturadelete", "delete");
    loadjs.done("fpagos_proveedor_facturadelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.pagos_proveedor_factura) ew.vars.tables.pagos_proveedor_factura = <?= JsonEncode(GetClientVar("tables", "pagos_proveedor_factura")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpagos_proveedor_facturadelete" id="fpagos_proveedor_facturadelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pagos_proveedor_factura">
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
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><span id="elh_pagos_proveedor_factura_tipo_documento" class="pagos_proveedor_factura_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
        <th class="<?= $Page->abono->headerCellClass() ?>"><span id="elh_pagos_proveedor_factura_abono" class="pagos_proveedor_factura_abono"><?= $Page->abono->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
        <th class="<?= $Page->monto->headerCellClass() ?>"><span id="elh_pagos_proveedor_factura_monto" class="pagos_proveedor_factura_monto"><?= $Page->monto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <th class="<?= $Page->comprobante->headerCellClass() ?>"><span id="elh_pagos_proveedor_factura_comprobante" class="pagos_proveedor_factura_comprobante"><?= $Page->comprobante->caption() ?></span></th>
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
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <td <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_factura_tipo_documento" class="pagos_proveedor_factura_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
        <td <?= $Page->abono->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_factura_abono" class="pagos_proveedor_factura_abono">
<span<?= $Page->abono->viewAttributes() ?>>
<?= $Page->abono->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
        <td <?= $Page->monto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_factura_monto" class="pagos_proveedor_factura_monto">
<span<?= $Page->monto->viewAttributes() ?>>
<?= $Page->monto->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <td <?= $Page->comprobante->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_factura_comprobante" class="pagos_proveedor_factura_comprobante">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Page->comprobante->getViewValue()) && $Page->comprobante->linkAttributes() != "") { ?>
<a<?= $Page->comprobante->linkAttributes() ?>><?= $Page->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->comprobante->getViewValue() ?>
<?php } ?>
</span>
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
