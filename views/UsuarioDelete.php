<?php

namespace PHPMaker2021\mandrake;

// Page object
$UsuarioDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fusuariodelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fusuariodelete = currentForm = new ew.Form("fusuariodelete", "delete");
    loadjs.done("fusuariodelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.usuario) ew.vars.tables.usuario = <?= JsonEncode(GetClientVar("tables", "usuario")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fusuariodelete" id="fusuariodelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="usuario">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->_username->Visible) { // username ?>
        <th class="<?= $Page->_username->headerCellClass() ?>"><span id="elh_usuario__username" class="usuario__username"><?= $Page->_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th class="<?= $Page->nombre->headerCellClass() ?>"><span id="elh_usuario_nombre" class="usuario_nombre"><?= $Page->nombre->caption() ?></span></th>
<?php } ?>
<?php if ($Page->userlevelid->Visible) { // userlevelid ?>
        <th class="<?= $Page->userlevelid->headerCellClass() ?>"><span id="elh_usuario_userlevelid" class="usuario_userlevelid"><?= $Page->userlevelid->caption() ?></span></th>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
        <th class="<?= $Page->asesor->headerCellClass() ?>"><span id="elh_usuario_asesor" class="usuario_asesor"><?= $Page->asesor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th class="<?= $Page->cliente->headerCellClass() ?>"><span id="elh_usuario_cliente" class="usuario_cliente"><?= $Page->cliente->caption() ?></span></th>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <th class="<?= $Page->proveedor->headerCellClass() ?>"><span id="elh_usuario_proveedor" class="usuario_proveedor"><?= $Page->proveedor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->foto->Visible) { // foto ?>
        <th class="<?= $Page->foto->headerCellClass() ?>"><span id="elh_usuario_foto" class="usuario_foto"><?= $Page->foto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><span id="elh_usuario_activo" class="usuario_activo"><?= $Page->activo->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->_username->Visible) { // username ?>
        <td <?= $Page->_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_usuario__username" class="usuario__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <td <?= $Page->nombre->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_usuario_nombre" class="usuario_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->userlevelid->Visible) { // userlevelid ?>
        <td <?= $Page->userlevelid->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_usuario_userlevelid" class="usuario_userlevelid">
<span<?= $Page->userlevelid->viewAttributes() ?>>
<?= $Page->userlevelid->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
        <td <?= $Page->asesor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_usuario_asesor" class="usuario_asesor">
<span<?= $Page->asesor->viewAttributes() ?>>
<?= $Page->asesor->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <td <?= $Page->cliente->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_usuario_cliente" class="usuario_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <td <?= $Page->proveedor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_usuario_proveedor" class="usuario_proveedor">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->foto->Visible) { // foto ?>
        <td <?= $Page->foto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_usuario_foto" class="usuario_foto">
<span>
<?= GetFileViewTag($Page->foto, $Page->foto->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <td <?= $Page->activo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_usuario_activo" class="usuario_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
