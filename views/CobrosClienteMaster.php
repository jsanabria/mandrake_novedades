<?php

namespace PHPMaker2021\mandrake;

// Table
$cobros_cliente = Container("cobros_cliente");
?>
<?php if ($cobros_cliente->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_cobros_clientemaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($cobros_cliente->id->Visible) { // id ?>
        <tr id="r_id">
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->id->caption() ?></td>
            <td <?= $cobros_cliente->id->cellAttributes() ?>>
<span id="el_cobros_cliente_id">
<span<?= $cobros_cliente->id->viewAttributes() ?>>
<?= $cobros_cliente->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->cliente->Visible) { // cliente ?>
        <tr id="r_cliente">
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->cliente->caption() ?></td>
            <td <?= $cobros_cliente->cliente->cellAttributes() ?>>
<span id="el_cobros_cliente_cliente">
<span<?= $cobros_cliente->cliente->viewAttributes() ?>>
<?= $cobros_cliente->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->id_documento->Visible) { // id_documento ?>
        <tr id="r_id_documento">
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->id_documento->caption() ?></td>
            <td <?= $cobros_cliente->id_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_id_documento">
<span<?= $cobros_cliente->id_documento->viewAttributes() ?>>
<?= $cobros_cliente->id_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->fecha->Visible) { // fecha ?>
        <tr id="r_fecha">
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->fecha->caption() ?></td>
            <td <?= $cobros_cliente->fecha->cellAttributes() ?>>
<span id="el_cobros_cliente_fecha">
<span<?= $cobros_cliente->fecha->viewAttributes() ?>>
<?= $cobros_cliente->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->moneda->Visible) { // moneda ?>
        <tr id="r_moneda">
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->moneda->caption() ?></td>
            <td <?= $cobros_cliente->moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_moneda">
<span<?= $cobros_cliente->moneda->viewAttributes() ?>>
<?= $cobros_cliente->moneda->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->pago->Visible) { // pago ?>
        <tr id="r_pago">
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->pago->caption() ?></td>
            <td <?= $cobros_cliente->pago->cellAttributes() ?>>
<span id="el_cobros_cliente_pago">
<span<?= $cobros_cliente->pago->viewAttributes() ?>>
<?= $cobros_cliente->pago->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
