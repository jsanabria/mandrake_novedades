<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcobros_clientedelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fcobros_clientedelete = currentForm = new ew.Form("fcobros_clientedelete", "delete");
    loadjs.done("fcobros_clientedelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.cobros_cliente) ew.vars.tables.cobros_cliente = <?= JsonEncode(GetClientVar("tables", "cobros_cliente")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcobros_clientedelete" id="fcobros_clientedelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_cobros_cliente_id" class="cobros_cliente_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th class="<?= $Page->cliente->headerCellClass() ?>"><span id="elh_cobros_cliente_cliente" class="cobros_cliente_cliente"><?= $Page->cliente->caption() ?></span></th>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
        <th class="<?= $Page->id_documento->headerCellClass() ?>"><span id="elh_cobros_cliente_id_documento" class="cobros_cliente_id_documento"><?= $Page->id_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_cobros_cliente_fecha" class="cobros_cliente_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <th class="<?= $Page->moneda->headerCellClass() ?>"><span id="elh_cobros_cliente_moneda" class="cobros_cliente_moneda"><?= $Page->moneda->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pago->Visible) { // pago ?>
        <th class="<?= $Page->pago->headerCellClass() ?>"><span id="elh_cobros_cliente_pago" class="cobros_cliente_pago"><?= $Page->pago->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_cobros_cliente_id" class="cobros_cliente_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <td <?= $Page->cliente->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_cliente" class="cobros_cliente_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
        <td <?= $Page->id_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_id_documento" class="cobros_cliente_id_documento">
<span<?= $Page->id_documento->viewAttributes() ?>>
<?= $Page->id_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_fecha" class="cobros_cliente_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <td <?= $Page->moneda->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_moneda" class="cobros_cliente_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pago->Visible) { // pago ?>
        <td <?= $Page->pago->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_pago" class="cobros_cliente_pago">
<span<?= $Page->pago->viewAttributes() ?>>
<?= $Page->pago->getViewValue() ?></span>
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
