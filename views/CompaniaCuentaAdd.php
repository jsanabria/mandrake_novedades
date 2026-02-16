<?php

namespace PHPMaker2021\mandrake;

// Page object
$CompaniaCuentaAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcompania_cuentaadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fcompania_cuentaadd = currentForm = new ew.Form("fcompania_cuentaadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "compania_cuenta")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.compania_cuenta)
        ew.vars.tables.compania_cuenta = currentTable;
    fcompania_cuentaadd.addFields([
        ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null], fields.banco.isInvalid],
        ["titular", [fields.titular.visible && fields.titular.required ? ew.Validators.required(fields.titular.caption) : null], fields.titular.isInvalid],
        ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
        ["numero", [fields.numero.visible && fields.numero.required ? ew.Validators.required(fields.numero.caption) : null], fields.numero.isInvalid],
        ["mostrar", [fields.mostrar.visible && fields.mostrar.required ? ew.Validators.required(fields.mostrar.caption) : null], fields.mostrar.isInvalid],
        ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
        ["pago_electronico", [fields.pago_electronico.visible && fields.pago_electronico.required ? ew.Validators.required(fields.pago_electronico.caption) : null], fields.pago_electronico.isInvalid],
        ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcompania_cuentaadd,
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
    fcompania_cuentaadd.validate = function () {
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
    fcompania_cuentaadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcompania_cuentaadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcompania_cuentaadd.lists.banco = <?= $Page->banco->toClientList($Page) ?>;
    fcompania_cuentaadd.lists.tipo = <?= $Page->tipo->toClientList($Page) ?>;
    fcompania_cuentaadd.lists.mostrar = <?= $Page->mostrar->toClientList($Page) ?>;
    fcompania_cuentaadd.lists.cuenta = <?= $Page->cuenta->toClientList($Page) ?>;
    fcompania_cuentaadd.lists.pago_electronico = <?= $Page->pago_electronico->toClientList($Page) ?>;
    fcompania_cuentaadd.lists.activo = <?= $Page->activo->toClientList($Page) ?>;
    loadjs.done("fcompania_cuentaadd");
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
<form name="fcompania_cuentaadd" id="fcompania_cuentaadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="compania_cuenta">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "compania") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="compania">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->compania->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->banco->Visible) { // banco ?>
    <div id="r_banco" class="form-group row">
        <label id="elh_compania_cuenta_banco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco->caption() ?><?= $Page->banco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->banco->cellAttributes() ?>>
<span id="el_compania_cuenta_banco">
<?php
$onchange = $Page->banco->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->banco->EditAttrs["onchange"] = "";
?>
<span id="as_x_banco" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->banco->getInputTextType() ?>" class="form-control" name="sv_x_banco" id="sv_x_banco" value="<?= RemoveHtml($Page->banco->EditValue) ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->banco->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->banco->getPlaceHolder()) ?>"<?= $Page->banco->editAttributes() ?> aria-describedby="x_banco_help">
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->banco->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_banco',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->banco->ReadOnly || $Page->banco->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="compania_cuenta" data-field="x_banco" data-input="sv_x_banco" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->banco->displayValueSeparatorAttribute() ?>" name="x_banco" id="x_banco" value="<?= HtmlEncode($Page->banco->CurrentValue) ?>"<?= $onchange ?>>
<?= $Page->banco->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->banco->getErrorMessage() ?></div>
<script>
loadjs.ready(["fcompania_cuentaadd"], function() {
    fcompania_cuentaadd.createAutoSuggest(Object.assign({"id":"x_banco","forceSelect":true}, ew.vars.tables.compania_cuenta.fields.banco.autoSuggestOptions));
});
</script>
<?= $Page->banco->Lookup->getParamTag($Page, "p_x_banco") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->titular->Visible) { // titular ?>
    <div id="r_titular" class="form-group row">
        <label id="elh_compania_cuenta_titular" for="x_titular" class="<?= $Page->LeftColumnClass ?>"><?= $Page->titular->caption() ?><?= $Page->titular->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->titular->cellAttributes() ?>>
<span id="el_compania_cuenta_titular">
<input type="<?= $Page->titular->getInputTextType() ?>" data-table="compania_cuenta" data-field="x_titular" name="x_titular" id="x_titular" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->titular->getPlaceHolder()) ?>" value="<?= $Page->titular->EditValue ?>"<?= $Page->titular->editAttributes() ?> aria-describedby="x_titular_help">
<?= $Page->titular->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->titular->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <div id="r_tipo" class="form-group row">
        <label id="elh_compania_cuenta_tipo" for="x_tipo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo->caption() ?><?= $Page->tipo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo->cellAttributes() ?>>
<span id="el_compania_cuenta_tipo">
    <select
        id="x_tipo"
        name="x_tipo"
        class="form-control ew-select<?= $Page->tipo->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x_tipo"
        data-table="compania_cuenta"
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
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x_tipo']"),
        options = { name: "x_tipo", selectId: "compania_cuenta_x_tipo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.tipo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.tipo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
    <div id="r_numero" class="form-group row">
        <label id="elh_compania_cuenta_numero" for="x_numero" class="<?= $Page->LeftColumnClass ?>"><?= $Page->numero->caption() ?><?= $Page->numero->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->numero->cellAttributes() ?>>
<span id="el_compania_cuenta_numero">
<input type="<?= $Page->numero->getInputTextType() ?>" data-table="compania_cuenta" data-field="x_numero" name="x_numero" id="x_numero" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->numero->getPlaceHolder()) ?>" value="<?= $Page->numero->EditValue ?>"<?= $Page->numero->editAttributes() ?> aria-describedby="x_numero_help">
<?= $Page->numero->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->numero->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mostrar->Visible) { // mostrar ?>
    <div id="r_mostrar" class="form-group row">
        <label id="elh_compania_cuenta_mostrar" for="x_mostrar" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mostrar->caption() ?><?= $Page->mostrar->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->mostrar->cellAttributes() ?>>
<span id="el_compania_cuenta_mostrar">
    <select
        id="x_mostrar"
        name="x_mostrar"
        class="form-control ew-select<?= $Page->mostrar->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x_mostrar"
        data-table="compania_cuenta"
        data-field="x_mostrar"
        data-value-separator="<?= $Page->mostrar->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mostrar->getPlaceHolder()) ?>"
        <?= $Page->mostrar->editAttributes() ?>>
        <?= $Page->mostrar->selectOptionListHtml("x_mostrar") ?>
    </select>
    <?= $Page->mostrar->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->mostrar->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x_mostrar']"),
        options = { name: "x_mostrar", selectId: "compania_cuenta_x_mostrar", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.mostrar.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.mostrar.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <div id="r_cuenta" class="form-group row">
        <label id="elh_compania_cuenta_cuenta" for="x_cuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta->caption() ?><?= $Page->cuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cuenta->cellAttributes() ?>>
<span id="el_compania_cuenta_cuenta">
<div class="input-group ew-lookup-list" aria-describedby="x_cuenta_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_cuenta"><?= EmptyValue(strval($Page->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->cuenta->ReadOnly || $Page->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->cuenta->getErrorMessage() ?></div>
<?= $Page->cuenta->getCustomMessage() ?>
<?= $Page->cuenta->Lookup->getParamTag($Page, "p_x_cuenta") ?>
<input type="hidden" is="selection-list" data-table="compania_cuenta" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cuenta->displayValueSeparatorAttribute() ?>" name="x_cuenta" id="x_cuenta" value="<?= $Page->cuenta->CurrentValue ?>"<?= $Page->cuenta->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pago_electronico->Visible) { // pago_electronico ?>
    <div id="r_pago_electronico" class="form-group row">
        <label id="elh_compania_cuenta_pago_electronico" for="x_pago_electronico" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pago_electronico->caption() ?><?= $Page->pago_electronico->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pago_electronico->cellAttributes() ?>>
<span id="el_compania_cuenta_pago_electronico">
    <select
        id="x_pago_electronico"
        name="x_pago_electronico"
        class="form-control ew-select<?= $Page->pago_electronico->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x_pago_electronico"
        data-table="compania_cuenta"
        data-field="x_pago_electronico"
        data-value-separator="<?= $Page->pago_electronico->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->pago_electronico->getPlaceHolder()) ?>"
        <?= $Page->pago_electronico->editAttributes() ?>>
        <?= $Page->pago_electronico->selectOptionListHtml("x_pago_electronico") ?>
    </select>
    <?= $Page->pago_electronico->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->pago_electronico->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x_pago_electronico']"),
        options = { name: "x_pago_electronico", selectId: "compania_cuenta_x_pago_electronico", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.pago_electronico.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.pago_electronico.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo" class="form-group row">
        <label id="elh_compania_cuenta_activo" for="x_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->activo->cellAttributes() ?>>
<span id="el_compania_cuenta_activo">
    <select
        id="x_activo"
        name="x_activo"
        class="form-control ew-select<?= $Page->activo->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x_activo"
        data-table="compania_cuenta"
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
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x_activo']"),
        options = { name: "x_activo", selectId: "compania_cuenta_x_activo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.activo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <?php if (strval($Page->compania->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_compania" id="x_compania" value="<?= HtmlEncode(strval($Page->compania->getSessionValue())) ?>">
    <?php } ?>
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
    ew.addEventHandlers("compania_cuenta");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
