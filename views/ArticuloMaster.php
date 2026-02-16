<?php

namespace PHPMaker2021\mandrake;

// Table
$articulo = Container("articulo");
?>
<?php if ($articulo->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_articulomaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($articulo->codigo_ims->Visible) { // codigo_ims ?>
        <tr id="r_codigo_ims">
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->codigo_ims->caption() ?></td>
            <td <?= $articulo->codigo_ims->cellAttributes() ?>>
<span id="el_articulo_codigo_ims">
<span<?= $articulo->codigo_ims->viewAttributes() ?>>
<?= $articulo->codigo_ims->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->codigo->Visible) { // codigo ?>
        <tr id="r_codigo">
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->codigo->caption() ?></td>
            <td <?= $articulo->codigo->cellAttributes() ?>>
<span id="el_articulo_codigo">
<span<?= $articulo->codigo->viewAttributes() ?>>
<?= $articulo->codigo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->principio_activo->Visible) { // principio_activo ?>
        <tr id="r_principio_activo">
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->principio_activo->caption() ?></td>
            <td <?= $articulo->principio_activo->cellAttributes() ?>>
<span id="el_articulo_principio_activo">
<span<?= $articulo->principio_activo->viewAttributes() ?>>
<?= $articulo->principio_activo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->fabricante->Visible) { // fabricante ?>
        <tr id="r_fabricante">
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->fabricante->caption() ?></td>
            <td <?= $articulo->fabricante->cellAttributes() ?>>
<span id="el_articulo_fabricante">
<span<?= $articulo->fabricante->viewAttributes() ?>>
<?= $articulo->fabricante->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <tr id="r_cantidad_en_mano">
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->cantidad_en_mano->caption() ?></td>
            <td <?= $articulo->cantidad_en_mano->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_mano">
<span<?= $articulo->cantidad_en_mano->viewAttributes() ?>>
<?= $articulo->cantidad_en_mano->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->cantidad_en_pedido->Visible) { // cantidad_en_pedido ?>
        <tr id="r_cantidad_en_pedido">
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->cantidad_en_pedido->caption() ?></td>
            <td <?= $articulo->cantidad_en_pedido->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_pedido">
<span<?= $articulo->cantidad_en_pedido->viewAttributes() ?>>
<?= $articulo->cantidad_en_pedido->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->cantidad_en_transito->Visible) { // cantidad_en_transito ?>
        <tr id="r_cantidad_en_transito">
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->cantidad_en_transito->caption() ?></td>
            <td <?= $articulo->cantidad_en_transito->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_transito">
<span<?= $articulo->cantidad_en_transito->viewAttributes() ?>>
<?= $articulo->cantidad_en_transito->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->descuento->Visible) { // descuento ?>
        <tr id="r_descuento">
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->descuento->caption() ?></td>
            <td <?= $articulo->descuento->cellAttributes() ?>>
<span id="el_articulo_descuento">
<span<?= $articulo->descuento->viewAttributes() ?>>
<?= $articulo->descuento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->activo->Visible) { // activo ?>
        <tr id="r_activo">
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->activo->caption() ?></td>
            <td <?= $articulo->activo->cellAttributes() ?>>
<span id="el_articulo_activo">
<span<?= $articulo->activo->viewAttributes() ?>>
<?= $articulo->activo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->puntos_ventas->Visible) { // puntos_ventas ?>
        <tr id="r_puntos_ventas">
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->puntos_ventas->caption() ?></td>
            <td <?= $articulo->puntos_ventas->cellAttributes() ?>>
<span id="el_articulo_puntos_ventas">
<span<?= $articulo->puntos_ventas->viewAttributes() ?>>
<?= $articulo->puntos_ventas->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->puntos_premio->Visible) { // puntos_premio ?>
        <tr id="r_puntos_premio">
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->puntos_premio->caption() ?></td>
            <td <?= $articulo->puntos_premio->cellAttributes() ?>>
<span id="el_articulo_puntos_premio">
<span<?= $articulo->puntos_premio->viewAttributes() ?>>
<?= $articulo->puntos_premio->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
