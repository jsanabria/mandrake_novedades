<?php

namespace PHPMaker2021\mandrake;

// Page object
$PuntosEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpuntosedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fpuntosedit = currentForm = new ew.Form("fpuntosedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "puntos")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.puntos)
        ew.vars.tables.puntos = currentTable;
    fpuntosedit.addFields([
        ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(14)], fields.fecha.isInvalid],
        ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
        ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["puntos", [fields.puntos.visible && fields.puntos.required ? ew.Validators.required(fields.puntos.caption) : null, ew.Validators.integer], fields.puntos.isInvalid],
        ["saldo", [fields.saldo.visible && fields.saldo.required ? ew.Validators.required(fields.saldo.caption) : null, ew.Validators.integer], fields.saldo.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpuntosedit,
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
    fpuntosedit.validate = function () {
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
    fpuntosedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpuntosedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpuntosedit.lists.cliente = <?= $Page->cliente->toClientList($Page) ?>;
    fpuntosedit.lists.tipo = <?= $Page->tipo->toClientList($Page) ?>;
    fpuntosedit.lists.referencia = <?= $Page->referencia->toClientList($Page) ?>;
    loadjs.done("fpuntosedit");
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
<form name="fpuntosedit" id="fpuntosedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="puntos">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id" class="form-group row">
        <label id="elh_puntos_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id->cellAttributes() ?>>
<span id="el_puntos_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="puntos" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente" class="form-group row">
        <label id="elh_puntos_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cliente->cellAttributes() ?>>
<span id="el_puntos_cliente">
<div class="input-group ew-lookup-list" aria-describedby="x_cliente_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_cliente"><?= EmptyValue(strval($Page->cliente->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->cliente->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->cliente->ReadOnly || $Page->cliente->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_cliente',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage() ?></div>
<?= $Page->cliente->getCustomMessage() ?>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
<input type="hidden" is="selection-list" data-table="puntos" data-field="x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>" name="x_cliente" id="x_cliente" value="<?= $Page->cliente->CurrentValue ?>"<?= $Page->cliente->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_puntos_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_puntos_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="puntos" data-field="x_fecha" data-format="14" name="x_fecha" id="x_fecha" maxlength="10" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpuntosedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fpuntosedit", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":14});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <div id="r_tipo" class="form-group row">
        <label id="elh_puntos_tipo" for="x_tipo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo->caption() ?><?= $Page->tipo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo->cellAttributes() ?>>
<span id="el_puntos_tipo">
    <select
        id="x_tipo"
        name="x_tipo"
        class="form-control ew-select<?= $Page->tipo->isInvalidClass() ?>"
        data-select2-id="puntos_x_tipo"
        data-table="puntos"
        data-field="x_tipo"
        data-value-separator="<?= $Page->tipo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo->getPlaceHolder()) ?>"
        <?= $Page->tipo->editAttributes() ?>>
        <?= $Page->tipo->selectOptionListHtml("x_tipo") ?>
    </select>
    <?= $Page->tipo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='puntos_x_tipo']"),
        options = { name: "x_tipo", selectId: "puntos_x_tipo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.puntos.fields.tipo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.puntos.fields.tipo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento" class="form-group row">
        <label id="elh_puntos_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_puntos_nro_documento">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" data-table="puntos" data-field="x_nro_documento" name="x_nro_documento" id="x_nro_documento" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" value="<?= $Page->nro_documento->EditValue ?>"<?= $Page->nro_documento->editAttributes() ?> aria-describedby="x_nro_documento_help">
<?= $Page->nro_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia" class="form-group row">
        <label id="elh_puntos_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->referencia->cellAttributes() ?>>
<span id="el_puntos_referencia">
<div class="input-group ew-lookup-list" aria-describedby="x_referencia_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_referencia"><?= EmptyValue(strval($Page->referencia->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->referencia->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->referencia->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->referencia->ReadOnly || $Page->referencia->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_referencia',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
<?= $Page->referencia->getCustomMessage() ?>
<?= $Page->referencia->Lookup->getParamTag($Page, "p_x_referencia") ?>
<input type="hidden" is="selection-list" data-table="puntos" data-field="x_referencia" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->referencia->displayValueSeparatorAttribute() ?>" name="x_referencia" id="x_referencia" value="<?= $Page->referencia->CurrentValue ?>"<?= $Page->referencia->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->puntos->Visible) { // puntos ?>
    <div id="r_puntos" class="form-group row">
        <label id="elh_puntos_puntos" for="x_puntos" class="<?= $Page->LeftColumnClass ?>"><?= $Page->puntos->caption() ?><?= $Page->puntos->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->puntos->cellAttributes() ?>>
<span id="el_puntos_puntos">
<input type="<?= $Page->puntos->getInputTextType() ?>" data-table="puntos" data-field="x_puntos" name="x_puntos" id="x_puntos" size="30" maxlength="11" placeholder="<?= HtmlEncode($Page->puntos->getPlaceHolder()) ?>" value="<?= $Page->puntos->EditValue ?>"<?= $Page->puntos->editAttributes() ?> aria-describedby="x_puntos_help">
<?= $Page->puntos->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->puntos->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
    <div id="r_saldo" class="form-group row">
        <label id="elh_puntos_saldo" for="x_saldo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->saldo->caption() ?><?= $Page->saldo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->saldo->cellAttributes() ?>>
<span id="el_puntos_saldo">
<input type="<?= $Page->saldo->getInputTextType() ?>" data-table="puntos" data-field="x_saldo" name="x_saldo" id="x_saldo" size="30" maxlength="11" placeholder="<?= HtmlEncode($Page->saldo->getPlaceHolder()) ?>" value="<?= $Page->saldo->EditValue ?>"<?= $Page->saldo->editAttributes() ?> aria-describedby="x_saldo_help">
<?= $Page->saldo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->saldo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota" class="form-group row">
        <label id="elh_puntos_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota->cellAttributes() ?>>
<span id="el_puntos_nota">
<input type="<?= $Page->nota->getInputTextType() ?>" data-table="puntos" data-field="x_nota" name="x_nota" id="x_nota" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>" value="<?= $Page->nota->EditValue ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help">
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <div id="r__username" class="form-group row">
        <label id="elh_puntos__username" for="x__username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?><?= $Page->_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_username->cellAttributes() ?>>
<span id="el_puntos__username">
<input type="<?= $Page->_username->getInputTextType() ?>" data-table="puntos" data-field="x__username" name="x__username" id="x__username" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>" value="<?= $Page->_username->EditValue ?>"<?= $Page->_username->editAttributes() ?> aria-describedby="x__username_help">
<?= $Page->_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_username->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
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
    ew.addEventHandlers("puntos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
