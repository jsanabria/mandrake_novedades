<?php

namespace PHPMaker2021\mandrake;

// Page object
$AlicuotaDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var falicuotadelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    falicuotadelete = currentForm = new ew.Form("falicuotadelete", "delete");
    loadjs.done("falicuotadelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.alicuota) ew.vars.tables.alicuota = <?= JsonEncode(GetClientVar("tables", "alicuota")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="falicuotadelete" id="falicuotadelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="alicuota">
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
<?php if ($Page->codigo->Visible) { // codigo ?>
        <th class="<?= $Page->codigo->headerCellClass() ?>"><span id="elh_alicuota_codigo" class="alicuota_codigo"><?= $Page->codigo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th class="<?= $Page->nombre->headerCellClass() ?>"><span id="elh_alicuota_nombre" class="alicuota_nombre"><?= $Page->nombre->caption() ?></span></th>
<?php } ?>
<?php if ($Page->alicuota->Visible) { // alicuota ?>
        <th class="<?= $Page->alicuota->headerCellClass() ?>"><span id="elh_alicuota_alicuota" class="alicuota_alicuota"><?= $Page->alicuota->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_alicuota_fecha" class="alicuota_fecha"><?= $Page->fecha->caption() ?></span></th>
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
<?php if ($Page->codigo->Visible) { // codigo ?>
        <td <?= $Page->codigo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_alicuota_codigo" class="alicuota_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <td <?= $Page->nombre->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_alicuota_nombre" class="alicuota_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->alicuota->Visible) { // alicuota ?>
        <td <?= $Page->alicuota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_alicuota_alicuota" class="alicuota_alicuota">
<span<?= $Page->alicuota->viewAttributes() ?>>
<?= $Page->alicuota->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_alicuota_fecha" class="alicuota_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
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
