<?php

namespace PHPMaker2021\mandrake;

// Page object
$NotificacionesAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fnotificacionesadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fnotificacionesadd = currentForm = new ew.Form("fnotificacionesadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "notificaciones")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.notificaciones)
        ew.vars.tables.notificaciones = currentTable;
    fnotificacionesadd.addFields([
        ["notificar", [fields.notificar.visible && fields.notificar.required ? ew.Validators.required(fields.notificar.caption) : null], fields.notificar.isInvalid],
        ["asunto", [fields.asunto.visible && fields.asunto.required ? ew.Validators.required(fields.asunto.caption) : null], fields.asunto.isInvalid],
        ["notificacion", [fields.notificacion.visible && fields.notificacion.required ? ew.Validators.required(fields.notificacion.caption) : null], fields.notificacion.isInvalid],
        ["notificados", [fields.notificados.visible && fields.notificados.required ? ew.Validators.required(fields.notificados.caption) : null], fields.notificados.isInvalid],
        ["notificados_efectivos", [fields.notificados_efectivos.visible && fields.notificados_efectivos.required ? ew.Validators.required(fields.notificados_efectivos.caption) : null], fields.notificados_efectivos.isInvalid],
        ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(7)], fields.fecha.isInvalid],
        ["enviado", [fields.enviado.visible && fields.enviado.required ? ew.Validators.required(fields.enviado.caption) : null], fields.enviado.isInvalid],
        ["adjunto", [fields.adjunto.visible && fields.adjunto.required ? ew.Validators.fileRequired(fields.adjunto.caption) : null], fields.adjunto.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fnotificacionesadd,
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
    fnotificacionesadd.validate = function () {
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
    fnotificacionesadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fnotificacionesadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fnotificacionesadd.lists.notificar = <?= $Page->notificar->toClientList($Page) ?>;
    fnotificacionesadd.lists._username = <?= $Page->_username->toClientList($Page) ?>;
    fnotificacionesadd.lists.enviado = <?= $Page->enviado->toClientList($Page) ?>;
    loadjs.done("fnotificacionesadd");
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
<form name="fnotificacionesadd" id="fnotificacionesadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="notificaciones">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->notificar->Visible) { // notificar ?>
    <div id="r_notificar" class="form-group row">
        <label id="elh_notificaciones_notificar" for="x_notificar" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notificar->caption() ?><?= $Page->notificar->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->notificar->cellAttributes() ?>>
<span id="el_notificaciones_notificar">
    <select
        id="x_notificar"
        name="x_notificar"
        class="form-control ew-select<?= $Page->notificar->isInvalidClass() ?>"
        data-select2-id="notificaciones_x_notificar"
        data-table="notificaciones"
        data-field="x_notificar"
        data-value-separator="<?= $Page->notificar->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->notificar->getPlaceHolder()) ?>"
        <?= $Page->notificar->editAttributes() ?>>
        <?= $Page->notificar->selectOptionListHtml("x_notificar") ?>
    </select>
    <?= $Page->notificar->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->notificar->getErrorMessage() ?></div>
<?= $Page->notificar->Lookup->getParamTag($Page, "p_x_notificar") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='notificaciones_x_notificar']"),
        options = { name: "x_notificar", selectId: "notificaciones_x_notificar", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.notificaciones.fields.notificar.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
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
<?php
$onchange = $Page->_username->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->_username->EditAttrs["onchange"] = "";
?>
<span id="as_x__username" class="ew-auto-suggest">
    <input type="<?= $Page->_username->getInputTextType() ?>" class="form-control" name="sv_x__username" id="sv_x__username" value="<?= RemoveHtml($Page->_username->EditValue) ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>"<?= $Page->_username->editAttributes() ?> aria-describedby="x__username_help">
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="notificaciones" data-field="x__username" data-input="sv_x__username" data-value-separator="<?= $Page->_username->displayValueSeparatorAttribute() ?>" name="x__username" id="x__username" value="<?= HtmlEncode($Page->_username->CurrentValue) ?>"<?= $onchange ?>>
<?= $Page->_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_username->getErrorMessage() ?></div>
<script>
loadjs.ready(["fnotificacionesadd"], function() {
    fnotificacionesadd.createAutoSuggest(Object.assign({"id":"x__username","forceSelect":false}, ew.vars.tables.notificaciones.fields._username.autoSuggestOptions));
});
</script>
<?= $Page->_username->Lookup->getParamTag($Page, "p_x__username") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_notificaciones_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_notificaciones_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="notificaciones" data-field="x_fecha" data-format="7" name="x_fecha" id="x_fecha" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->enviado->Visible) { // enviado ?>
    <div id="r_enviado" class="form-group row">
        <label id="elh_notificaciones_enviado" for="x_enviado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->enviado->caption() ?><?= $Page->enviado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->enviado->cellAttributes() ?>>
<span id="el_notificaciones_enviado">
    <select
        id="x_enviado"
        name="x_enviado"
        class="form-control ew-select<?= $Page->enviado->isInvalidClass() ?>"
        data-select2-id="notificaciones_x_enviado"
        data-table="notificaciones"
        data-field="x_enviado"
        data-value-separator="<?= $Page->enviado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->enviado->getPlaceHolder()) ?>"
        <?= $Page->enviado->editAttributes() ?>>
        <?= $Page->enviado->selectOptionListHtml("x_enviado") ?>
    </select>
    <?= $Page->enviado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->enviado->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='notificaciones_x_enviado']"),
        options = { name: "x_enviado", selectId: "notificaciones_x_enviado", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.notificaciones.fields.enviado.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.notificaciones.fields.enviado.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->adjunto->Visible) { // adjunto ?>
    <div id="r_adjunto" class="form-group row">
        <label id="elh_notificaciones_adjunto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->adjunto->caption() ?><?= $Page->adjunto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->adjunto->cellAttributes() ?>>
<span id="el_notificaciones_adjunto">
<div id="fd_x_adjunto">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->adjunto->title() ?>" data-table="notificaciones" data-field="x_adjunto" name="x_adjunto" id="x_adjunto" lang="<?= CurrentLanguageID() ?>"<?= $Page->adjunto->editAttributes() ?><?= ($Page->adjunto->ReadOnly || $Page->adjunto->Disabled) ? " disabled" : "" ?> aria-describedby="x_adjunto_help">
        <label class="custom-file-label ew-file-label" for="x_adjunto"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->adjunto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->adjunto->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_adjunto" id= "fn_x_adjunto" value="<?= $Page->adjunto->Upload->FileName ?>">
<input type="hidden" name="fa_x_adjunto" id= "fa_x_adjunto" value="0">
<input type="hidden" name="fs_x_adjunto" id= "fs_x_adjunto" value="255">
<input type="hidden" name="fx_x_adjunto" id= "fx_x_adjunto" value="<?= $Page->adjunto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_adjunto" id= "fm_x_adjunto" value="<?= $Page->adjunto->UploadMaxFileSize ?>">
</div>
<table id="ft_x_adjunto" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
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
