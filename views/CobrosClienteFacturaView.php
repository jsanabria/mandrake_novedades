<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteFacturaView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcobros_cliente_facturaview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcobros_cliente_facturaview = currentForm = new ew.Form("fcobros_cliente_facturaview", "view");
    loadjs.done("fcobros_cliente_facturaview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.cobros_cliente_factura) ew.vars.tables.cobros_cliente_factura = <?= JsonEncode(GetClientVar("tables", "cobros_cliente_factura")) ?>;
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
<form name="fcobros_cliente_facturaview" id="fcobros_cliente_facturaview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente_factura">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <tr id="r_tipo_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_factura_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></td>
        <td data-name="tipo_documento" <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
    <tr id="r_abono">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_factura_abono"><?= $Page->abono->caption() ?></span></td>
        <td data-name="abono" <?= $Page->abono->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_abono">
<span<?= $Page->abono->viewAttributes() ?>>
<?= $Page->abono->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <tr id="r_monto">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_factura_monto"><?= $Page->monto->caption() ?></span></td>
        <td data-name="monto" <?= $Page->monto->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_monto">
<span<?= $Page->monto->viewAttributes() ?>>
<?= $Page->monto->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->retivamonto->Visible) { // retivamonto ?>
    <tr id="r_retivamonto">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_factura_retivamonto"><?= $Page->retivamonto->caption() ?></span></td>
        <td data-name="retivamonto" <?= $Page->retivamonto->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_retivamonto">
<span<?= $Page->retivamonto->viewAttributes() ?>>
<?= $Page->retivamonto->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->retiva->Visible) { // retiva ?>
    <tr id="r_retiva">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_factura_retiva"><?= $Page->retiva->caption() ?></span></td>
        <td data-name="retiva" <?= $Page->retiva->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_retiva">
<span<?= $Page->retiva->viewAttributes() ?>>
<?= $Page->retiva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->retislrmonto->Visible) { // retislrmonto ?>
    <tr id="r_retislrmonto">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_factura_retislrmonto"><?= $Page->retislrmonto->caption() ?></span></td>
        <td data-name="retislrmonto" <?= $Page->retislrmonto->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_retislrmonto">
<span<?= $Page->retislrmonto->viewAttributes() ?>>
<?= $Page->retislrmonto->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->retislr->Visible) { // retislr ?>
    <tr id="r_retislr">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_factura_retislr"><?= $Page->retislr->caption() ?></span></td>
        <td data-name="retislr" <?= $Page->retislr->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_retislr">
<span<?= $Page->retislr->viewAttributes() ?>>
<?= $Page->retislr->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <tr id="r_comprobante">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_factura_comprobante"><?= $Page->comprobante->caption() ?></span></td>
        <td data-name="comprobante" <?= $Page->comprobante->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_comprobante">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Page->comprobante->getViewValue()) && $Page->comprobante->linkAttributes() != "") { ?>
<a<?= $Page->comprobante->linkAttributes() ?>><?= $Page->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->comprobante->getViewValue() ?>
<?php } ?>
</span>
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
