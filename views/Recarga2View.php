<?php

namespace PHPMaker2021\mandrake;

// Page object
$Recarga2View = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var frecarga2view;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    frecarga2view = currentForm = new ew.Form("frecarga2view", "view");
    loadjs.done("frecarga2view");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.recarga2) ew.vars.tables.recarga2 = <?= JsonEncode(GetClientVar("tables", "recarga2")) ?>;
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
<form name="frecarga2view" id="frecarga2view" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="recarga2">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->cliente->Visible) { // cliente ?>
    <tr id="r_cliente">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_cliente"><?= $Page->cliente->caption() ?></span></td>
        <td data-name="cliente" <?= $Page->cliente->cellAttributes() ?>>
<span id="el_recarga2_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_recarga2_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <tr id="r_metodo_pago">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_metodo_pago"><?= $Page->metodo_pago->caption() ?></span></td>
        <td data-name="metodo_pago" <?= $Page->metodo_pago->cellAttributes() ?>>
<span id="el_recarga2_metodo_pago">
<span<?= $Page->metodo_pago->viewAttributes() ?>>
<?= $Page->metodo_pago->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <tr id="r_referencia">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_referencia"><?= $Page->referencia->caption() ?></span></td>
        <td data-name="referencia" <?= $Page->referencia->cellAttributes() ?>>
<span id="el_recarga2_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->reverso->Visible) { // reverso ?>
    <tr id="r_reverso">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_reverso"><?= $Page->reverso->caption() ?></span></td>
        <td data-name="reverso" <?= $Page->reverso->cellAttributes() ?>>
<span id="el_recarga2_reverso">
<span<?= $Page->reverso->viewAttributes() ?>>
<?= $Page->reverso->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
    <tr id="r_monto_moneda">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_monto_moneda"><?= $Page->monto_moneda->caption() ?></span></td>
        <td data-name="monto_moneda" <?= $Page->monto_moneda->cellAttributes() ?>>
<span id="el_recarga2_monto_moneda">
<span<?= $Page->monto_moneda->viewAttributes() ?>>
<?= $Page->monto_moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda" <?= $Page->moneda->cellAttributes() ?>>
<span id="el_recarga2_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_moneda->Visible) { // tasa_moneda ?>
    <tr id="r_tasa_moneda">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_tasa_moneda"><?= $Page->tasa_moneda->caption() ?></span></td>
        <td data-name="tasa_moneda" <?= $Page->tasa_moneda->cellAttributes() ?>>
<span id="el_recarga2_tasa_moneda">
<span<?= $Page->tasa_moneda->viewAttributes() ?>>
<?= $Page->tasa_moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_bs->Visible) { // monto_bs ?>
    <tr id="r_monto_bs">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_monto_bs"><?= $Page->monto_bs->caption() ?></span></td>
        <td data-name="monto_bs" <?= $Page->monto_bs->cellAttributes() ?>>
<span id="el_recarga2_monto_bs">
<span<?= $Page->monto_bs->viewAttributes() ?>>
<?= $Page->monto_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
    <tr id="r_tasa_usd">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_tasa_usd"><?= $Page->tasa_usd->caption() ?></span></td>
        <td data-name="tasa_usd" <?= $Page->tasa_usd->cellAttributes() ?>>
<span id="el_recarga2_tasa_usd">
<span<?= $Page->tasa_usd->viewAttributes() ?>>
<?= $Page->tasa_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
    <tr id="r_monto_usd">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_monto_usd"><?= $Page->monto_usd->caption() ?></span></td>
        <td data-name="monto_usd" <?= $Page->monto_usd->cellAttributes() ?>>
<span id="el_recarga2_monto_usd">
<span<?= $Page->monto_usd->viewAttributes() ?>>
<?= $Page->monto_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
    <tr id="r_saldo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_saldo"><?= $Page->saldo->caption() ?></span></td>
        <td data-name="saldo" <?= $Page->saldo->cellAttributes() ?>>
<span id="el_recarga2_saldo">
<span<?= $Page->saldo->viewAttributes() ?>>
<?= $Page->saldo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota" <?= $Page->nota->cellAttributes() ?>>
<span id="el_recarga2_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username" <?= $Page->_username->cellAttributes() ?>>
<span id="el_recarga2__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_recibo->Visible) { // nro_recibo ?>
    <tr id="r_nro_recibo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_recarga2_nro_recibo"><?= $Page->nro_recibo->caption() ?></span></td>
        <td data-name="nro_recibo" <?= $Page->nro_recibo->cellAttributes() ?>>
<span id="el_recarga2_nro_recibo">
<span<?= $Page->nro_recibo->viewAttributes() ?>>
<?= $Page->nro_recibo->getViewValue() ?></span>
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
