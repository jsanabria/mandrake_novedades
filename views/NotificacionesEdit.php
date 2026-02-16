<?php

namespace PHPMaker2021\mandrake;

// Page object
$NotificacionesEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fnotificacionesedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fnotificacionesedit = currentForm = new ew.Form("fnotificacionesedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "notificaciones")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.notificaciones)
        ew.vars.tables.notificaciones = currentTable;
    fnotificacionesedit.addFields([
        ["notificar", [fields.notificar.visible && fields.notificar.required ? ew.Validators.required(fields.notificar.caption) : null], fields.notificar.isInvalid],
        ["asunto", [fields.asunto.visible && fields.asunto.required ? ew.Validators.required(fields.asunto.caption) : null], fields.asunto.isInvalid],
        ["notificacion", [fields.notificacion.visible && fields.notificacion.required ? ew.Validators.required(fields.notificacion.caption) : null], fields.notificacion.isInvalid],
        ["notificados", [fields.notificados.visible && fields.notificados.required ? ew.Validators.required(fields.notificados.caption) : null], fields.notificados.isInvalid],
        ["notificados_efectivos", [fields.notificados_efectivos.visible && fields.notificados_efectivos.required ? ew.Validators.required(fields.notificados_efectivos.caption) : null], fields.notificados_efectivos.isInvalid],
        ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
        ["enviado", [fields.enviado.visible && fields.enviado.required ? ew.Validators.required(fields.enviado.caption) : null], fields.enviado.isInvalid],
        ["adjunto", [fields.adjunto.visible && fields.adjunto.required ? ew.Validators.fileRequired(fields.adjunto.caption) : null], fields.adjunto.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fnotificacionesedit,
            fobj = f.getForm(),
            $fobj = $(fobj),
            $k = $fobj.find("#" + f.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            f.setInvalid(rowIndex);
        }
    });

    // Validate form
    fnotificacionesedit.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj);
        if ($fobj.find("#confirm").val() == "confirm")
            return true;
        var addcnt = 0,
            $k = $fobj.find("#" + this.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1, // Check rowcnt == 0 => Inline-Add
            gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            $fobj.data("rowindex", rowIndex);

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
        }

        // Process detail forms
        var dfs = $fobj.find("input[name='detailpage']").get();
        for (var i = 0; i < dfs.length; i++) {
            var df = dfs[i],
                val = df.value,
                frm = ew.forms.get(val);
            if (val && frm && !frm.validate())
                return false;
        }
        return true;
    }

    // Form_CustomValidate
    fnotificacionesedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fnotificacionesedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fnotificacionesedit");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if (!$Page->IsModal) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fnotificacionesedit" id="fnotificacionesedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="notificaciones">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->notificar->Visible) { // notificar ?>
    <div id="r_notificar" class="form-group row">
        <label id="elh_notificaciones_notificar" for="x_notificar" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notificar->caption() ?><?= $Page->notificar->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->notificar->cellAttributes() ?>>
<span id="el_notificaciones_notificar">
<span<?= $Page->notificar->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->notificar->getDisplayValue($Page->notificar->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="notificaciones" data-field="x_notificar" data-hidden="1" name="x_notificar" id="x_notificar" value="<?= HtmlEncode($Page->notificar->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->asunto->Visible) { // asunto ?>
    <div id="r_asunto" class="form-group row">
        <label id="elh_notificaciones_asunto" for="x_asunto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->asunto->caption() ?><?= $Page->asunto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->asunto->cellAttributes() ?>>
<span id="el_notificaciones_asunto">
<input type="<?= $Page->asunto->getInputTextType() ?>" data-table="notificaciones" data-field="x_asunto" name="x_asunto" id="x_asunto" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->asunto->getPlaceHolder()) ?>" value="<?= $Page->asunto->EditValue ?>"<?= $Page->asunto->editAttributes() ?> aria-describedby="x_asunto_help">
<?= $Page->asunto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->asunto->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notificacion->Visible) { // notificacion ?>
    <div id="r_notificacion" class="form-group row">
        <label id="elh_notificaciones_notificacion" for="x_notificacion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notificacion->caption() ?><?= $Page->notificacion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->notificacion->cellAttributes() ?>>
<span id="el_notificaciones_notificacion">
<textarea data-table="notificaciones" data-field="x_notificacion" name="x_notificacion" id="x_notificacion" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notificacion->getPlaceHolder()) ?>"<?= $Page->notificacion->editAttributes() ?> aria-describedby="x_notificacion_help"><?= $Page->notificacion->EditValue ?></textarea>
<?= $Page->notificacion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notificacion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notificados->Visible) { // notificados ?>
    <div id="r_notificados" class="form-group row">
        <label id="elh_notificaciones_notificados" for="x_notificados" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notificados->caption() ?><?= $Page->notificados->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->notificados->cellAttributes() ?>>
<span id="el_notificaciones_notificados">
<textarea data-table="notificaciones" data-field="x_notificados" name="x_notificados" id="x_notificados" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notificados->getPlaceHolder()) ?>"<?= $Page->notificados->editAttributes() ?> aria-describedby="x_notificados_help"><?= $Page->notificados->EditValue ?></textarea>
<?= $Page->notificados->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notificados->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notificados_efectivos->Visible) { // notificados_efectivos ?>
    <div id="r_notificados_efectivos" class="form-group row">
        <label id="elh_notificaciones_notificados_efectivos" for="x_notificados_efectivos" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notificados_efectivos->caption() ?><?= $Page->notificados_efectivos->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->notificados_efectivos->cellAttributes() ?>>
<span id="el_notificaciones_notificados_efectivos">
<textarea data-table="notificaciones" data-field="x_notificados_efectivos" name="x_notificados_efectivos" id="x_notificados_efectivos" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notificados_efectivos->getPlaceHolder()) ?>"<?= $Page->notificados_efectivos->editAttributes() ?> aria-describedby="x_notificados_efectivos_help"><?= $Page->notificados_efectivos->EditValue ?></textarea>
<?= $Page->notificados_efectivos->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notificados_efectivos->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <div id="r__username" class="form-group row">
        <label id="elh_notificaciones__username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?><?= $Page->_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_username->cellAttributes() ?>>
<span id="el_notificaciones__username">
<span<?= $Page->_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->_username->getDisplayValue($Page->_username->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="notificaciones" data-field="x__username" data-hidden="1" name="x__username" id="x__username" value="<?= HtmlEncode($Page->_username->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_notificaciones_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_notificaciones_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha->getDisplayValue($Page->fecha->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="notificaciones" data-field="x_fecha" data-hidden="1" name="x_fecha" id="x_fecha" value="<?= HtmlEncode($Page->fecha->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->enviado->Visible) { // enviado ?>
    <div id="r_enviado" class="form-group row">
        <label id="elh_notificaciones_enviado" for="x_enviado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->enviado->caption() ?><?= $Page->enviado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->enviado->cellAttributes() ?>>
<span id="el_notificaciones_enviado">
<span<?= $Page->enviado->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->enviado->getDisplayValue($Page->enviado->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="notificaciones" data-field="x_enviado" data-hidden="1" name="x_enviado" id="x_enviado" value="<?= HtmlEncode($Page->enviado->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->adjunto->Visible) { // adjunto ?>
    <div id="r_adjunto" class="form-group row">
        <label id="elh_notificaciones_adjunto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->adjunto->caption() ?><?= $Page->adjunto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->adjunto->cellAttributes() ?>>
<span id="el_notificaciones_adjunto">
<span>
<?= GetFileViewTag($Page->adjunto, $Page->adjunto->EditValue, false) ?>
</span>
</span>
<input type="hidden" data-table="notificaciones" data-field="x_adjunto" data-hidden="1" name="x_adjunto" id="x_adjunto" value="<?= HtmlEncode($Page->adjunto->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="notificaciones" data-field="x_Nnotificaciones" data-hidden="1" name="x_Nnotificaciones" id="x_Nnotificaciones" value="<?= HtmlEncode($Page->Nnotificaciones->CurrentValue) ?>">
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("notificaciones");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
