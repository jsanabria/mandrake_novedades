<?php

namespace PHPMaker2021\mandrake;

// Page object
$TipoDocumentoView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var ftipo_documentoview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    ftipo_documentoview = currentForm = new ew.Form("ftipo_documentoview", "view");
    loadjs.done("ftipo_documentoview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.tipo_documento) ew.vars.tables.tipo_documento = <?= JsonEncode(GetClientVar("tables", "tipo_documento")) ?>;
</script>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="ftipo_documentoview" id="ftipo_documentoview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tipo_documento">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->codigo->Visible) { // codigo ?>
    <tr id="r_codigo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_codigo"><?= $Page->codigo->caption() ?></span></td>
        <td data-name="codigo" <?= $Page->codigo->cellAttributes() ?>>
<span id="el_tipo_documento_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <tr id="r_descripcion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_descripcion"><?= $Page->descripcion->caption() ?></span></td>
        <td data-name="descripcion" <?= $Page->descripcion->cellAttributes() ?>>
<span id="el_tipo_documento_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <tr id="r_tipo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_tipo"><?= $Page->tipo->caption() ?></span></td>
        <td data-name="tipo" <?= $Page->tipo->cellAttributes() ?>>
<span id="el_tipo_documento_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M01->Visible) { // M01 ?>
    <tr id="r_M01">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_M01"><?= $Page->M01->caption() ?></span></td>
        <td data-name="M01" <?= $Page->M01->cellAttributes() ?>>
<span id="el_tipo_documento_M01">
<span<?= $Page->M01->viewAttributes() ?>>
<?= $Page->M01->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M02->Visible) { // M02 ?>
    <tr id="r_M02">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_M02"><?= $Page->M02->caption() ?></span></td>
        <td data-name="M02" <?= $Page->M02->cellAttributes() ?>>
<span id="el_tipo_documento_M02">
<span<?= $Page->M02->viewAttributes() ?>>
<?= $Page->M02->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M03->Visible) { // M03 ?>
    <tr id="r_M03">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_M03"><?= $Page->M03->caption() ?></span></td>
        <td data-name="M03" <?= $Page->M03->cellAttributes() ?>>
<span id="el_tipo_documento_M03">
<span<?= $Page->M03->viewAttributes() ?>>
<?= $Page->M03->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M04->Visible) { // M04 ?>
    <tr id="r_M04">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_M04"><?= $Page->M04->caption() ?></span></td>
        <td data-name="M04" <?= $Page->M04->cellAttributes() ?>>
<span id="el_tipo_documento_M04">
<span<?= $Page->M04->viewAttributes() ?>>
<?= $Page->M04->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M05->Visible) { // M05 ?>
    <tr id="r_M05">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_M05"><?= $Page->M05->caption() ?></span></td>
        <td data-name="M05" <?= $Page->M05->cellAttributes() ?>>
<span id="el_tipo_documento_M05">
<span<?= $Page->M05->viewAttributes() ?>>
<?= $Page->M05->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M06->Visible) { // M06 ?>
    <tr id="r_M06">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_M06"><?= $Page->M06->caption() ?></span></td>
        <td data-name="M06" <?= $Page->M06->cellAttributes() ?>>
<span id="el_tipo_documento_M06">
<span<?= $Page->M06->viewAttributes() ?>>
<?= $Page->M06->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M07->Visible) { // M07 ?>
    <tr id="r_M07">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_M07"><?= $Page->M07->caption() ?></span></td>
        <td data-name="M07" <?= $Page->M07->cellAttributes() ?>>
<span id="el_tipo_documento_M07">
<span<?= $Page->M07->viewAttributes() ?>>
<?= $Page->M07->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M08->Visible) { // M08 ?>
    <tr id="r_M08">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_M08"><?= $Page->M08->caption() ?></span></td>
        <td data-name="M08" <?= $Page->M08->cellAttributes() ?>>
<span id="el_tipo_documento_M08">
<span<?= $Page->M08->viewAttributes() ?>>
<?= $Page->M08->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M09->Visible) { // M09 ?>
    <tr id="r_M09">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_M09"><?= $Page->M09->caption() ?></span></td>
        <td data-name="M09" <?= $Page->M09->cellAttributes() ?>>
<span id="el_tipo_documento_M09">
<span<?= $Page->M09->viewAttributes() ?>>
<?= $Page->M09->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M10->Visible) { // M10 ?>
    <tr id="r_M10">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_M10"><?= $Page->M10->caption() ?></span></td>
        <td data-name="M10" <?= $Page->M10->cellAttributes() ?>>
<span id="el_tipo_documento_M10">
<span<?= $Page->M10->viewAttributes() ?>>
<?= $Page->M10->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M11->Visible) { // M11 ?>
    <tr id="r_M11">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_M11"><?= $Page->M11->caption() ?></span></td>
        <td data-name="M11" <?= $Page->M11->cellAttributes() ?>>
<span id="el_tipo_documento_M11">
<span<?= $Page->M11->viewAttributes() ?>>
<?= $Page->M11->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M12->Visible) { // M12 ?>
    <tr id="r_M12">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tipo_documento_M12"><?= $Page->M12->caption() ?></span></td>
        <td data-name="M12" <?= $Page->M12->cellAttributes() ?>>
<span id="el_tipo_documento_M12">
<span<?= $Page->M12->viewAttributes() ?>>
<?= $Page->M12->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
<?php } ?>
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
