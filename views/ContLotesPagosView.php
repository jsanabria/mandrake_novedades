<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContLotesPagosView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_lotes_pagosview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcont_lotes_pagosview = currentForm = new ew.Form("fcont_lotes_pagosview", "view");
    loadjs.done("fcont_lotes_pagosview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.cont_lotes_pagos) ew.vars.tables.cont_lotes_pagos = <?= JsonEncode(GetClientVar("tables", "cont_lotes_pagos")) ?>;
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
<form name="fcont_lotes_pagosview" id="fcont_lotes_pagosview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_lotes_pagos">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id" <?= $Page->id->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
    <tr id="r_banco">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_banco"><?= $Page->banco->caption() ?></span></td>
        <td data-name="banco" <?= $Page->banco->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_banco">
<span<?= $Page->banco->viewAttributes() ?>>
<?= $Page->banco->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <tr id="r_referencia">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_referencia"><?= $Page->referencia->caption() ?></span></td>
        <td data-name="referencia" <?= $Page->referencia->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda" <?= $Page->moneda->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->procesado->Visible) { // procesado ?>
    <tr id="r_procesado">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_procesado"><?= $Page->procesado->caption() ?></span></td>
        <td data-name="procesado" <?= $Page->procesado->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_procesado">
<span<?= $Page->procesado->viewAttributes() ?>>
<?= $Page->procesado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota" <?= $Page->nota->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
    <tr id="r_usuario">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_lotes_pagos_usuario"><?= $Page->usuario->caption() ?></span></td>
        <td data-name="usuario" <?= $Page->usuario->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_usuario">
<span<?= $Page->usuario->viewAttributes() ?>>
<?= $Page->usuario->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("cont_lotes_pagos_detalle", explode(",", $Page->getCurrentDetailTable())) && $cont_lotes_pagos_detalle->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("cont_lotes_pagos_detalle", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ContLotesPagosDetalleGrid.php" ?>
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
