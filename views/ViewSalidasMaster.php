<?php

namespace PHPMaker2021\mandrake;

// Table
$view_salidas = Container("view_salidas");
?>
<?php if ($view_salidas->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_salidasmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_salidas->nombre_documento->Visible) { // nombre_documento ?>
        <tr id="r_nombre_documento">
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->nombre_documento->caption() ?></td>
            <td <?= $view_salidas->nombre_documento->cellAttributes() ?>>
<span id="el_view_salidas_nombre_documento">
<span<?= $view_salidas->nombre_documento->viewAttributes() ?>>
<?= $view_salidas->nombre_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_salidas->cliente->Visible) { // cliente ?>
        <tr id="r_cliente">
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->cliente->caption() ?></td>
            <td <?= $view_salidas->cliente->cellAttributes() ?>>
<span id="el_view_salidas_cliente">
<span<?= $view_salidas->cliente->viewAttributes() ?>>
<?= $view_salidas->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_salidas->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento">
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->nro_documento->caption() ?></td>
            <td <?= $view_salidas->nro_documento->cellAttributes() ?>>
<span id="el_view_salidas_nro_documento">
<span<?= $view_salidas->nro_documento->viewAttributes() ?>>
<?= $view_salidas->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_salidas->fecha->Visible) { // fecha ?>
        <tr id="r_fecha">
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->fecha->caption() ?></td>
            <td <?= $view_salidas->fecha->cellAttributes() ?>>
<span id="el_view_salidas_fecha">
<span<?= $view_salidas->fecha->viewAttributes() ?>>
<?= $view_salidas->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_salidas->nota->Visible) { // nota ?>
        <tr id="r_nota">
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->nota->caption() ?></td>
            <td <?= $view_salidas->nota->cellAttributes() ?>>
<span id="el_view_salidas_nota">
<span<?= $view_salidas->nota->viewAttributes() ?>>
<?= $view_salidas->nota->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
