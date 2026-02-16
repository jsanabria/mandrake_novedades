<?php

namespace PHPMaker2021\mandrake;

// Table
$proveedor = Container("proveedor");
?>
<?php if ($proveedor->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_proveedormaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($proveedor->ci_rif->Visible) { // ci_rif ?>
        <tr id="r_ci_rif">
            <td class="<?= $proveedor->TableLeftColumnClass ?>"><?= $proveedor->ci_rif->caption() ?></td>
            <td <?= $proveedor->ci_rif->cellAttributes() ?>>
<span id="el_proveedor_ci_rif">
<span<?= $proveedor->ci_rif->viewAttributes() ?>>
<?= $proveedor->ci_rif->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($proveedor->nombre->Visible) { // nombre ?>
        <tr id="r_nombre">
            <td class="<?= $proveedor->TableLeftColumnClass ?>"><?= $proveedor->nombre->caption() ?></td>
            <td <?= $proveedor->nombre->cellAttributes() ?>>
<span id="el_proveedor_nombre">
<span<?= $proveedor->nombre->viewAttributes() ?>>
<?= $proveedor->nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($proveedor->activo->Visible) { // activo ?>
        <tr id="r_activo">
            <td class="<?= $proveedor->TableLeftColumnClass ?>"><?= $proveedor->activo->caption() ?></td>
            <td <?= $proveedor->activo->cellAttributes() ?>>
<span id="el_proveedor_activo">
<span<?= $proveedor->activo->viewAttributes() ?>>
<?= $proveedor->activo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
