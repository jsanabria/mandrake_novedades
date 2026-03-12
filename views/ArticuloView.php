<?php

namespace PHPMaker2021\mandrake;

// Page object
$ArticuloView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var farticuloview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    farticuloview = currentForm = new ew.Form("farticuloview", "view");
    loadjs.done("farticuloview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.articulo) ew.vars.tables.articulo = <?= JsonEncode(GetClientVar("tables", "articulo")) ?>;
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
<form name="farticuloview" id="farticuloview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="articulo">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->codigo->Visible) { // codigo ?>
    <tr id="r_codigo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_codigo"><?= $Page->codigo->caption() ?></span></td>
        <td data-name="codigo" <?= $Page->codigo->cellAttributes() ?>>
<span id="el_articulo_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
    <tr id="r_principio_activo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_principio_activo"><?= $Page->principio_activo->caption() ?></span></td>
        <td data-name="principio_activo" <?= $Page->principio_activo->cellAttributes() ?>>
<span id="el_articulo_principio_activo">
<span<?= $Page->principio_activo->viewAttributes() ?>>
<?= $Page->principio_activo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <tr id="r_fabricante">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_fabricante"><?= $Page->fabricante->caption() ?></span></td>
        <td data-name="fabricante" <?= $Page->fabricante->cellAttributes() ?>>
<span id="el_articulo_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->codigo_de_barra->Visible) { // codigo_de_barra ?>
    <tr id="r_codigo_de_barra">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_codigo_de_barra"><?= $Page->codigo_de_barra->caption() ?></span></td>
        <td data-name="codigo_de_barra" <?= $Page->codigo_de_barra->cellAttributes() ?>>
<span id="el_articulo_codigo_de_barra">
<span<?= $Page->codigo_de_barra->viewAttributes() ?>>
<?= $Page->codigo_de_barra->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->unidad_medida_defecto->Visible) { // unidad_medida_defecto ?>
    <tr id="r_unidad_medida_defecto">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_unidad_medida_defecto"><?= $Page->unidad_medida_defecto->caption() ?></span></td>
        <td data-name="unidad_medida_defecto" <?= $Page->unidad_medida_defecto->cellAttributes() ?>>
<span id="el_articulo_unidad_medida_defecto">
<span<?= $Page->unidad_medida_defecto->viewAttributes() ?>>
<?= $Page->unidad_medida_defecto->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_por_unidad_medida->Visible) { // cantidad_por_unidad_medida ?>
    <tr id="r_cantidad_por_unidad_medida">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_por_unidad_medida"><?= $Page->cantidad_por_unidad_medida->caption() ?></span></td>
        <td data-name="cantidad_por_unidad_medida" <?= $Page->cantidad_por_unidad_medida->cellAttributes() ?>>
<span id="el_articulo_cantidad_por_unidad_medida">
<span<?= $Page->cantidad_por_unidad_medida->viewAttributes() ?>>
<?= $Page->cantidad_por_unidad_medida->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->foto->Visible) { // foto ?>
    <tr id="r_foto">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_foto"><?= $Page->foto->caption() ?></span></td>
        <td data-name="foto" <?= $Page->foto->cellAttributes() ?>>
<span id="el_articulo_foto">
<span>
<?= GetFileViewTag($Page->foto, $Page->foto->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_minima->Visible) { // cantidad_minima ?>
    <tr id="r_cantidad_minima">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_minima"><?= $Page->cantidad_minima->caption() ?></span></td>
        <td data-name="cantidad_minima" <?= $Page->cantidad_minima->cellAttributes() ?>>
<span id="el_articulo_cantidad_minima">
<span<?= $Page->cantidad_minima->viewAttributes() ?>>
<?= $Page->cantidad_minima->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_maxima->Visible) { // cantidad_maxima ?>
    <tr id="r_cantidad_maxima">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_maxima"><?= $Page->cantidad_maxima->caption() ?></span></td>
        <td data-name="cantidad_maxima" <?= $Page->cantidad_maxima->cellAttributes() ?>>
<span id="el_articulo_cantidad_maxima">
<span<?= $Page->cantidad_maxima->viewAttributes() ?>>
<?= $Page->cantidad_maxima->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
    <tr id="r_cantidad_en_mano">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_en_mano"><?= $Page->cantidad_en_mano->caption() ?></span></td>
        <td data-name="cantidad_en_mano" <?= $Page->cantidad_en_mano->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_mano">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<?= $Page->cantidad_en_mano->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_en_pedido->Visible) { // cantidad_en_pedido ?>
    <tr id="r_cantidad_en_pedido">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_en_pedido"><?= $Page->cantidad_en_pedido->caption() ?></span></td>
        <td data-name="cantidad_en_pedido" <?= $Page->cantidad_en_pedido->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_pedido">
<span<?= $Page->cantidad_en_pedido->viewAttributes() ?>>
<?= $Page->cantidad_en_pedido->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_en_transito->Visible) { // cantidad_en_transito ?>
    <tr id="r_cantidad_en_transito">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_en_transito"><?= $Page->cantidad_en_transito->caption() ?></span></td>
        <td data-name="cantidad_en_transito" <?= $Page->cantidad_en_transito->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_transito">
<span<?= $Page->cantidad_en_transito->viewAttributes() ?>>
<?= $Page->cantidad_en_transito->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
    <tr id="r_ultimo_costo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_ultimo_costo"><?= $Page->ultimo_costo->caption() ?></span></td>
        <td data-name="ultimo_costo" <?= $Page->ultimo_costo->cellAttributes() ?>>
<span id="el_articulo_ultimo_costo">
<span<?= $Page->ultimo_costo->viewAttributes() ?>>
<?= $Page->ultimo_costo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <tr id="r_descuento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_descuento"><?= $Page->descuento->caption() ?></span></td>
        <td data-name="descuento" <?= $Page->descuento->cellAttributes() ?>>
<span id="el_articulo_descuento">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <tr id="r_precio">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_precio"><?= $Page->precio->caption() ?></span></td>
        <td data-name="precio" <?= $Page->precio->cellAttributes() ?>>
<span id="el_articulo_precio">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->precio2->Visible) { // precio2 ?>
    <tr id="r_precio2">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_precio2"><?= $Page->precio2->caption() ?></span></td>
        <td data-name="precio2" <?= $Page->precio2->cellAttributes() ?>>
<span id="el_articulo_precio2">
<span<?= $Page->precio2->viewAttributes() ?>>
<?= $Page->precio2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alicuota->Visible) { // alicuota ?>
    <tr id="r_alicuota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_alicuota"><?= $Page->alicuota->caption() ?></span></td>
        <td data-name="alicuota" <?= $Page->alicuota->cellAttributes() ?>>
<span id="el_articulo_alicuota">
<span<?= $Page->alicuota->viewAttributes() ?>>
<?= $Page->alicuota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->articulo_inventario->Visible) { // articulo_inventario ?>
    <tr id="r_articulo_inventario">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_articulo_inventario"><?= $Page->articulo_inventario->caption() ?></span></td>
        <td data-name="articulo_inventario" <?= $Page->articulo_inventario->cellAttributes() ?>>
<span id="el_articulo_articulo_inventario">
<span<?= $Page->articulo_inventario->viewAttributes() ?>>
<?= $Page->articulo_inventario->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <tr id="r_activo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_activo"><?= $Page->activo->caption() ?></span></td>
        <td data-name="activo" <?= $Page->activo->cellAttributes() ?>>
<span id="el_articulo_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->puntos_ventas->Visible) { // puntos_ventas ?>
    <tr id="r_puntos_ventas">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_puntos_ventas"><?= $Page->puntos_ventas->caption() ?></span></td>
        <td data-name="puntos_ventas" <?= $Page->puntos_ventas->cellAttributes() ?>>
<span id="el_articulo_puntos_ventas">
<span<?= $Page->puntos_ventas->viewAttributes() ?>>
<?= $Page->puntos_ventas->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->puntos_premio->Visible) { // puntos_premio ?>
    <tr id="r_puntos_premio">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_puntos_premio"><?= $Page->puntos_premio->caption() ?></span></td>
        <td data-name="puntos_premio" <?= $Page->puntos_premio->cellAttributes() ?>>
<span id="el_articulo_puntos_premio">
<span<?= $Page->puntos_premio->viewAttributes() ?>>
<?= $Page->puntos_premio->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sincroniza->Visible) { // sincroniza ?>
    <tr id="r_sincroniza">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_sincroniza"><?= $Page->sincroniza->caption() ?></span></td>
        <td data-name="sincroniza" <?= $Page->sincroniza->cellAttributes() ?>>
<span id="el_articulo_sincroniza">
<span<?= $Page->sincroniza->viewAttributes() ?>>
<?= $Page->sincroniza->getViewValue() ?></span>
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
    if (in_array("articulo_unidad_medida", explode(",", $Page->getCurrentDetailTable())) && $articulo_unidad_medida->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("articulo_unidad_medida", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ArticuloUnidadMedidaGrid.php" ?>
<?php } ?>
<?php
    if (in_array("adjunto", explode(",", $Page->getCurrentDetailTable())) && $adjunto->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("adjunto", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "AdjuntoGrid.php" ?>
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
