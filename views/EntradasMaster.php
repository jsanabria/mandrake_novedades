<?php

namespace PHPMaker2021\mandrake;

// Table
$entradas = Container("entradas");
?>
<?php if ($entradas->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_entradasmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($entradas->id->Visible) { // id ?>
        <tr id="r_id">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->id->caption() ?></td>
            <td <?= $entradas->id->cellAttributes() ?>>
<span id="el_entradas_id">
<span<?= $entradas->id->viewAttributes() ?>>
<?= $entradas->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->tipo_documento->Visible) { // tipo_documento ?>
        <tr id="r_tipo_documento">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->tipo_documento->caption() ?></td>
            <td <?= $entradas->tipo_documento->cellAttributes() ?>>
<span id="el_entradas_tipo_documento">
<span<?= $entradas->tipo_documento->viewAttributes() ?>>
<?= $entradas->tipo_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->nro_documento->caption() ?></td>
            <td <?= $entradas->nro_documento->cellAttributes() ?>>
<span id="el_entradas_nro_documento">
<span<?= $entradas->nro_documento->viewAttributes() ?>>
<?= $entradas->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->fecha->Visible) { // fecha ?>
        <tr id="r_fecha">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->fecha->caption() ?></td>
            <td <?= $entradas->fecha->cellAttributes() ?>>
<span id="el_entradas_fecha">
<span<?= $entradas->fecha->viewAttributes() ?>>
<?= $entradas->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->proveedor->Visible) { // proveedor ?>
        <tr id="r_proveedor">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->proveedor->caption() ?></td>
            <td <?= $entradas->proveedor->cellAttributes() ?>>
<span id="el_entradas_proveedor">
<span<?= $entradas->proveedor->viewAttributes() ?>>
<?= $entradas->proveedor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->monto_total->Visible) { // monto_total ?>
        <tr id="r_monto_total">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->monto_total->caption() ?></td>
            <td <?= $entradas->monto_total->cellAttributes() ?>>
<span id="el_entradas_monto_total">
<span<?= $entradas->monto_total->viewAttributes() ?>>
<?= $entradas->monto_total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->alicuota_iva->Visible) { // alicuota_iva ?>
        <tr id="r_alicuota_iva">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->alicuota_iva->caption() ?></td>
            <td <?= $entradas->alicuota_iva->cellAttributes() ?>>
<span id="el_entradas_alicuota_iva">
<span<?= $entradas->alicuota_iva->viewAttributes() ?>>
<?= $entradas->alicuota_iva->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->iva->Visible) { // iva ?>
        <tr id="r_iva">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->iva->caption() ?></td>
            <td <?= $entradas->iva->cellAttributes() ?>>
<span id="el_entradas_iva">
<span<?= $entradas->iva->viewAttributes() ?>>
<?= $entradas->iva->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->total->Visible) { // total ?>
        <tr id="r_total">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->total->caption() ?></td>
            <td <?= $entradas->total->cellAttributes() ?>>
<span id="el_entradas_total">
<span<?= $entradas->total->viewAttributes() ?>>
<?= $entradas->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->documento->Visible) { // documento ?>
        <tr id="r_documento">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->documento->caption() ?></td>
            <td <?= $entradas->documento->cellAttributes() ?>>
<span id="el_entradas_documento">
<span<?= $entradas->documento->viewAttributes() ?>>
<?= $entradas->documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->estatus->Visible) { // estatus ?>
        <tr id="r_estatus">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->estatus->caption() ?></td>
            <td <?= $entradas->estatus->cellAttributes() ?>>
<span id="el_entradas_estatus">
<span<?= $entradas->estatus->viewAttributes() ?>>
<?= $entradas->estatus->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->_username->Visible) { // username ?>
        <tr id="r__username">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->_username->caption() ?></td>
            <td <?= $entradas->_username->cellAttributes() ?>>
<span id="el_entradas__username">
<span<?= $entradas->_username->viewAttributes() ?>>
<?= $entradas->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->ref_islr->Visible) { // ref_islr ?>
        <tr id="r_ref_islr">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->ref_islr->caption() ?></td>
            <td <?= $entradas->ref_islr->cellAttributes() ?>>
<span id="el_entradas_ref_islr">
<span<?= $entradas->ref_islr->viewAttributes() ?>>
<?php if (!EmptyString($entradas->ref_islr->getViewValue()) && $entradas->ref_islr->linkAttributes() != "") { ?>
<a<?= $entradas->ref_islr->linkAttributes() ?>><?= $entradas->ref_islr->getViewValue() ?></a>
<?php } else { ?>
<?= $entradas->ref_islr->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->ref_municipal->Visible) { // ref_municipal ?>
        <tr id="r_ref_municipal">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->ref_municipal->caption() ?></td>
            <td <?= $entradas->ref_municipal->cellAttributes() ?>>
<span id="el_entradas_ref_municipal">
<span<?= $entradas->ref_municipal->viewAttributes() ?>>
<?= $entradas->ref_municipal->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->cliente->Visible) { // cliente ?>
        <tr id="r_cliente">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->cliente->caption() ?></td>
            <td <?= $entradas->cliente->cellAttributes() ?>>
<span id="el_entradas_cliente">
<span<?= $entradas->cliente->viewAttributes() ?>>
<?= $entradas->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->descuento->Visible) { // descuento ?>
        <tr id="r_descuento">
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->descuento->caption() ?></td>
            <td <?= $entradas->descuento->cellAttributes() ?>>
<span id="el_entradas_descuento">
<span<?= $entradas->descuento->viewAttributes() ?>>
<?= $entradas->descuento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
