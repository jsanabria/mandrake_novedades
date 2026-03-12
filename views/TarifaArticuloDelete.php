<?php

namespace PHPMaker2021\mandrake;

// Page object
$TarifaArticuloDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var ftarifa_articulodelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    ftarifa_articulodelete = currentForm = new ew.Form("ftarifa_articulodelete", "delete");
    loadjs.done("ftarifa_articulodelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.tarifa_articulo) ew.vars.tables.tarifa_articulo = <?= JsonEncode(GetClientVar("tables", "tarifa_articulo")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="ftarifa_articulodelete" id="ftarifa_articulodelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tarifa_articulo">
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
<?php if ($Page->tarifa->Visible) { // tarifa ?>
        <th class="<?= $Page->tarifa->headerCellClass() ?>"><span id="elh_tarifa_articulo_tarifa" class="tarifa_articulo_tarifa"><?= $Page->tarifa->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th class="<?= $Page->fabricante->headerCellClass() ?>"><span id="elh_tarifa_articulo_fabricante" class="tarifa_articulo_fabricante"><?= $Page->fabricante->caption() ?></span></th>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><span id="elh_tarifa_articulo_articulo" class="tarifa_articulo_articulo"><?= $Page->articulo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <th class="<?= $Page->precio->headerCellClass() ?>"><span id="elh_tarifa_articulo_precio" class="tarifa_articulo_precio"><?= $Page->precio->caption() ?></span></th>
<?php } ?>
<?php if ($Page->precio2->Visible) { // precio2 ?>
        <th class="<?= $Page->precio2->headerCellClass() ?>"><span id="elh_tarifa_articulo_precio2" class="tarifa_articulo_precio2"><?= $Page->precio2->caption() ?></span></th>
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
<?php if ($Page->tarifa->Visible) { // tarifa ?>
        <td <?= $Page->tarifa->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_tarifa" class="tarifa_articulo_tarifa">
<span<?= $Page->tarifa->viewAttributes() ?>>
<?= $Page->tarifa->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td <?= $Page->fabricante->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_fabricante" class="tarifa_articulo_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <td <?= $Page->articulo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_articulo" class="tarifa_articulo_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <td <?= $Page->precio->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_precio" class="tarifa_articulo_precio">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->precio2->Visible) { // precio2 ?>
        <td <?= $Page->precio2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_precio2" class="tarifa_articulo_precio2">
<span<?= $Page->precio2->viewAttributes() ?>>
<?= $Page->precio2->getViewValue() ?></span>
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
