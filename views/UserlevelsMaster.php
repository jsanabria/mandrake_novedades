<?php

namespace PHPMaker2021\mandrake;

// Table
$userlevels = Container("userlevels");
?>
<?php if ($userlevels->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_userlevelsmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($userlevels->userlevelid->Visible) { // userlevelid ?>
        <tr id="r_userlevelid">
            <td class="<?= $userlevels->TableLeftColumnClass ?>"><?= $userlevels->userlevelid->caption() ?></td>
            <td <?= $userlevels->userlevelid->cellAttributes() ?>>
<span id="el_userlevels_userlevelid">
<span<?= $userlevels->userlevelid->viewAttributes() ?>>
<?= $userlevels->userlevelid->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($userlevels->userlevelname->Visible) { // userlevelname ?>
        <tr id="r_userlevelname">
            <td class="<?= $userlevels->TableLeftColumnClass ?>"><?= $userlevels->userlevelname->caption() ?></td>
            <td <?= $userlevels->userlevelname->cellAttributes() ?>>
<span id="el_userlevels_userlevelname">
<span<?= $userlevels->userlevelname->viewAttributes() ?>>
<?= $userlevels->userlevelname->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($userlevels->tipo_acceso->Visible) { // tipo_acceso ?>
        <tr id="r_tipo_acceso">
            <td class="<?= $userlevels->TableLeftColumnClass ?>"><?= $userlevels->tipo_acceso->caption() ?></td>
            <td <?= $userlevels->tipo_acceso->cellAttributes() ?>>
<span id="el_userlevels_tipo_acceso">
<span<?= $userlevels->tipo_acceso->viewAttributes() ?>>
<?= $userlevels->tipo_acceso->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
