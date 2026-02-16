<?php

namespace PHPMaker2021\mandrake;

// Page object
$NotificacionesDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fnotificacionesdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fnotificacionesdelete = currentForm = new ew.Form("fnotificacionesdelete", "delete");
    loadjs.done("fnotificacionesdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.notificaciones) ew.vars.tables.notificaciones = <?= JsonEncode(GetClientVar("tables", "notificaciones")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fnotificacionesdelete" id="fnotificacionesdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="notificaciones">
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
<?php if ($Page->notificar->Visible) { // notificar ?>
        <th class="<?= $Page->notificar->headerCellClass() ?>"><span id="elh_notificaciones_notificar" class="notificaciones_notificar"><?= $Page->notificar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->asunto->Visible) { // asunto ?>
        <th class="<?= $Page->asunto->headerCellClass() ?>"><span id="elh_notificaciones_asunto" class="notificaciones_asunto"><?= $Page->asunto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <th class="<?= $Page->_username->headerCellClass() ?>"><span id="elh_notificaciones__username" class="notificaciones__username"><?= $Page->_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_notificaciones_fecha" class="notificaciones_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->enviado->Visible) { // enviado ?>
        <th class="<?= $Page->enviado->headerCellClass() ?>"><span id="elh_notificaciones_enviado" class="notificaciones_enviado"><?= $Page->enviado->caption() ?></span></th>
<?php } ?>
<?php if ($Page->adjunto->Visible) { // adjunto ?>
        <th class="<?= $Page->adjunto->headerCellClass() ?>"><span id="elh_notificaciones_adjunto" class="notificaciones_adjunto"><?= $Page->adjunto->caption() ?></span></th>
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
<?php if ($Page->notificar->Visible) { // notificar ?>
        <td <?= $Page->notificar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_notificaciones_notificar" class="notificaciones_notificar">
<span<?= $Page->notificar->viewAttributes() ?>>
<?= $Page->notificar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->asunto->Visible) { // asunto ?>
        <td <?= $Page->asunto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_notificaciones_asunto" class="notificaciones_asunto">
<span<?= $Page->asunto->viewAttributes() ?>>
<?= $Page->asunto->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <td <?= $Page->_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_notificaciones__username" class="notificaciones__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_notificaciones_fecha" class="notificaciones_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->enviado->Visible) { // enviado ?>
        <td <?= $Page->enviado->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_notificaciones_enviado" class="notificaciones_enviado">
<span<?= $Page->enviado->viewAttributes() ?>>
<?= $Page->enviado->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->adjunto->Visible) { // adjunto ?>
        <td <?= $Page->adjunto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_notificaciones_adjunto" class="notificaciones_adjunto">
<span>
<?= GetFileViewTag($Page->adjunto, $Page->adjunto->getViewValue(), false) ?>
</span>
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
