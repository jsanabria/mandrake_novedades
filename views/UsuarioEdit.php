<?php

namespace PHPMaker2021\mandrake;

// Page object
$UsuarioEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fusuarioedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fusuarioedit = currentForm = new ew.Form("fusuarioedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "usuario")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.usuario)
        ew.vars.tables.usuario = currentTable;
    fusuarioedit.addFields([
        ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid],
        ["_password", [fields._password.visible && fields._password.required ? ew.Validators.required(fields._password.caption) : null], fields._password.isInvalid],
        ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
        ["telefono", [fields.telefono.visible && fields.telefono.required ? ew.Validators.required(fields.telefono.caption) : null], fields.telefono.isInvalid],
        ["_email", [fields._email.visible && fields._email.required ? ew.Validators.required(fields._email.caption) : null, ew.Validators.email], fields._email.isInvalid],
        ["userlevelid", [fields.userlevelid.visible && fields.userlevelid.required ? ew.Validators.required(fields.userlevelid.caption) : null], fields.userlevelid.isInvalid],
        ["asesor", [fields.asesor.visible && fields.asesor.required ? ew.Validators.required(fields.asesor.caption) : null], fields.asesor.isInvalid],
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
        ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
        ["foto", [fields.foto.visible && fields.foto.required ? ew.Validators.fileRequired(fields.foto.caption) : null], fields.foto.isInvalid],
        ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fusuarioedit,
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
    fusuarioedit.validate = function () {
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
    fusuarioedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fusuarioedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fusuarioedit.lists.userlevelid = <?= $Page->userlevelid->toClientList($Page) ?>;
    fusuarioedit.lists.asesor = <?= $Page->asesor->toClientList($Page) ?>;
    fusuarioedit.lists.cliente = <?= $Page->cliente->toClientList($Page) ?>;
    fusuarioedit.lists.proveedor = <?= $Page->proveedor->toClientList($Page) ?>;
    fusuarioedit.lists.activo = <?= $Page->activo->toClientList($Page) ?>;
    loadjs.done("fusuarioedit");
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
<form name="fusuarioedit" id="fusuarioedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="usuario">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->_username->Visible) { // username ?>
    <div id="r__username" class="form-group row">
        <label id="elh_usuario__username" for="x__username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?><?= $Page->_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_username->cellAttributes() ?>>
<span id="el_usuario__username">
<span<?= $Page->_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->_username->getDisplayValue($Page->_username->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="usuario" data-field="x__username" data-hidden="1" name="x__username" id="x__username" value="<?= HtmlEncode($Page->_username->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_password->Visible) { // password ?>
    <div id="r__password" class="form-group row">
        <label id="elh_usuario__password" for="x__password" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_password->caption() ?><?= $Page->_password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_password->cellAttributes() ?>>
<span id="el_usuario__password">
<div class="input-group">
    <input type="password" name="x__password" id="x__password" autocomplete="new-password" data-field="x__password" value="<?= $Page->_password->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->_password->getPlaceHolder()) ?>"<?= $Page->_password->editAttributes() ?> aria-describedby="x__password_help">
    <div class="input-group-append"><button type="button" class="btn btn-default ew-toggle-password rounded-right" onclick="ew.togglePassword(event);"><i class="fas fa-eye"></i></button></div>
</div>
<?= $Page->_password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_password->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre" class="form-group row">
        <label id="elh_usuario_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nombre->cellAttributes() ?>>
<span id="el_usuario_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" data-table="usuario" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" value="<?= $Page->nombre->EditValue ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono->Visible) { // telefono ?>
    <div id="r_telefono" class="form-group row">
        <label id="elh_usuario_telefono" for="x_telefono" class="<?= $Page->LeftColumnClass ?>"><?= $Page->telefono->caption() ?><?= $Page->telefono->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->telefono->cellAttributes() ?>>
<span id="el_usuario_telefono">
<input type="<?= $Page->telefono->getInputTextType() ?>" data-table="usuario" data-field="x_telefono" name="x_telefono" id="x_telefono" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono->getPlaceHolder()) ?>" value="<?= $Page->telefono->EditValue ?>"<?= $Page->telefono->editAttributes() ?> aria-describedby="x_telefono_help">
<?= $Page->telefono->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
    <div id="r__email" class="form-group row">
        <label id="elh_usuario__email" for="x__email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_email->caption() ?><?= $Page->_email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_email->cellAttributes() ?>>
<span id="el_usuario__email">
<input type="<?= $Page->_email->getInputTextType() ?>" data-table="usuario" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->_email->getPlaceHolder()) ?>" value="<?= $Page->_email->EditValue ?>"<?= $Page->_email->editAttributes() ?> aria-describedby="x__email_help">
<?= $Page->_email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_email->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->userlevelid->Visible) { // userlevelid ?>
    <div id="r_userlevelid" class="form-group row">
        <label id="elh_usuario_userlevelid" for="x_userlevelid" class="<?= $Page->LeftColumnClass ?>"><?= $Page->userlevelid->caption() ?><?= $Page->userlevelid->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->userlevelid->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el_usuario_userlevelid">
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->userlevelid->getDisplayValue($Page->userlevelid->EditValue))) ?>">
</span>
<?php } else { ?>
<span id="el_usuario_userlevelid">
    <select
        id="x_userlevelid"
        name="x_userlevelid"
        class="form-control ew-select<?= $Page->userlevelid->isInvalidClass() ?>"
        data-select2-id="usuario_x_userlevelid"
        data-table="usuario"
        data-field="x_userlevelid"
        data-value-separator="<?= $Page->userlevelid->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->userlevelid->getPlaceHolder()) ?>"
        <?= $Page->userlevelid->editAttributes() ?>>
        <?= $Page->userlevelid->selectOptionListHtml("x_userlevelid") ?>
    </select>
    <?= $Page->userlevelid->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->userlevelid->getErrorMessage() ?></div>
<?= $Page->userlevelid->Lookup->getParamTag($Page, "p_x_userlevelid") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='usuario_x_userlevelid']"),
        options = { name: "x_userlevelid", selectId: "usuario_x_userlevelid", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.usuario.fields.userlevelid.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
    <div id="r_asesor" class="form-group row">
        <label id="elh_usuario_asesor" for="x_asesor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->asesor->caption() ?><?= $Page->asesor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->asesor->cellAttributes() ?>>
<span id="el_usuario_asesor">
<div class="input-group ew-lookup-list" aria-describedby="x_asesor_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_asesor"><?= EmptyValue(strval($Page->asesor->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->asesor->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->asesor->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->asesor->ReadOnly || $Page->asesor->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_asesor',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->asesor->getErrorMessage() ?></div>
<?= $Page->asesor->getCustomMessage() ?>
<?= $Page->asesor->Lookup->getParamTag($Page, "p_x_asesor") ?>
<input type="hidden" is="selection-list" data-table="usuario" data-field="x_asesor" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->asesor->displayValueSeparatorAttribute() ?>" name="x_asesor" id="x_asesor" value="<?= $Page->asesor->CurrentValue ?>"<?= $Page->asesor->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente" class="form-group row">
        <label id="elh_usuario_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cliente->cellAttributes() ?>>
<span id="el_usuario_cliente">
<div class="input-group ew-lookup-list" aria-describedby="x_cliente_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_cliente"><?= EmptyValue(strval($Page->cliente->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->cliente->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->cliente->ReadOnly || $Page->cliente->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_cliente',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage() ?></div>
<?= $Page->cliente->getCustomMessage() ?>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
<input type="hidden" is="selection-list" data-table="usuario" data-field="x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>" name="x_cliente" id="x_cliente" value="<?= $Page->cliente->CurrentValue ?>"<?= $Page->cliente->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <div id="r_proveedor" class="form-group row">
        <label id="elh_usuario_proveedor" for="x_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proveedor->caption() ?><?= $Page->proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->proveedor->cellAttributes() ?>>
<span id="el_usuario_proveedor">
<div class="input-group ew-lookup-list" aria-describedby="x_proveedor_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_proveedor"><?= EmptyValue(strval($Page->proveedor->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->proveedor->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->proveedor->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->proveedor->ReadOnly || $Page->proveedor->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_proveedor',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->proveedor->getErrorMessage() ?></div>
<?= $Page->proveedor->getCustomMessage() ?>
<?= $Page->proveedor->Lookup->getParamTag($Page, "p_x_proveedor") ?>
<input type="hidden" is="selection-list" data-table="usuario" data-field="x_proveedor" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->proveedor->displayValueSeparatorAttribute() ?>" name="x_proveedor" id="x_proveedor" value="<?= $Page->proveedor->CurrentValue ?>"<?= $Page->proveedor->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->foto->Visible) { // foto ?>
    <div id="r_foto" class="form-group row">
        <label id="elh_usuario_foto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->foto->caption() ?><?= $Page->foto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->foto->cellAttributes() ?>>
<span id="el_usuario_foto">
<div id="fd_x_foto">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->foto->title() ?>" data-table="usuario" data-field="x_foto" name="x_foto" id="x_foto" lang="<?= CurrentLanguageID() ?>"<?= $Page->foto->editAttributes() ?><?= ($Page->foto->ReadOnly || $Page->foto->Disabled) ? " disabled" : "" ?> aria-describedby="x_foto_help">
        <label class="custom-file-label ew-file-label" for="x_foto"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->foto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->foto->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_foto" id= "fn_x_foto" value="<?= $Page->foto->Upload->FileName ?>">
<input type="hidden" name="fa_x_foto" id= "fa_x_foto" value="<?= (Post("fa_x_foto") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_foto" id= "fs_x_foto" value="250">
<input type="hidden" name="fx_x_foto" id= "fx_x_foto" value="<?= $Page->foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_foto" id= "fm_x_foto" value="<?= $Page->foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x_foto" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo" class="form-group row">
        <label id="elh_usuario_activo" for="x_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->activo->cellAttributes() ?>>
<span id="el_usuario_activo">
    <select
        id="x_activo"
        name="x_activo"
        class="form-control ew-select<?= $Page->activo->isInvalidClass() ?>"
        data-select2-id="usuario_x_activo"
        data-table="usuario"
        data-field="x_activo"
        data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->activo->getPlaceHolder()) ?>"
        <?= $Page->activo->editAttributes() ?>>
        <?= $Page->activo->selectOptionListHtml("x_activo") ?>
    </select>
    <?= $Page->activo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->activo->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='usuario_x_activo']"),
        options = { name: "x_activo", selectId: "usuario_x_activo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.usuario.fields.activo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.usuario.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="usuario" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
    ew.addEventHandlers("usuario");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
