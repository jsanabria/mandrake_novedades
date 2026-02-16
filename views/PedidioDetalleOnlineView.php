<?php

namespace PHPMaker2021\mandrake;

// Page object
$PedidioDetalleOnlineView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpedidio_detalle_onlineview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fpedidio_detalle_onlineview = currentForm = new ew.Form("fpedidio_detalle_onlineview", "view");
    loadjs.done("fpedidio_detalle_onlineview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.pedidio_detalle_online) ew.vars.tables.pedidio_detalle_online = <?= JsonEncode(GetClientVar("tables", "pedidio_detalle_online")) ?>;
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
<form name="fpedidio_detalle_onlineview" id="fpedidio_detalle_onlineview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pedidio_detalle_online">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <tr id="r_fabricante">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pedidio_detalle_online_fabricante"><?= $Page->fabricante->caption() ?></span></td>
        <td data-name="fabricante" <?= $Page->fabricante->cellAttributes() ?>>
<span id="el_pedidio_detalle_online_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <tr id="r_articulo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pedidio_detalle_online_articulo"><?= $Page->articulo->caption() ?></span></td>
        <td data-name="articulo" <?= $Page->articulo->cellAttributes() ?>>
<span id="el_pedidio_detalle_online_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
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
