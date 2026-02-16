<?php

namespace PHPMaker2021\mandrake;

// Page object
$PedidoOnlineDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpedido_onlinedelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fpedido_onlinedelete = currentForm = new ew.Form("fpedido_onlinedelete", "delete");
    loadjs.done("fpedido_onlinedelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.pedido_online) ew.vars.tables.pedido_online = <?= JsonEncode(GetClientVar("tables", "pedido_online")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpedido_onlinedelete" id="fpedido_onlinedelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pedido_online">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_pedido_online_id" class="pedido_online_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><span id="elh_pedido_online_tipo_documento" class="pedido_online_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
        <th class="<?= $Page->asesor->headerCellClass() ?>"><span id="elh_pedido_online_asesor" class="pedido_online_asesor"><?= $Page->asesor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th class="<?= $Page->cliente->headerCellClass() ?>"><span id="elh_pedido_online_cliente" class="pedido_online_cliente"><?= $Page->cliente->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_pedido_online_fecha" class="pedido_online_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <th class="<?= $Page->monto_total->headerCellClass() ?>"><span id="elh_pedido_online_monto_total" class="pedido_online_monto_total"><?= $Page->monto_total->caption() ?></span></th>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
        <th class="<?= $Page->iva->headerCellClass() ?>"><span id="elh_pedido_online_iva" class="pedido_online_iva"><?= $Page->iva->caption() ?></span></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th class="<?= $Page->total->headerCellClass() ?>"><span id="elh_pedido_online_total" class="pedido_online_total"><?= $Page->total->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
        <th class="<?= $Page->nota->headerCellClass() ?>"><span id="elh_pedido_online_nota" class="pedido_online_nota"><?= $Page->nota->caption() ?></span></th>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <th class="<?= $Page->estatus->headerCellClass() ?>"><span id="elh_pedido_online_estatus" class="pedido_online_estatus"><?= $Page->estatus->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_pedido_online_id" class="pedido_online_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <td <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pedido_online_tipo_documento" class="pedido_online_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
        <td <?= $Page->asesor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pedido_online_asesor" class="pedido_online_asesor">
<span<?= $Page->asesor->viewAttributes() ?>>
<?= $Page->asesor->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <td <?= $Page->cliente->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pedido_online_cliente" class="pedido_online_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pedido_online_fecha" class="pedido_online_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <td <?= $Page->monto_total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pedido_online_monto_total" class="pedido_online_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?= $Page->monto_total->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
        <td <?= $Page->iva->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pedido_online_iva" class="pedido_online_iva">
<span<?= $Page->iva->viewAttributes() ?>>
<?= $Page->iva->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <td <?= $Page->total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pedido_online_total" class="pedido_online_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
        <td <?= $Page->nota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pedido_online_nota" class="pedido_online_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <td <?= $Page->estatus->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pedido_online_estatus" class="pedido_online_estatus">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
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
