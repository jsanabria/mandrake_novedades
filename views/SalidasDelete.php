<?php

namespace PHPMaker2021\mandrake;

// Page object
$SalidasDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fsalidasdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fsalidasdelete = currentForm = new ew.Form("fsalidasdelete", "delete");
    loadjs.done("fsalidasdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.salidas) ew.vars.tables.salidas = <?= JsonEncode(GetClientVar("tables", "salidas")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fsalidasdelete" id="fsalidasdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="salidas">
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
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><span id="elh_salidas_tipo_documento" class="salidas_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><span id="elh_salidas_nro_documento" class="salidas_nro_documento"><?= $Page->nro_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_salidas_fecha" class="salidas_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th class="<?= $Page->cliente->headerCellClass() ?>"><span id="elh_salidas_cliente" class="salidas_cliente"><?= $Page->cliente->caption() ?></span></th>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
        <th class="<?= $Page->documento->headerCellClass() ?>"><span id="elh_salidas_documento" class="salidas_documento"><?= $Page->documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
        <th class="<?= $Page->doc_afectado->headerCellClass() ?>"><span id="elh_salidas_doc_afectado" class="salidas_doc_afectado"><?= $Page->doc_afectado->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <th class="<?= $Page->monto_total->headerCellClass() ?>"><span id="elh_salidas_monto_total" class="salidas_monto_total"><?= $Page->monto_total->caption() ?></span></th>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
        <th class="<?= $Page->alicuota_iva->headerCellClass() ?>"><span id="elh_salidas_alicuota_iva" class="salidas_alicuota_iva"><?= $Page->alicuota_iva->caption() ?></span></th>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
        <th class="<?= $Page->iva->headerCellClass() ?>"><span id="elh_salidas_iva" class="salidas_iva"><?= $Page->iva->caption() ?></span></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th class="<?= $Page->total->headerCellClass() ?>"><span id="elh_salidas_total" class="salidas_total"><?= $Page->total->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
        <th class="<?= $Page->lista_pedido->headerCellClass() ?>"><span id="elh_salidas_lista_pedido" class="salidas_lista_pedido"><?= $Page->lista_pedido->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <th class="<?= $Page->_username->headerCellClass() ?>"><span id="elh_salidas__username" class="salidas__username"><?= $Page->_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <th class="<?= $Page->estatus->headerCellClass() ?>"><span id="elh_salidas_estatus" class="salidas_estatus"><?= $Page->estatus->caption() ?></span></th>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
        <th class="<?= $Page->asesor->headerCellClass() ?>"><span id="elh_salidas_asesor" class="salidas_asesor"><?= $Page->asesor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->unidades->Visible) { // unidades ?>
        <th class="<?= $Page->unidades->headerCellClass() ?>"><span id="elh_salidas_unidades" class="salidas_unidades"><?= $Page->unidades->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
        <th class="<?= $Page->nro_despacho->headerCellClass() ?>"><span id="elh_salidas_nro_despacho" class="salidas_nro_despacho"><?= $Page->nro_despacho->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_salidas_tipo_documento" class="salidas_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_nro_documento" class="salidas_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_fecha" class="salidas_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <td <?= $Page->cliente->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_cliente" class="salidas_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
        <td <?= $Page->documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_documento" class="salidas_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
        <td <?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_doc_afectado" class="salidas_doc_afectado">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <td <?= $Page->monto_total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_monto_total" class="salidas_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?= $Page->monto_total->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
        <td <?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_alicuota_iva" class="salidas_alicuota_iva">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<?= $Page->alicuota_iva->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
        <td <?= $Page->iva->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_iva" class="salidas_iva">
<span<?= $Page->iva->viewAttributes() ?>>
<?= $Page->iva->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <td <?= $Page->total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_total" class="salidas_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
        <td <?= $Page->lista_pedido->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_lista_pedido" class="salidas_lista_pedido">
<span<?= $Page->lista_pedido->viewAttributes() ?>>
<?= $Page->lista_pedido->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <td <?= $Page->_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas__username" class="salidas__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <td <?= $Page->estatus->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_estatus" class="salidas_estatus">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
        <td <?= $Page->asesor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_asesor" class="salidas_asesor">
<span<?= $Page->asesor->viewAttributes() ?>>
<?= $Page->asesor->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->unidades->Visible) { // unidades ?>
        <td <?= $Page->unidades->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_unidades" class="salidas_unidades">
<span<?= $Page->unidades->viewAttributes() ?>>
<?= $Page->unidades->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
        <td <?= $Page->nro_despacho->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_nro_despacho" class="salidas_nro_despacho">
<span<?= $Page->nro_despacho->viewAttributes() ?>>
<?= $Page->nro_despacho->getViewValue() ?></span>
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
