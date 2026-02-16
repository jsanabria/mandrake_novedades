<?php

namespace PHPMaker2021\mandrake;

// Table
$cobros_cliente_encabezado = Container("cobros_cliente_encabezado");
?>
<?php if ($cobros_cliente_encabezado->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_cobros_cliente_encabezadomaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($cobros_cliente_encabezado->id->Visible) { // id ?>
        <tr id="r_id">
            <td class="<?= $cobros_cliente_encabezado->TableLeftColumnClass ?>"><?= $cobros_cliente_encabezado->id->caption() ?></td>
            <td <?= $cobros_cliente_encabezado->id->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_id">
<span<?= $cobros_cliente_encabezado->id->viewAttributes() ?>>
<?= $cobros_cliente_encabezado->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente_encabezado->cliente->Visible) { // cliente ?>
        <tr id="r_cliente">
            <td class="<?= $cobros_cliente_encabezado->TableLeftColumnClass ?>"><?= $cobros_cliente_encabezado->cliente->caption() ?></td>
            <td <?= $cobros_cliente_encabezado->cliente->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_cliente">
<span<?= $cobros_cliente_encabezado->cliente->viewAttributes() ?>>
<?= $cobros_cliente_encabezado->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente_encabezado->id_documento->Visible) { // id_documento ?>
        <tr id="r_id_documento">
            <td class="<?= $cobros_cliente_encabezado->TableLeftColumnClass ?>"><?= $cobros_cliente_encabezado->id_documento->caption() ?></td>
            <td <?= $cobros_cliente_encabezado->id_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_id_documento">
<span<?= $cobros_cliente_encabezado->id_documento->viewAttributes() ?>>
<?= $cobros_cliente_encabezado->id_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente_encabezado->tipo_documento->Visible) { // tipo_documento ?>
        <tr id="r_tipo_documento">
            <td class="<?= $cobros_cliente_encabezado->TableLeftColumnClass ?>"><?= $cobros_cliente_encabezado->tipo_documento->caption() ?></td>
            <td <?= $cobros_cliente_encabezado->tipo_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_tipo_documento">
<span<?= $cobros_cliente_encabezado->tipo_documento->viewAttributes() ?>>
<?= $cobros_cliente_encabezado->tipo_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente_encabezado->fecha->Visible) { // fecha ?>
        <tr id="r_fecha">
            <td class="<?= $cobros_cliente_encabezado->TableLeftColumnClass ?>"><?= $cobros_cliente_encabezado->fecha->caption() ?></td>
            <td <?= $cobros_cliente_encabezado->fecha->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_fecha">
<span<?= $cobros_cliente_encabezado->fecha->viewAttributes() ?>>
<?= $cobros_cliente_encabezado->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente_encabezado->monto->Visible) { // monto ?>
        <tr id="r_monto">
            <td class="<?= $cobros_cliente_encabezado->TableLeftColumnClass ?>"><?= $cobros_cliente_encabezado->monto->caption() ?></td>
            <td <?= $cobros_cliente_encabezado->monto->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_monto">
<span<?= $cobros_cliente_encabezado->monto->viewAttributes() ?>>
<?= $cobros_cliente_encabezado->monto->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente_encabezado->moneda->Visible) { // moneda ?>
        <tr id="r_moneda">
            <td class="<?= $cobros_cliente_encabezado->TableLeftColumnClass ?>"><?= $cobros_cliente_encabezado->moneda->caption() ?></td>
            <td <?= $cobros_cliente_encabezado->moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_moneda">
<span<?= $cobros_cliente_encabezado->moneda->viewAttributes() ?>>
<?= $cobros_cliente_encabezado->moneda->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente_encabezado->nota->Visible) { // nota ?>
        <tr id="r_nota">
            <td class="<?= $cobros_cliente_encabezado->TableLeftColumnClass ?>"><?= $cobros_cliente_encabezado->nota->caption() ?></td>
            <td <?= $cobros_cliente_encabezado->nota->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_nota">
<span<?= $cobros_cliente_encabezado->nota->viewAttributes() ?>>
<?= $cobros_cliente_encabezado->nota->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente_encabezado->fecha_registro->Visible) { // fecha_registro ?>
        <tr id="r_fecha_registro">
            <td class="<?= $cobros_cliente_encabezado->TableLeftColumnClass ?>"><?= $cobros_cliente_encabezado->fecha_registro->caption() ?></td>
            <td <?= $cobros_cliente_encabezado->fecha_registro->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_fecha_registro">
<span<?= $cobros_cliente_encabezado->fecha_registro->viewAttributes() ?>>
<?= $cobros_cliente_encabezado->fecha_registro->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
