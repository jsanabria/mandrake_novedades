<?php

namespace PHPMaker2021\mandrake;

// Page object
$AbonoView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fabonoview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fabonoview = currentForm = new ew.Form("fabonoview", "view");
    loadjs.done("fabonoview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.abono) ew.vars.tables.abono = <?= JsonEncode(GetClientVar("tables", "abono")) ?>;
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
<form name="fabonoview" id="fabonoview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="abono">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->nro_recibo->Visible) { // nro_recibo ?>
    <tr id="r_nro_recibo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_abono_nro_recibo"><?= $Page->nro_recibo->caption() ?></span></td>
        <td data-name="nro_recibo" <?= $Page->nro_recibo->cellAttributes() ?>>
<span id="el_abono_nro_recibo">
<span<?= $Page->nro_recibo->viewAttributes() ?>>
<?= $Page->nro_recibo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <tr id="r_cliente">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_abono_cliente"><?= $Page->cliente->caption() ?></span></td>
        <td data-name="cliente" <?= $Page->cliente->cellAttributes() ?>>
<span id="el_abono_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_abono_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_abono_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pago->Visible) { // pago ?>
    <tr id="r_pago">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_abono_pago"><?= $Page->pago->caption() ?></span></td>
        <td data-name="pago" <?= $Page->pago->cellAttributes() ?>>
<span id="el_abono_pago">
<span<?= $Page->pago->viewAttributes() ?>>
<?= $Page->pago->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_abono_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota" <?= $Page->nota->cellAttributes() ?>>
<span id="el_abono_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <tr id="r_metodo_pago">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_abono_metodo_pago"><?= $Page->metodo_pago->caption() ?></span></td>
        <td data-name="metodo_pago" <?= $Page->metodo_pago->cellAttributes() ?>>
<span id="el_abono_metodo_pago">
<span<?= $Page->metodo_pago->viewAttributes() ?>>
<?= $Page->metodo_pago->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_abono__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username" <?= $Page->_username->cellAttributes() ?>>
<span id="el_abono__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
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
<?php
    if (in_array("recarga", explode(",", $Page->getCurrentDetailTable())) && $recarga->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("recarga", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "RecargaGrid.php" ?>
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
