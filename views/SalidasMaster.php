<?php

namespace PHPMaker2021\mandrake;

// Table
$salidas = Container("salidas");
?>
<?php if ($salidas->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_salidasmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($salidas->tipo_documento->Visible) { // tipo_documento ?>
        <tr id="r_tipo_documento">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->tipo_documento->caption() ?></td>
            <td <?= $salidas->tipo_documento->cellAttributes() ?>>
<span id="el_salidas_tipo_documento">
<span<?= $salidas->tipo_documento->viewAttributes() ?>>
<?= $salidas->tipo_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->nro_documento->caption() ?></td>
            <td <?= $salidas->nro_documento->cellAttributes() ?>>
<span id="el_salidas_nro_documento">
<span<?= $salidas->nro_documento->viewAttributes() ?>>
<?= $salidas->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->fecha->Visible) { // fecha ?>
        <tr id="r_fecha">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->fecha->caption() ?></td>
            <td <?= $salidas->fecha->cellAttributes() ?>>
<span id="el_salidas_fecha">
<span<?= $salidas->fecha->viewAttributes() ?>>
<?= $salidas->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->cliente->Visible) { // cliente ?>
        <tr id="r_cliente">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->cliente->caption() ?></td>
            <td <?= $salidas->cliente->cellAttributes() ?>>
<span id="el_salidas_cliente">
<span<?= $salidas->cliente->viewAttributes() ?>>
<?= $salidas->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->documento->Visible) { // documento ?>
        <tr id="r_documento">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->documento->caption() ?></td>
            <td <?= $salidas->documento->cellAttributes() ?>>
<span id="el_salidas_documento">
<span<?= $salidas->documento->viewAttributes() ?>>
<?= $salidas->documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->doc_afectado->Visible) { // doc_afectado ?>
        <tr id="r_doc_afectado">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->doc_afectado->caption() ?></td>
            <td <?= $salidas->doc_afectado->cellAttributes() ?>>
<span id="el_salidas_doc_afectado">
<span<?= $salidas->doc_afectado->viewAttributes() ?>>
<?= $salidas->doc_afectado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->monto_total->Visible) { // monto_total ?>
        <tr id="r_monto_total">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->monto_total->caption() ?></td>
            <td <?= $salidas->monto_total->cellAttributes() ?>>
<span id="el_salidas_monto_total">
<span<?= $salidas->monto_total->viewAttributes() ?>>
<?= $salidas->monto_total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->alicuota_iva->Visible) { // alicuota_iva ?>
        <tr id="r_alicuota_iva">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->alicuota_iva->caption() ?></td>
            <td <?= $salidas->alicuota_iva->cellAttributes() ?>>
<span id="el_salidas_alicuota_iva">
<span<?= $salidas->alicuota_iva->viewAttributes() ?>>
<?= $salidas->alicuota_iva->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->iva->Visible) { // iva ?>
        <tr id="r_iva">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->iva->caption() ?></td>
            <td <?= $salidas->iva->cellAttributes() ?>>
<span id="el_salidas_iva">
<span<?= $salidas->iva->viewAttributes() ?>>
<?= $salidas->iva->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->total->Visible) { // total ?>
        <tr id="r_total">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->total->caption() ?></td>
            <td <?= $salidas->total->cellAttributes() ?>>
<span id="el_salidas_total">
<span<?= $salidas->total->viewAttributes() ?>>
<?= $salidas->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->lista_pedido->Visible) { // lista_pedido ?>
        <tr id="r_lista_pedido">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->lista_pedido->caption() ?></td>
            <td <?= $salidas->lista_pedido->cellAttributes() ?>>
<span id="el_salidas_lista_pedido">
<span<?= $salidas->lista_pedido->viewAttributes() ?>>
<?= $salidas->lista_pedido->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->_username->Visible) { // username ?>
        <tr id="r__username">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->_username->caption() ?></td>
            <td <?= $salidas->_username->cellAttributes() ?>>
<span id="el_salidas__username">
<span<?= $salidas->_username->viewAttributes() ?>>
<?= $salidas->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->estatus->Visible) { // estatus ?>
        <tr id="r_estatus">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->estatus->caption() ?></td>
            <td <?= $salidas->estatus->cellAttributes() ?>>
<span id="el_salidas_estatus">
<span<?= $salidas->estatus->viewAttributes() ?>>
<?= $salidas->estatus->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->asesor->Visible) { // asesor ?>
        <tr id="r_asesor">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->asesor->caption() ?></td>
            <td <?= $salidas->asesor->cellAttributes() ?>>
<span id="el_salidas_asesor">
<span<?= $salidas->asesor->viewAttributes() ?>>
<?= $salidas->asesor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->unidades->Visible) { // unidades ?>
        <tr id="r_unidades">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->unidades->caption() ?></td>
            <td <?= $salidas->unidades->cellAttributes() ?>>
<span id="el_salidas_unidades">
<span<?= $salidas->unidades->viewAttributes() ?>>
<?= $salidas->unidades->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->nro_despacho->Visible) { // nro_despacho ?>
        <tr id="r_nro_despacho">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->nro_despacho->caption() ?></td>
            <td <?= $salidas->nro_despacho->cellAttributes() ?>>
<span id="el_salidas_nro_despacho">
<span<?= $salidas->nro_despacho->viewAttributes() ?>>
<?= $salidas->nro_despacho->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->pago_premio->Visible) { // pago_premio ?>
        <tr id="r_pago_premio">
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->pago_premio->caption() ?></td>
            <td <?= $salidas->pago_premio->cellAttributes() ?>>
<span id="el_salidas_pago_premio">
<span<?= $salidas->pago_premio->viewAttributes() ?>>
<?= $salidas->pago_premio->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
