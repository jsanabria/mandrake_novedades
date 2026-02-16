<?php

namespace PHPMaker2021\mandrake;

// Page object
$PagosDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpagosdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fpagosdelete = currentForm = new ew.Form("fpagosdelete", "delete");
    loadjs.done("fpagosdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.pagos) ew.vars.tables.pagos = <?= JsonEncode(GetClientVar("tables", "pagos")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpagosdelete" id="fpagosdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pagos">
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
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
        <th class="<?= $Page->tipo_pago->headerCellClass() ?>"><span id="elh_pagos_tipo_pago" class="pagos_tipo_pago"><?= $Page->tipo_pago->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_pagos_fecha" class="pagos_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <th class="<?= $Page->referencia->headerCellClass() ?>"><span id="elh_pagos_referencia" class="pagos_referencia"><?= $Page->referencia->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
        <th class="<?= $Page->monto->headerCellClass() ?>"><span id="elh_pagos_monto" class="pagos_monto"><?= $Page->monto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->comprobante_pago->Visible) { // comprobante_pago ?>
        <th class="<?= $Page->comprobante_pago->headerCellClass() ?>"><span id="elh_pagos_comprobante_pago" class="pagos_comprobante_pago"><?= $Page->comprobante_pago->caption() ?></span></th>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <th class="<?= $Page->comprobante->headerCellClass() ?>"><span id="elh_pagos_comprobante" class="pagos_comprobante"><?= $Page->comprobante->caption() ?></span></th>
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
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
        <td <?= $Page->tipo_pago->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_tipo_pago" class="pagos_tipo_pago">
<span<?= $Page->tipo_pago->viewAttributes() ?>>
<?= $Page->tipo_pago->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_fecha" class="pagos_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <td <?= $Page->referencia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_referencia" class="pagos_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
        <td <?= $Page->monto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_monto" class="pagos_monto">
<span<?= $Page->monto->viewAttributes() ?>>
<?= $Page->monto->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->comprobante_pago->Visible) { // comprobante_pago ?>
        <td <?= $Page->comprobante_pago->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_comprobante_pago" class="pagos_comprobante_pago">
<span>
<?= GetFileViewTag($Page->comprobante_pago, $Page->comprobante_pago->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <td <?= $Page->comprobante->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_comprobante" class="pagos_comprobante">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?= $Page->comprobante->getViewValue() ?></span>
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
