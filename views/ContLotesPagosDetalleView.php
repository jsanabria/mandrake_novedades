<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContLotesPagosDetalleView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_lotes_pagos_detalleview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcont_lotes_pagos_detalleview = currentForm = new ew.Form("fcont_lotes_pagos_detalleview", "view");
    loadjs.done("fcont_lotes_pagos_detalleview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.cont_lotes_pagos_detalle) ew.vars.tables.cont_lotes_pagos_detalle = <?= JsonEncode(GetClientVar("tables", "cont_lotes_pagos_detalle")) ?>;
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
<form name="fcont_lotes_pagos_detalleview" id="fcont_lotes_pagos_detalleview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_lotes_pagos_detalle">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <tr id="r_proveedor">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_detalle_proveedor"><?= $Page->proveedor->caption() ?></span></td>
        <td data-name="proveedor" <?= $Page->proveedor->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_proveedor">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_detalle_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipodoc->Visible) { // tipodoc ?>
    <tr id="r_tipodoc">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_detalle_tipodoc"><?= $Page->tipodoc->caption() ?></span></td>
        <td data-name="tipodoc" <?= $Page->tipodoc->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_tipodoc">
<span<?= $Page->tipodoc->viewAttributes() ?>>
<?= $Page->tipodoc->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <tr id="r_nro_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_detalle_nro_documento"><?= $Page->nro_documento->caption() ?></span></td>
        <td data-name="nro_documento" <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_a_pagar->Visible) { // monto_a_pagar ?>
    <tr id="r_monto_a_pagar">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_detalle_monto_a_pagar"><?= $Page->monto_a_pagar->caption() ?></span></td>
        <td data-name="monto_a_pagar" <?= $Page->monto_a_pagar->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_monto_a_pagar">
<span<?= $Page->monto_a_pagar->viewAttributes() ?>>
<?= $Page->monto_a_pagar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_pagado->Visible) { // monto_pagado ?>
    <tr id="r_monto_pagado">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_detalle_monto_pagado"><?= $Page->monto_pagado->caption() ?></span></td>
        <td data-name="monto_pagado" <?= $Page->monto_pagado->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_monto_pagado">
<span<?= $Page->monto_pagado->viewAttributes() ?>>
<?= $Page->monto_pagado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
    <tr id="r_saldo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_detalle_saldo"><?= $Page->saldo->caption() ?></span></td>
        <td data-name="saldo" <?= $Page->saldo->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_saldo">
<span<?= $Page->saldo->viewAttributes() ?>>
<?= $Page->saldo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <tr id="r_comprobante">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_detalle_comprobante"><?= $Page->comprobante->caption() ?></span></td>
        <td data-name="comprobante" <?= $Page->comprobante->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_comprobante">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?= $Page->comprobante->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
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
