<?php

namespace PHPMaker2021\mandrake;

// Page object
$PuntosDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpuntosdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fpuntosdelete = currentForm = new ew.Form("fpuntosdelete", "delete");
    loadjs.done("fpuntosdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.puntos) ew.vars.tables.puntos = <?= JsonEncode(GetClientVar("tables", "puntos")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpuntosdelete" id="fpuntosdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="puntos">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_puntos_id" class="puntos_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th class="<?= $Page->cliente->headerCellClass() ?>"><span id="elh_puntos_cliente" class="puntos_cliente"><?= $Page->cliente->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_puntos_fecha" class="puntos_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <th class="<?= $Page->tipo->headerCellClass() ?>"><span id="elh_puntos_tipo" class="puntos_tipo"><?= $Page->tipo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><span id="elh_puntos_nro_documento" class="puntos_nro_documento"><?= $Page->nro_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <th class="<?= $Page->referencia->headerCellClass() ?>"><span id="elh_puntos_referencia" class="puntos_referencia"><?= $Page->referencia->caption() ?></span></th>
<?php } ?>
<?php if ($Page->puntos->Visible) { // puntos ?>
        <th class="<?= $Page->puntos->headerCellClass() ?>"><span id="elh_puntos_puntos" class="puntos_puntos"><?= $Page->puntos->caption() ?></span></th>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
        <th class="<?= $Page->saldo->headerCellClass() ?>"><span id="elh_puntos_saldo" class="puntos_saldo"><?= $Page->saldo->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_puntos_id" class="puntos_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <td <?= $Page->cliente->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_cliente" class="puntos_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_fecha" class="puntos_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <td <?= $Page->tipo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_tipo" class="puntos_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_nro_documento" class="puntos_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <td <?= $Page->referencia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_referencia" class="puntos_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->puntos->Visible) { // puntos ?>
        <td <?= $Page->puntos->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_puntos" class="puntos_puntos">
<span<?= $Page->puntos->viewAttributes() ?>>
<?= $Page->puntos->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
        <td <?= $Page->saldo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_saldo" class="puntos_saldo">
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
