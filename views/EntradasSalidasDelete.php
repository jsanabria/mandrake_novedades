<?php

namespace PHPMaker2021\mandrake;

// Page object
$EntradasSalidasDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fentradas_salidasdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fentradas_salidasdelete = currentForm = new ew.Form("fentradas_salidasdelete", "delete");
    loadjs.done("fentradas_salidasdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.entradas_salidas) ew.vars.tables.entradas_salidas = <?= JsonEncode(GetClientVar("tables", "entradas_salidas")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fentradas_salidasdelete" id="fentradas_salidasdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas_salidas">
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
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><span id="elh_entradas_salidas_articulo" class="entradas_salidas_articulo"><?= $Page->articulo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <th class="<?= $Page->cantidad_articulo->headerCellClass() ?>"><span id="elh_entradas_salidas_cantidad_articulo" class="entradas_salidas_cantidad_articulo"><?= $Page->cantidad_articulo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <th class="<?= $Page->precio_unidad_sin_desc->headerCellClass() ?>"><span id="elh_entradas_salidas_precio_unidad_sin_desc" class="entradas_salidas_precio_unidad_sin_desc"><?= $Page->precio_unidad_sin_desc->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <th class="<?= $Page->descuento->headerCellClass() ?>"><span id="elh_entradas_salidas_descuento" class="entradas_salidas_descuento"><?= $Page->descuento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
        <th class="<?= $Page->costo_unidad->headerCellClass() ?>"><span id="elh_entradas_salidas_costo_unidad" class="entradas_salidas_costo_unidad"><?= $Page->costo_unidad->caption() ?></span></th>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
        <th class="<?= $Page->costo->headerCellClass() ?>"><span id="elh_entradas_salidas_costo" class="entradas_salidas_costo"><?= $Page->costo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
        <th class="<?= $Page->precio_unidad->headerCellClass() ?>"><span id="elh_entradas_salidas_precio_unidad" class="entradas_salidas_precio_unidad"><?= $Page->precio_unidad->caption() ?></span></th>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <th class="<?= $Page->precio->headerCellClass() ?>"><span id="elh_entradas_salidas_precio" class="entradas_salidas_precio"><?= $Page->precio->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
        <th class="<?= $Page->lote->headerCellClass() ?>"><span id="elh_entradas_salidas_lote" class="entradas_salidas_lote"><?= $Page->lote->caption() ?></span></th>
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
<?php if ($Page->articulo->Visible) { // articulo ?>
        <td <?= $Page->articulo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_articulo" class="entradas_salidas_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td <?= $Page->cantidad_articulo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_cantidad_articulo" class="entradas_salidas_cantidad_articulo">
<span<?= $Page->cantidad_articulo->viewAttributes() ?>>
<?= $Page->cantidad_articulo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <td <?= $Page->precio_unidad_sin_desc->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio_unidad_sin_desc" class="entradas_salidas_precio_unidad_sin_desc">
<span<?= $Page->precio_unidad_sin_desc->viewAttributes() ?>>
<?= $Page->precio_unidad_sin_desc->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <td <?= $Page->descuento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_descuento" class="entradas_salidas_descuento">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
        <td <?= $Page->costo_unidad->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_costo_unidad" class="entradas_salidas_costo_unidad">
<span<?= $Page->costo_unidad->viewAttributes() ?>>
<?= $Page->costo_unidad->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
        <td <?= $Page->costo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_costo" class="entradas_salidas_costo">
<span<?= $Page->costo->viewAttributes() ?>>
<?= $Page->costo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
        <td <?= $Page->precio_unidad->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio_unidad" class="entradas_salidas_precio_unidad">
<span<?= $Page->precio_unidad->viewAttributes() ?>>
<?= $Page->precio_unidad->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <td <?= $Page->precio->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio" class="entradas_salidas_precio">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
        <td <?= $Page->lote->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_lote" class="entradas_salidas_lote">
<span<?= $Page->lote->viewAttributes() ?>>
<?= $Page->lote->getViewValue() ?></span>
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
