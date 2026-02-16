<?php

namespace PHPMaker2021\mandrake;

// Page object
$PuntosView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpuntosview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fpuntosview = currentForm = new ew.Form("fpuntosview", "view");
    loadjs.done("fpuntosview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.puntos) ew.vars.tables.puntos = <?= JsonEncode(GetClientVar("tables", "puntos")) ?>;
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
<form name="fpuntosview" id="fpuntosview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="puntos">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_puntos_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id" <?= $Page->id->cellAttributes() ?>>
<span id="el_puntos_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <tr id="r_cliente">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_puntos_cliente"><?= $Page->cliente->caption() ?></span></td>
        <td data-name="cliente" <?= $Page->cliente->cellAttributes() ?>>
<span id="el_puntos_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_puntos_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_puntos_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <tr id="r_tipo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_puntos_tipo"><?= $Page->tipo->caption() ?></span></td>
        <td data-name="tipo" <?= $Page->tipo->cellAttributes() ?>>
<span id="el_puntos_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <tr id="r_nro_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_puntos_nro_documento"><?= $Page->nro_documento->caption() ?></span></td>
        <td data-name="nro_documento" <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_puntos_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <tr id="r_referencia">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_puntos_referencia"><?= $Page->referencia->caption() ?></span></td>
        <td data-name="referencia" <?= $Page->referencia->cellAttributes() ?>>
<span id="el_puntos_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_puntos_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota" <?= $Page->nota->cellAttributes() ?>>
<span id="el_puntos_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->puntos->Visible) { // puntos ?>
    <tr id="r_puntos">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_puntos_puntos"><?= $Page->puntos->caption() ?></span></td>
        <td data-name="puntos" <?= $Page->puntos->cellAttributes() ?>>
<span id="el_puntos_puntos">
<span<?= $Page->puntos->viewAttributes() ?>>
<?= $Page->puntos->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
    <tr id="r_saldo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_puntos_saldo"><?= $Page->saldo->caption() ?></span></td>
        <td data-name="saldo" <?= $Page->saldo->cellAttributes() ?>>
<span id="el_puntos_saldo">
<span<?= $Page->saldo->viewAttributes() ?>>
<?= $Page->saldo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_puntos__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username" <?= $Page->_username->cellAttributes() ?>>
<span id="el_puntos__username">
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
