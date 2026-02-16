<?php

namespace PHPMaker2021\mandrake;

// Table
$abono = Container("abono");
?>
<?php if ($abono->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_abonomaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($abono->nro_recibo->Visible) { // nro_recibo ?>
        <tr id="r_nro_recibo">
            <td class="<?= $abono->TableLeftColumnClass ?>"><?= $abono->nro_recibo->caption() ?></td>
            <td <?= $abono->nro_recibo->cellAttributes() ?>>
<span id="el_abono_nro_recibo">
<span<?= $abono->nro_recibo->viewAttributes() ?>>
<?= $abono->nro_recibo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono->cliente->Visible) { // cliente ?>
        <tr id="r_cliente">
            <td class="<?= $abono->TableLeftColumnClass ?>"><?= $abono->cliente->caption() ?></td>
            <td <?= $abono->cliente->cellAttributes() ?>>
<span id="el_abono_cliente">
<span<?= $abono->cliente->viewAttributes() ?>>
<?= $abono->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono->fecha->Visible) { // fecha ?>
        <tr id="r_fecha">
            <td class="<?= $abono->TableLeftColumnClass ?>"><?= $abono->fecha->caption() ?></td>
            <td <?= $abono->fecha->cellAttributes() ?>>
<span id="el_abono_fecha">
<span<?= $abono->fecha->viewAttributes() ?>>
<?= $abono->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono->pago->Visible) { // pago ?>
        <tr id="r_pago">
            <td class="<?= $abono->TableLeftColumnClass ?>"><?= $abono->pago->caption() ?></td>
            <td <?= $abono->pago->cellAttributes() ?>>
<span id="el_abono_pago">
<span<?= $abono->pago->viewAttributes() ?>>
<?= $abono->pago->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono->_username->Visible) { // username ?>
        <tr id="r__username">
            <td class="<?= $abono->TableLeftColumnClass ?>"><?= $abono->_username->caption() ?></td>
            <td <?= $abono->_username->cellAttributes() ?>>
<span id="el_abono__username">
<span<?= $abono->_username->viewAttributes() ?>>
<?= $abono->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
