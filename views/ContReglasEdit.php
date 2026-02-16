<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContReglasEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_reglasedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fcont_reglasedit = currentForm = new ew.Form("fcont_reglasedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_reglas")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_reglas)
        ew.vars.tables.cont_reglas = currentTable;
    fcont_reglasedit.addFields([
        ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
        ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
        ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
        ["cargo", [fields.cargo.visible && fields.cargo.required ? ew.Validators.required(fields.cargo.caption) : null], fields.cargo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_reglasedit,
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
    fcont_reglasedit.validate = function () {
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
    fcont_reglasedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_reglasedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_reglasedit.lists.cuenta = <?= $Page->cuenta->toClientList($Page) ?>;
    fcont_reglasedit.lists.cargo = <?= $Page->cargo->toClientList($Page) ?>;
    loadjs.done("fcont_reglasedit");
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
<form name="fcont_reglasedit" id="fcont_reglasedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_reglas">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "cont_reglas_hr") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="cont_reglas_hr">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->regla->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo" class="form-group row">
        <label id="elh_cont_reglas_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->codigo->cellAttributes() ?>>
<span id="el_cont_reglas_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->codigo->getDisplayValue($Page->codigo->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_codigo" data-hidden="1" name="x_codigo" id="x_codigo" value="<?= HtmlEncode($Page->codigo->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion" class="form-group row">
        <label id="elh_cont_reglas_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_reglas_descripcion">
<input type="<?= $Page->descripcion->getInputTextType() ?>" data-table="cont_reglas" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>" value="<?= $Page->descripcion->EditValue ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help">
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <div id="r_cuenta" class="form-group row">
        <label id="elh_cont_reglas_cuenta" for="x_cuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta->caption() ?><?= $Page->cuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cuenta->cellAttributes() ?>>
<span id="el_cont_reglas_cuenta">
<div class="input-group ew-lookup-list" aria-describedby="x_cuenta_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_cuenta"><?= EmptyValue(strval($Page->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->cuenta->ReadOnly || $Page->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->cuenta->getErrorMessage() ?></div>
<?= $Page->cuenta->getCustomMessage() ?>
<?= $Page->cuenta->Lookup->getParamTag($Page, "p_x_cuenta") ?>
<input type="hidden" is="selection-list" data-table="cont_reglas" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cuenta->displayValueSeparatorAttribute() ?>" name="x_cuenta" id="x_cuenta" value="<?= $Page->cuenta->CurrentValue ?>"<?= $Page->cuenta->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cargo->Visible) { // cargo ?>
    <div id="r_cargo" class="form-group row">
        <label id="elh_cont_reglas_cargo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cargo->caption() ?><?= $Page->cargo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cargo->cellAttributes() ?>>
<span id="el_cont_reglas_cargo">
<template id="tp_x_cargo">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_reglas" data-field="x_cargo" name="x_cargo" id="x_cargo"<?= $Page->cargo->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_cargo" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_cargo"
    name="x_cargo"
    value="<?= HtmlEncode($Page->cargo->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_cargo"
    data-target="dsl_x_cargo"
    data-repeatcolumn="5"
    class="form-control<?= $Page->cargo->isInvalidClass() ?>"
    data-table="cont_reglas"
    data-field="x_cargo"
    data-value-separator="<?= $Page->cargo->displayValueSeparatorAttribute() ?>"
    <?= $Page->cargo->editAttributes() ?>>
<?= $Page->cargo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cargo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="cont_reglas" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
    ew.addEventHandlers("cont_reglas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
