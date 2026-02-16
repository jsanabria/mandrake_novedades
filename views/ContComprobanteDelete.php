<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContComprobanteDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_comprobantedelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fcont_comprobantedelete = currentForm = new ew.Form("fcont_comprobantedelete", "delete");
    loadjs.done("fcont_comprobantedelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.cont_comprobante) ew.vars.tables.cont_comprobante = <?= JsonEncode(GetClientVar("tables", "cont_comprobante")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcont_comprobantedelete" id="fcont_comprobantedelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_comprobante">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_cont_comprobante_id" class="cont_comprobante_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <th class="<?= $Page->tipo->headerCellClass() ?>"><span id="elh_cont_comprobante_tipo" class="cont_comprobante_tipo"><?= $Page->tipo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_cont_comprobante_fecha" class="cont_comprobante_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <th class="<?= $Page->descripcion->headerCellClass() ?>"><span id="elh_cont_comprobante_descripcion" class="cont_comprobante_descripcion"><?= $Page->descripcion->caption() ?></span></th>
<?php } ?>
<?php if ($Page->contabilizacion->Visible) { // contabilizacion ?>
        <th class="<?= $Page->contabilizacion->headerCellClass() ?>"><span id="elh_cont_comprobante_contabilizacion" class="cont_comprobante_contabilizacion"><?= $Page->contabilizacion->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_cont_comprobante_id" class="cont_comprobante_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <td <?= $Page->tipo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_comprobante_tipo" class="cont_comprobante_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_comprobante_fecha" class="cont_comprobante_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <td <?= $Page->descripcion->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_comprobante_descripcion" class="cont_comprobante_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->contabilizacion->Visible) { // contabilizacion ?>
        <td <?= $Page->contabilizacion->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_comprobante_contabilizacion" class="cont_comprobante_contabilizacion">
<span<?= $Page->contabilizacion->viewAttributes() ?>>
<?= $Page->contabilizacion->getViewValue() ?></span>
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
