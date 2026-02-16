<?php

namespace PHPMaker2021\mandrake;

// Table
$cliente = Container("cliente");
?>
<?php if ($cliente->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_clientemaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($cliente->codigo->Visible) { // codigo ?>
        <tr id="r_codigo">
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->codigo->caption() ?></td>
            <td <?= $cliente->codigo->cellAttributes() ?>>
<span id="el_cliente_codigo">
<span<?= $cliente->codigo->viewAttributes() ?>>
<?= $cliente->codigo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->ci_rif->Visible) { // ci_rif ?>
        <tr id="r_ci_rif">
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->ci_rif->caption() ?></td>
            <td <?= $cliente->ci_rif->cellAttributes() ?>>
<span id="el_cliente_ci_rif">
<span<?= $cliente->ci_rif->viewAttributes() ?>>
<?= $cliente->ci_rif->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->nombre->Visible) { // nombre ?>
        <tr id="r_nombre">
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->nombre->caption() ?></td>
            <td <?= $cliente->nombre->cellAttributes() ?>>
<span id="el_cliente_nombre">
<span<?= $cliente->nombre->viewAttributes() ?>>
<?= $cliente->nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->web->Visible) { // web ?>
        <tr id="r_web">
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->web->caption() ?></td>
            <td <?= $cliente->web->cellAttributes() ?>>
<span id="el_cliente_web">
<span<?= $cliente->web->viewAttributes() ?>>
<?= $cliente->web->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->tarifa->Visible) { // tarifa ?>
        <tr id="r_tarifa">
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->tarifa->caption() ?></td>
            <td <?= $cliente->tarifa->cellAttributes() ?>>
<span id="el_cliente_tarifa">
<span<?= $cliente->tarifa->viewAttributes() ?>>
<?= $cliente->tarifa->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->activo->Visible) { // activo ?>
        <tr id="r_activo">
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->activo->caption() ?></td>
            <td <?= $cliente->activo->cellAttributes() ?>>
<span id="el_cliente_activo">
<span<?= $cliente->activo->viewAttributes() ?>>
<?= $cliente->activo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
