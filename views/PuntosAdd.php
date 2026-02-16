<?php

namespace PHPMaker2021\mandrake;

// Page object
$PuntosAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpuntosadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fpuntosadd = currentForm = new ew.Form("fpuntosadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "puntos")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.puntos)
        ew.vars.tables.puntos = currentTable;
    fpuntosadd.addFields([
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null, ew.Validators.integer], fields.cliente.isInvalid],
        ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["puntos", [fields.puntos.visible && fields.puntos.required ? ew.Validators.required(fields.puntos.caption) : null, ew.Validators.integer], fields.puntos.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpuntosadd,
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
    fpuntosadd.validate = function () {
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
    fpuntosadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpuntosadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpuntosadd.lists.cliente = <?= $Page->cliente->toClientList($Page) ?>;
    fpuntosadd.lists.tipo = <?= $Page->tipo->toClientList($Page) ?>;
    fpuntosadd.lists.referencia = <?= $Page->referencia->toClientList($Page) ?>;
    loadjs.done("fpuntosadd");
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
<form name="fpuntosadd" id="fpuntosadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="puntos">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente" class="form-group row">
        <label id="elh_puntos_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cliente->cellAttributes() ?>>
<span id="el_puntos_cliente">
<?php
$onchange = $Page->cliente->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->cliente->EditAttrs["onchange"] = "";
?>
<span id="as_x_cliente" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->cliente->getInputTextType() ?>" class="form-control" name="sv_x_cliente" id="sv_x_cliente" value="<?= RemoveHtml($Page->cliente->EditValue) ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>"<?= $Page->cliente->editAttributes() ?> aria-describedby="x_cliente_help">
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_cliente',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->cliente->ReadOnly || $Page->cliente->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="puntos" data-field="x_cliente" data-input="sv_x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>" name="x_cliente" id="x_cliente" value="<?= HtmlEncode($Page->cliente->CurrentValue) ?>"<?= $onchange ?>>
<?= $Page->cliente->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage() ?></div>
<script>
loadjs.ready(["fpuntosadd"], function() {
    fpuntosadd.createAutoSuggest(Object.assign({"id":"x_cliente","forceSelect":true}, ew.vars.tables.puntos.fields.cliente.autoSuggestOptions));
});
</script>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
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
<?= $Page->tipo->Lookup->getParamTag($Page, "p_x_tipo") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='puntos_x_tipo']"),
        options = { name: "x_tipo", selectId: "puntos_x_tipo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.puntos.fields.tipo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia" class="form-group row">
        <label id="elh_puntos_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->referencia->cellAttributes() ?>>
<span id="el_puntos_referencia">
<?php
$onchange = $Page->referencia->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->referencia->EditAttrs["onchange"] = "";
?>
<span id="as_x_referencia" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->referencia->getInputTextType() ?>" class="form-control" name="sv_x_referencia" id="sv_x_referencia" value="<?= RemoveHtml($Page->referencia->EditValue) ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>"<?= $Page->referencia->editAttributes() ?> aria-describedby="x_referencia_help">
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->referencia->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_referencia',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->referencia->ReadOnly || $Page->referencia->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="puntos" data-field="x_referencia" data-input="sv_x_referencia" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->referencia->displayValueSeparatorAttribute() ?>" name="x_referencia" id="x_referencia" value="<?= HtmlEncode($Page->referencia->CurrentValue) ?>"<?= $onchange ?>>
<?= $Page->referencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
<script>
loadjs.ready(["fpuntosadd"], function() {
    fpuntosadd.createAutoSuggest(Object.assign({"id":"x_referencia","forceSelect":true}, ew.vars.tables.puntos.fields.referencia.autoSuggestOptions));
});
</script>
<?= $Page->referencia->Lookup->getParamTag($Page, "p_x_referencia") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota" class="form-group row">
        <label id="elh_puntos_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota->cellAttributes() ?>>
<span id="el_puntos_nota">
<textarea data-table="puntos" data-field="x_nota" name="x_nota" id="x_nota" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
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
    ew.addEventHandlers("puntos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    $(document).ready((function(){$("#r_puntos").hide()})),$("#x_tipo").change((function(){"CM"==$("#x_tipo").value()?$("#r_puntos").show():$("#r_puntos").hide()}));
});
</script>
