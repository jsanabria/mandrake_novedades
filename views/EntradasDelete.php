<?php

namespace PHPMaker2021\mandrake;

// Page object
$EntradasDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fentradasdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fentradasdelete = currentForm = new ew.Form("fentradasdelete", "delete");
    loadjs.done("fentradasdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.entradas) ew.vars.tables.entradas = <?= JsonEncode(GetClientVar("tables", "entradas")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fentradasdelete" id="fentradasdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_entradas_id" class="entradas_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><span id="elh_entradas_tipo_documento" class="entradas_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><span id="elh_entradas_nro_documento" class="entradas_nro_documento"><?= $Page->nro_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_entradas_fecha" class="entradas_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <th class="<?= $Page->proveedor->headerCellClass() ?>"><span id="elh_entradas_proveedor" class="entradas_proveedor"><?= $Page->proveedor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <th class="<?= $Page->monto_total->headerCellClass() ?>"><span id="elh_entradas_monto_total" class="entradas_monto_total"><?= $Page->monto_total->caption() ?></span></th>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
        <th class="<?= $Page->alicuota_iva->headerCellClass() ?>"><span id="elh_entradas_alicuota_iva" class="entradas_alicuota_iva"><?= $Page->alicuota_iva->caption() ?></span></th>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
        <th class="<?= $Page->iva->headerCellClass() ?>"><span id="elh_entradas_iva" class="entradas_iva"><?= $Page->iva->caption() ?></span></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th class="<?= $Page->total->headerCellClass() ?>"><span id="elh_entradas_total" class="entradas_total"><?= $Page->total->caption() ?></span></th>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
        <th class="<?= $Page->documento->headerCellClass() ?>"><span id="elh_entradas_documento" class="entradas_documento"><?= $Page->documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <th class="<?= $Page->estatus->headerCellClass() ?>"><span id="elh_entradas_estatus" class="entradas_estatus"><?= $Page->estatus->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <th class="<?= $Page->_username->headerCellClass() ?>"><span id="elh_entradas__username" class="entradas__username"><?= $Page->_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ref_islr->Visible) { // ref_islr ?>
        <th class="<?= $Page->ref_islr->headerCellClass() ?>"><span id="elh_entradas_ref_islr" class="entradas_ref_islr"><?= $Page->ref_islr->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ref_municipal->Visible) { // ref_municipal ?>
        <th class="<?= $Page->ref_municipal->headerCellClass() ?>"><span id="elh_entradas_ref_municipal" class="entradas_ref_municipal"><?= $Page->ref_municipal->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th class="<?= $Page->cliente->headerCellClass() ?>"><span id="elh_entradas_cliente" class="entradas_cliente"><?= $Page->cliente->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <th class="<?= $Page->descuento->headerCellClass() ?>"><span id="elh_entradas_descuento" class="entradas_descuento"><?= $Page->descuento->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_entradas_id" class="entradas_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <td <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_tipo_documento" class="entradas_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_nro_documento" class="entradas_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_fecha" class="entradas_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <td <?= $Page->proveedor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_proveedor" class="entradas_proveedor">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <td <?= $Page->monto_total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_monto_total" class="entradas_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?= $Page->monto_total->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
        <td <?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_alicuota_iva" class="entradas_alicuota_iva">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<?= $Page->alicuota_iva->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
        <td <?= $Page->iva->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_iva" class="entradas_iva">
<span<?= $Page->iva->viewAttributes() ?>>
<?= $Page->iva->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <td <?= $Page->total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_total" class="entradas_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
        <td <?= $Page->documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_documento" class="entradas_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <td <?= $Page->estatus->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_estatus" class="entradas_estatus">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <td <?= $Page->_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas__username" class="entradas__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ref_islr->Visible) { // ref_islr ?>
        <td <?= $Page->ref_islr->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_ref_islr" class="entradas_ref_islr">
<span<?= $Page->ref_islr->viewAttributes() ?>>
<?php if (!EmptyString($Page->ref_islr->getViewValue()) && $Page->ref_islr->linkAttributes() != "") { ?>
<a<?= $Page->ref_islr->linkAttributes() ?>><?= $Page->ref_islr->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->ref_islr->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->ref_municipal->Visible) { // ref_municipal ?>
        <td <?= $Page->ref_municipal->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_ref_municipal" class="entradas_ref_municipal">
<span<?= $Page->ref_municipal->viewAttributes() ?>>
<?= $Page->ref_municipal->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <td <?= $Page->cliente->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_cliente" class="entradas_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <td <?= $Page->descuento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_descuento" class="entradas_descuento">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
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
