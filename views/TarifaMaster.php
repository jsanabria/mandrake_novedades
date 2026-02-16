<?php

namespace PHPMaker2021\mandrake;

// Table
$tarifa = Container("tarifa");
?>
<?php if ($tarifa->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_tarifamaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($tarifa->nombre->Visible) { // nombre ?>
        <tr id="r_nombre">
            <td class="<?= $tarifa->TableLeftColumnClass ?>"><?= $tarifa->nombre->caption() ?></td>
            <td <?= $tarifa->nombre->cellAttributes() ?>>
<span id="el_tarifa_nombre">
<span<?= $tarifa->nombre->viewAttributes() ?>>
<?= $tarifa->nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($tarifa->patron->Visible) { // patron ?>
        <tr id="r_patron">
            <td class="<?= $tarifa->TableLeftColumnClass ?>"><?= $tarifa->patron->caption() ?></td>
            <td <?= $tarifa->patron->cellAttributes() ?>>
<span id="el_tarifa_patron">
<span<?= $tarifa->patron->viewAttributes() ?>>
<?= $tarifa->patron->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($tarifa->activo->Visible) { // activo ?>
        <tr id="r_activo">
            <td class="<?= $tarifa->TableLeftColumnClass ?>"><?= $tarifa->activo->caption() ?></td>
            <td <?= $tarifa->activo->cellAttributes() ?>>
<span id="el_tarifa_activo">
<span<?= $tarifa->activo->viewAttributes() ?>>
<?= $tarifa->activo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($tarifa->porcentaje->Visible) { // porcentaje ?>
        <tr id="r_porcentaje">
            <td class="<?= $tarifa->TableLeftColumnClass ?>"><?= $tarifa->porcentaje->caption() ?></td>
            <td <?= $tarifa->porcentaje->cellAttributes() ?>>
<span id="el_tarifa_porcentaje">
<span<?= $tarifa->porcentaje->viewAttributes() ?>>
<?= $tarifa->porcentaje->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
