<?php

namespace PHPMaker2021\mandrake;

// Table
$abono2 = Container("abono2");
?>
<?php if ($abono2->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_abono2master" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($abono2->cliente->Visible) { // cliente ?>
        <tr id="r_cliente">
            <td class="<?= $abono2->TableLeftColumnClass ?>"><?= $abono2->cliente->caption() ?></td>
            <td <?= $abono2->cliente->cellAttributes() ?>>
<span id="el_abono2_cliente">
<span<?= $abono2->cliente->viewAttributes() ?>>
<?= $abono2->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono2->fecha->Visible) { // fecha ?>
        <tr id="r_fecha">
            <td class="<?= $abono2->TableLeftColumnClass ?>"><?= $abono2->fecha->caption() ?></td>
            <td <?= $abono2->fecha->cellAttributes() ?>>
<span id="el_abono2_fecha">
<span<?= $abono2->fecha->viewAttributes() ?>>
<?= $abono2->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono2->nro_recibo->Visible) { // nro_recibo ?>
        <tr id="r_nro_recibo">
            <td class="<?= $abono2->TableLeftColumnClass ?>"><?= $abono2->nro_recibo->caption() ?></td>
            <td <?= $abono2->nro_recibo->cellAttributes() ?>>
<span id="el_abono2_nro_recibo">
<span<?= $abono2->nro_recibo->viewAttributes() ?>>
<?= $abono2->nro_recibo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono2->pago->Visible) { // pago ?>
        <tr id="r_pago">
            <td class="<?= $abono2->TableLeftColumnClass ?>"><?= $abono2->pago->caption() ?></td>
            <td <?= $abono2->pago->cellAttributes() ?>>
<span id="el_abono2_pago">
<span<?= $abono2->pago->viewAttributes() ?>>
<?= $abono2->pago->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono2->_username->Visible) { // username ?>
        <tr id="r__username">
            <td class="<?= $abono2->TableLeftColumnClass ?>"><?= $abono2->_username->caption() ?></td>
            <td <?= $abono2->_username->cellAttributes() ?>>
<span id="el_abono2__username">
<span<?= $abono2->_username->viewAttributes() ?>>
<?= $abono2->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
