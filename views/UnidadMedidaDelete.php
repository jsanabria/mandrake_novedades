<?php

namespace PHPMaker2021\mandrake;

// Page object
$UnidadMedidaDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var funidad_medidadelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    funidad_medidadelete = currentForm = new ew.Form("funidad_medidadelete", "delete");
    loadjs.done("funidad_medidadelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.unidad_medida) ew.vars.tables.unidad_medida = <?= JsonEncode(GetClientVar("tables", "unidad_medida")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="funidad_medidadelete" id="funidad_medidadelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="unidad_medida">
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
        <th class="<?= $Page->codigo->headerCellClass() ?>"><span id="elh_unidad_medida_codigo" class="unidad_medida_codigo"><?= $Page->codigo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <th class="<?= $Page->descripcion->headerCellClass() ?>"><span id="elh_unidad_medida_descripcion" class="unidad_medida_descripcion"><?= $Page->descripcion->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
        <th class="<?= $Page->cantidad->headerCellClass() ?>"><span id="elh_unidad_medida_cantidad" class="unidad_medida_cantidad"><?= $Page->cantidad->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_unidad_medida_codigo" class="unidad_medida_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <td <?= $Page->descripcion->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_unidad_medida_descripcion" class="unidad_medida_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
        <td <?= $Page->cantidad->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_unidad_medida_cantidad" class="unidad_medida_cantidad">
<span<?= $Page->cantidad->viewAttributes() ?>>
<?= $Page->cantidad->getViewValue() ?></span>
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
