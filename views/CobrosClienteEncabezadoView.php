<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteEncabezadoView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcobros_cliente_encabezadoview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcobros_cliente_encabezadoview = currentForm = new ew.Form("fcobros_cliente_encabezadoview", "view");
    loadjs.done("fcobros_cliente_encabezadoview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.cobros_cliente_encabezado) ew.vars.tables.cobros_cliente_encabezado = <?= JsonEncode(GetClientVar("tables", "cobros_cliente_encabezado")) ?>;
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
<form name="fcobros_cliente_encabezadoview" id="fcobros_cliente_encabezadoview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente_encabezado">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_encabezado_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id" <?= $Page->id->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <tr id="r_cliente">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_encabezado_cliente"><?= $Page->cliente->caption() ?></span></td>
        <td data-name="cliente" <?= $Page->cliente->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
    <tr id="r_id_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_encabezado_id_documento"><?= $Page->id_documento->caption() ?></span></td>
        <td data-name="id_documento" <?= $Page->id_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_id_documento">
<span<?= $Page->id_documento->viewAttributes() ?>>
<?= $Page->id_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <tr id="r_tipo_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_encabezado_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></td>
        <td data-name="tipo_documento" <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_encabezado_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <tr id="r_monto">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_encabezado_monto"><?= $Page->monto->caption() ?></span></td>
        <td data-name="monto" <?= $Page->monto->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_monto">
<span<?= $Page->monto->viewAttributes() ?>>
<?= $Page->monto->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_encabezado_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda" <?= $Page->moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_encabezado_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota" <?= $Page->nota->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_registro->Visible) { // fecha_registro ?>
    <tr id="r_fecha_registro">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_encabezado_fecha_registro"><?= $Page->fecha_registro->caption() ?></span></td>
        <td data-name="fecha_registro" <?= $Page->fecha_registro->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_fecha_registro">
<span<?= $Page->fecha_registro->viewAttributes() ?>>
<?= $Page->fecha_registro->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_encabezado__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username" <?= $Page->_username->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <tr id="r_comprobante">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_encabezado_comprobante"><?= $Page->comprobante->caption() ?></span></td>
        <td data-name="comprobante" <?= $Page->comprobante->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_comprobante">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?= $Page->comprobante->getViewValue() ?></span>
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
    if (in_array("cobros_cliente_detalle", explode(",", $Page->getCurrentDetailTable())) && $cobros_cliente_detalle->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("cobros_cliente_detalle", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "CobrosClienteDetalleGrid.php" ?>
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
