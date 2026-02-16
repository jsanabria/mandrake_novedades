<?php

namespace PHPMaker2021\mandrake;

// Table
$cont_lotes_pagos = Container("cont_lotes_pagos");
?>
<?php if ($cont_lotes_pagos->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_cont_lotes_pagosmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($cont_lotes_pagos->id->Visible) { // id ?>
        <tr id="r_id">
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->id->caption() ?></td>
            <td <?= $cont_lotes_pagos->id->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_id">
<span<?= $cont_lotes_pagos->id->viewAttributes() ?>>
<?= $cont_lotes_pagos->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_lotes_pagos->fecha->Visible) { // fecha ?>
        <tr id="r_fecha">
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->fecha->caption() ?></td>
            <td <?= $cont_lotes_pagos->fecha->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_fecha">
<span<?= $cont_lotes_pagos->fecha->viewAttributes() ?>>
<?= $cont_lotes_pagos->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_lotes_pagos->banco->Visible) { // banco ?>
        <tr id="r_banco">
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->banco->caption() ?></td>
            <td <?= $cont_lotes_pagos->banco->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_banco">
<span<?= $cont_lotes_pagos->banco->viewAttributes() ?>>
<?= $cont_lotes_pagos->banco->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_lotes_pagos->referencia->Visible) { // referencia ?>
        <tr id="r_referencia">
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->referencia->caption() ?></td>
            <td <?= $cont_lotes_pagos->referencia->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_referencia">
<span<?= $cont_lotes_pagos->referencia->viewAttributes() ?>>
<?= $cont_lotes_pagos->referencia->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_lotes_pagos->moneda->Visible) { // moneda ?>
        <tr id="r_moneda">
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->moneda->caption() ?></td>
            <td <?= $cont_lotes_pagos->moneda->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_moneda">
<span<?= $cont_lotes_pagos->moneda->viewAttributes() ?>>
<?= $cont_lotes_pagos->moneda->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_lotes_pagos->procesado->Visible) { // procesado ?>
        <tr id="r_procesado">
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->procesado->caption() ?></td>
            <td <?= $cont_lotes_pagos->procesado->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_procesado">
<span<?= $cont_lotes_pagos->procesado->viewAttributes() ?>>
<?= $cont_lotes_pagos->procesado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_lotes_pagos->nota->Visible) { // nota ?>
        <tr id="r_nota">
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->nota->caption() ?></td>
            <td <?= $cont_lotes_pagos->nota->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_nota">
<span<?= $cont_lotes_pagos->nota->viewAttributes() ?>>
<?= $cont_lotes_pagos->nota->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_lotes_pagos->usuario->Visible) { // usuario ?>
        <tr id="r_usuario">
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->usuario->caption() ?></td>
            <td <?= $cont_lotes_pagos->usuario->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_usuario">
<span<?= $cont_lotes_pagos->usuario->viewAttributes() ?>>
<?= $cont_lotes_pagos->usuario->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
