<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContLotesPagosDetalleDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_lotes_pagos_detalledelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fcont_lotes_pagos_detalledelete = currentForm = new ew.Form("fcont_lotes_pagos_detalledelete", "delete");
    loadjs.done("fcont_lotes_pagos_detalledelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.cont_lotes_pagos_detalle) ew.vars.tables.cont_lotes_pagos_detalle = <?= JsonEncode(GetClientVar("tables", "cont_lotes_pagos_detalle")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcont_lotes_pagos_detalledelete" id="fcont_lotes_pagos_detalledelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_lotes_pagos_detalle">
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
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <th class="<?= $Page->proveedor->headerCellClass() ?>"><span id="elh_cont_lotes_pagos_detalle_proveedor" class="cont_lotes_pagos_detalle_proveedor"><?= $Page->proveedor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_cont_lotes_pagos_detalle_fecha" class="cont_lotes_pagos_detalle_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tipodoc->Visible) { // tipodoc ?>
        <th class="<?= $Page->tipodoc->headerCellClass() ?>"><span id="elh_cont_lotes_pagos_detalle_tipodoc" class="cont_lotes_pagos_detalle_tipodoc"><?= $Page->tipodoc->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><span id="elh_cont_lotes_pagos_detalle_nro_documento" class="cont_lotes_pagos_detalle_nro_documento"><?= $Page->nro_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto_a_pagar->Visible) { // monto_a_pagar ?>
        <th class="<?= $Page->monto_a_pagar->headerCellClass() ?>"><span id="elh_cont_lotes_pagos_detalle_monto_a_pagar" class="cont_lotes_pagos_detalle_monto_a_pagar"><?= $Page->monto_a_pagar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto_pagado->Visible) { // monto_pagado ?>
        <th class="<?= $Page->monto_pagado->headerCellClass() ?>"><span id="elh_cont_lotes_pagos_detalle_monto_pagado" class="cont_lotes_pagos_detalle_monto_pagado"><?= $Page->monto_pagado->caption() ?></span></th>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
        <th class="<?= $Page->saldo->headerCellClass() ?>"><span id="elh_cont_lotes_pagos_detalle_saldo" class="cont_lotes_pagos_detalle_saldo"><?= $Page->saldo->caption() ?></span></th>
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
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <td <?= $Page->proveedor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_proveedor" class="cont_lotes_pagos_detalle_proveedor">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_fecha" class="cont_lotes_pagos_detalle_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tipodoc->Visible) { // tipodoc ?>
        <td <?= $Page->tipodoc->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_tipodoc" class="cont_lotes_pagos_detalle_tipodoc">
<span<?= $Page->tipodoc->viewAttributes() ?>>
<?= $Page->tipodoc->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_nro_documento" class="cont_lotes_pagos_detalle_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto_a_pagar->Visible) { // monto_a_pagar ?>
        <td <?= $Page->monto_a_pagar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_monto_a_pagar" class="cont_lotes_pagos_detalle_monto_a_pagar">
<span<?= $Page->monto_a_pagar->viewAttributes() ?>>
<?= $Page->monto_a_pagar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto_pagado->Visible) { // monto_pagado ?>
        <td <?= $Page->monto_pagado->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_monto_pagado" class="cont_lotes_pagos_detalle_monto_pagado">
<span<?= $Page->monto_pagado->viewAttributes() ?>>
<?= $Page->monto_pagado->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
        <td <?= $Page->saldo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_saldo" class="cont_lotes_pagos_detalle_saldo">
<span<?= $Page->saldo->viewAttributes() ?>>
<?= $Page->saldo->getViewValue() ?></span>
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
