<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContPlanctaDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_planctadelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fcont_planctadelete = currentForm = new ew.Form("fcont_planctadelete", "delete");
    loadjs.done("fcont_planctadelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.cont_plancta) ew.vars.tables.cont_plancta = <?= JsonEncode(GetClientVar("tables", "cont_plancta")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcont_planctadelete" id="fcont_planctadelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_plancta">
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
<?php if ($Page->clase->Visible) { // clase ?>
        <th class="<?= $Page->clase->headerCellClass() ?>"><span id="elh_cont_plancta_clase" class="cont_plancta_clase"><?= $Page->clase->caption() ?></span></th>
<?php } ?>
<?php if ($Page->grupo->Visible) { // grupo ?>
        <th class="<?= $Page->grupo->headerCellClass() ?>"><span id="elh_cont_plancta_grupo" class="cont_plancta_grupo"><?= $Page->grupo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><span id="elh_cont_plancta_cuenta" class="cont_plancta_cuenta"><?= $Page->cuenta->caption() ?></span></th>
<?php } ?>
<?php if ($Page->subcuenta->Visible) { // subcuenta ?>
        <th class="<?= $Page->subcuenta->headerCellClass() ?>"><span id="elh_cont_plancta_subcuenta" class="cont_plancta_subcuenta"><?= $Page->subcuenta->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <th class="<?= $Page->descripcion->headerCellClass() ?>"><span id="elh_cont_plancta_descripcion" class="cont_plancta_descripcion"><?= $Page->descripcion->caption() ?></span></th>
<?php } ?>
<?php if ($Page->clasificacion->Visible) { // clasificacion ?>
        <th class="<?= $Page->clasificacion->headerCellClass() ?>"><span id="elh_cont_plancta_clasificacion" class="cont_plancta_clasificacion"><?= $Page->clasificacion->caption() ?></span></th>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <th class="<?= $Page->moneda->headerCellClass() ?>"><span id="elh_cont_plancta_moneda" class="cont_plancta_moneda"><?= $Page->moneda->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activa->Visible) { // activa ?>
        <th class="<?= $Page->activa->headerCellClass() ?>"><span id="elh_cont_plancta_activa" class="cont_plancta_activa"><?= $Page->activa->caption() ?></span></th>
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
<?php if ($Page->clase->Visible) { // clase ?>
        <td <?= $Page->clase->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_plancta_clase" class="cont_plancta_clase">
<span<?= $Page->clase->viewAttributes() ?>>
<?= $Page->clase->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->grupo->Visible) { // grupo ?>
        <td <?= $Page->grupo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_plancta_grupo" class="cont_plancta_grupo">
<span<?= $Page->grupo->viewAttributes() ?>>
<?= $Page->grupo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <td <?= $Page->cuenta->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_plancta_cuenta" class="cont_plancta_cuenta">
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->subcuenta->Visible) { // subcuenta ?>
        <td <?= $Page->subcuenta->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_plancta_subcuenta" class="cont_plancta_subcuenta">
<span<?= $Page->subcuenta->viewAttributes() ?>>
<?= $Page->subcuenta->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <td <?= $Page->descripcion->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_plancta_descripcion" class="cont_plancta_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->clasificacion->Visible) { // clasificacion ?>
        <td <?= $Page->clasificacion->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_plancta_clasificacion" class="cont_plancta_clasificacion">
<span<?= $Page->clasificacion->viewAttributes() ?>>
<?= $Page->clasificacion->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <td <?= $Page->moneda->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_plancta_moneda" class="cont_plancta_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->activa->Visible) { // activa ?>
        <td <?= $Page->activa->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_plancta_activa" class="cont_plancta_activa">
<span<?= $Page->activa->viewAttributes() ?>>
<?= $Page->activa->getViewValue() ?></span>
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
