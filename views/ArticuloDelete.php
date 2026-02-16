<?php

namespace PHPMaker2021\mandrake;

// Page object
$ArticuloDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var farticulodelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    farticulodelete = currentForm = new ew.Form("farticulodelete", "delete");
    loadjs.done("farticulodelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.articulo) ew.vars.tables.articulo = <?= JsonEncode(GetClientVar("tables", "articulo")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="farticulodelete" id="farticulodelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="articulo">
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
<?php if ($Page->codigo_ims->Visible) { // codigo_ims ?>
        <th class="<?= $Page->codigo_ims->headerCellClass() ?>"><span id="elh_articulo_codigo_ims" class="articulo_codigo_ims"><?= $Page->codigo_ims->caption() ?></span></th>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
        <th class="<?= $Page->codigo->headerCellClass() ?>"><span id="elh_articulo_codigo" class="articulo_codigo"><?= $Page->codigo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
        <th class="<?= $Page->principio_activo->headerCellClass() ?>"><span id="elh_articulo_principio_activo" class="articulo_principio_activo"><?= $Page->principio_activo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th class="<?= $Page->fabricante->headerCellClass() ?>"><span id="elh_articulo_fabricante" class="articulo_fabricante"><?= $Page->fabricante->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <th class="<?= $Page->cantidad_en_mano->headerCellClass() ?>"><span id="elh_articulo_cantidad_en_mano" class="articulo_cantidad_en_mano"><?= $Page->cantidad_en_mano->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_en_pedido->Visible) { // cantidad_en_pedido ?>
        <th class="<?= $Page->cantidad_en_pedido->headerCellClass() ?>"><span id="elh_articulo_cantidad_en_pedido" class="articulo_cantidad_en_pedido"><?= $Page->cantidad_en_pedido->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_en_transito->Visible) { // cantidad_en_transito ?>
        <th class="<?= $Page->cantidad_en_transito->headerCellClass() ?>"><span id="elh_articulo_cantidad_en_transito" class="articulo_cantidad_en_transito"><?= $Page->cantidad_en_transito->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <th class="<?= $Page->descuento->headerCellClass() ?>"><span id="elh_articulo_descuento" class="articulo_descuento"><?= $Page->descuento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><span id="elh_articulo_activo" class="articulo_activo"><?= $Page->activo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->puntos_ventas->Visible) { // puntos_ventas ?>
        <th class="<?= $Page->puntos_ventas->headerCellClass() ?>"><span id="elh_articulo_puntos_ventas" class="articulo_puntos_ventas"><?= $Page->puntos_ventas->caption() ?></span></th>
<?php } ?>
<?php if ($Page->puntos_premio->Visible) { // puntos_premio ?>
        <th class="<?= $Page->puntos_premio->headerCellClass() ?>"><span id="elh_articulo_puntos_premio" class="articulo_puntos_premio"><?= $Page->puntos_premio->caption() ?></span></th>
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
<?php if ($Page->codigo_ims->Visible) { // codigo_ims ?>
        <td <?= $Page->codigo_ims->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_codigo_ims" class="articulo_codigo_ims">
<span<?= $Page->codigo_ims->viewAttributes() ?>>
<?= $Page->codigo_ims->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
        <td <?= $Page->codigo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_codigo" class="articulo_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
        <td <?= $Page->principio_activo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_principio_activo" class="articulo_principio_activo">
<span<?= $Page->principio_activo->viewAttributes() ?>>
<?= $Page->principio_activo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td <?= $Page->fabricante->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_fabricante" class="articulo_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <td <?= $Page->cantidad_en_mano->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_cantidad_en_mano" class="articulo_cantidad_en_mano">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<?= $Page->cantidad_en_mano->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_en_pedido->Visible) { // cantidad_en_pedido ?>
        <td <?= $Page->cantidad_en_pedido->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_cantidad_en_pedido" class="articulo_cantidad_en_pedido">
<span<?= $Page->cantidad_en_pedido->viewAttributes() ?>>
<?= $Page->cantidad_en_pedido->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_en_transito->Visible) { // cantidad_en_transito ?>
        <td <?= $Page->cantidad_en_transito->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_cantidad_en_transito" class="articulo_cantidad_en_transito">
<span<?= $Page->cantidad_en_transito->viewAttributes() ?>>
<?= $Page->cantidad_en_transito->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <td <?= $Page->descuento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_descuento" class="articulo_descuento">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <td <?= $Page->activo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_activo" class="articulo_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->puntos_ventas->Visible) { // puntos_ventas ?>
        <td <?= $Page->puntos_ventas->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_puntos_ventas" class="articulo_puntos_ventas">
<span<?= $Page->puntos_ventas->viewAttributes() ?>>
<?= $Page->puntos_ventas->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->puntos_premio->Visible) { // puntos_premio ?>
        <td <?= $Page->puntos_premio->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_puntos_premio" class="articulo_puntos_premio">
<span<?= $Page->puntos_premio->viewAttributes() ?>>
<?= $Page->puntos_premio->getViewValue() ?></span>
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
