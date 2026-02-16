<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContAsientoDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_asientodelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fcont_asientodelete = currentForm = new ew.Form("fcont_asientodelete", "delete");
    loadjs.done("fcont_asientodelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.cont_asiento) ew.vars.tables.cont_asiento = <?= JsonEncode(GetClientVar("tables", "cont_asiento")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcont_asientodelete" id="fcont_asientodelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_asiento">
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
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><span id="elh_cont_asiento_cuenta" class="cont_asiento_cuenta"><?= $Page->cuenta->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
        <th class="<?= $Page->nota->headerCellClass() ?>"><span id="elh_cont_asiento_nota" class="cont_asiento_nota"><?= $Page->nota->caption() ?></span></th>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <th class="<?= $Page->referencia->headerCellClass() ?>"><span id="elh_cont_asiento_referencia" class="cont_asiento_referencia"><?= $Page->referencia->caption() ?></span></th>
<?php } ?>
<?php if ($Page->debe->Visible) { // debe ?>
        <th class="<?= $Page->debe->headerCellClass() ?>"><span id="elh_cont_asiento_debe" class="cont_asiento_debe"><?= $Page->debe->caption() ?></span></th>
<?php } ?>
<?php if ($Page->haber->Visible) { // haber ?>
        <th class="<?= $Page->haber->headerCellClass() ?>"><span id="elh_cont_asiento_haber" class="cont_asiento_haber"><?= $Page->haber->caption() ?></span></th>
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
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <td <?= $Page->cuenta->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_asiento_cuenta" class="cont_asiento_cuenta">
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
        <td <?= $Page->nota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_asiento_nota" class="cont_asiento_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <td <?= $Page->referencia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_asiento_referencia" class="cont_asiento_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->debe->Visible) { // debe ?>
        <td <?= $Page->debe->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_asiento_debe" class="cont_asiento_debe">
<span<?= $Page->debe->viewAttributes() ?>>
<?= $Page->debe->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->haber->Visible) { // haber ?>
        <td <?= $Page->haber->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_asiento_haber" class="cont_asiento_haber">
<span<?= $Page->haber->viewAttributes() ?>>
<?= $Page->haber->getViewValue() ?></span>
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
