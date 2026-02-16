<?php

namespace PHPMaker2021\mandrake;

// Page object
$PagosView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpagosview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fpagosview = currentForm = new ew.Form("fpagosview", "view");
    loadjs.done("fpagosview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.pagos) ew.vars.tables.pagos = <?= JsonEncode(GetClientVar("tables", "pagos")) ?>;
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
<form name="fpagosview" id="fpagosview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pagos">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
    <tr id="r_tipo_pago">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pagos_tipo_pago"><?= $Page->tipo_pago->caption() ?></span></td>
        <td data-name="tipo_pago" <?= $Page->tipo_pago->cellAttributes() ?>>
<span id="el_pagos_tipo_pago">
<span<?= $Page->tipo_pago->viewAttributes() ?>>
<?= $Page->tipo_pago->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pagos_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_pagos_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <tr id="r_referencia">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pagos_referencia"><?= $Page->referencia->caption() ?></span></td>
        <td data-name="referencia" <?= $Page->referencia->cellAttributes() ?>>
<span id="el_pagos_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <tr id="r_monto">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pagos_monto"><?= $Page->monto->caption() ?></span></td>
        <td data-name="monto" <?= $Page->monto->cellAttributes() ?>>
<span id="el_pagos_monto">
<span<?= $Page->monto->viewAttributes() ?>>
<?= $Page->monto->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pagos_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota" <?= $Page->nota->cellAttributes() ?>>
<span id="el_pagos_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->comprobante_pago->Visible) { // comprobante_pago ?>
    <tr id="r_comprobante_pago">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pagos_comprobante_pago"><?= $Page->comprobante_pago->caption() ?></span></td>
        <td data-name="comprobante_pago" <?= $Page->comprobante_pago->cellAttributes() ?>>
<span id="el_pagos_comprobante_pago">
<span>
<?= GetFileViewTag($Page->comprobante_pago, $Page->comprobante_pago->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <tr id="r_comprobante">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pagos_comprobante"><?= $Page->comprobante->caption() ?></span></td>
        <td data-name="comprobante" <?= $Page->comprobante->cellAttributes() ?>>
<span id="el_pagos_comprobante">
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
