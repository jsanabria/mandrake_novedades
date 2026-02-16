<?php

namespace PHPMaker2021\mandrake;

// Page object
$ClienteAddopt = &$Page;
?>
<script>
var currentForm, currentPageID;
var fclienteaddopt;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "addopt";
    fclienteaddopt = currentForm = new ew.Form("fclienteaddopt", "addopt");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cliente")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cliente)
        ew.vars.tables.cliente = currentTable;
    fclienteaddopt.addFields([
        ["ci_rif", [fields.ci_rif.visible && fields.ci_rif.required ? ew.Validators.required(fields.ci_rif.caption) : null], fields.ci_rif.isInvalid],
        ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
        ["direccion", [fields.direccion.visible && fields.direccion.required ? ew.Validators.required(fields.direccion.caption) : null], fields.direccion.isInvalid],
        ["telefono1", [fields.telefono1.visible && fields.telefono1.required ? ew.Validators.required(fields.telefono1.caption) : null], fields.telefono1.isInvalid],
        ["email1", [fields.email1.visible && fields.email1.required ? ew.Validators.required(fields.email1.caption) : null, ew.Validators.email], fields.email1.isInvalid],
        ["web", [fields.web.visible && fields.web.required ? ew.Validators.required(fields.web.caption) : null], fields.web.isInvalid],
        ["tarifa", [fields.tarifa.visible && fields.tarifa.required ? ew.Validators.required(fields.tarifa.caption) : null], fields.tarifa.isInvalid],
        ["refiere", [fields.refiere.visible && fields.refiere.required ? ew.Validators.required(fields.refiere.caption) : null], fields.refiere.isInvalid],
        ["puntos_refiere", [fields.puntos_refiere.visible && fields.puntos_refiere.required ? ew.Validators.required(fields.puntos_refiere.caption) : null], fields.puntos_refiere.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fclienteaddopt,
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
    fclienteaddopt.validate = function () {
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
        return true;
    }

    // Form_CustomValidate
    fclienteaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fclienteaddopt.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fclienteaddopt.lists.web = <?= $Page->web->toClientList($Page) ?>;
    fclienteaddopt.lists.tarifa = <?= $Page->tarifa->toClientList($Page) ?>;
    fclienteaddopt.lists.refiere = <?= $Page->refiere->toClientList($Page) ?>;
    fclienteaddopt.lists.puntos_refiere = <?= $Page->puntos_refiere->toClientList($Page) ?>;
    loadjs.done("fclienteaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fclienteaddopt" id="fclienteaddopt" class="ew-form ew-horizontal" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="cliente">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_ci_rif"><?= $Page->ci_rif->caption() ?><?= $Page->ci_rif->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->ci_rif->getInputTextType() ?>" data-table="cliente" data-field="x_ci_rif" name="x_ci_rif" id="x_ci_rif" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ci_rif->getPlaceHolder()) ?>" value="<?= $Page->ci_rif->EditValue ?>"<?= $Page->ci_rif->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ci_rif->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_nombre"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->nombre->getInputTextType() ?>" data-table="cliente" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" value="<?= $Page->nombre->EditValue ?>"<?= $Page->nombre->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_direccion"><?= $Page->direccion->caption() ?><?= $Page->direccion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<textarea data-table="cliente" data-field="x_direccion" name="x_direccion" id="x_direccion" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->direccion->getPlaceHolder()) ?>"<?= $Page->direccion->editAttributes() ?>><?= $Page->direccion->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Page->direccion->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->telefono1->Visible) { // telefono1 ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_telefono1"><?= $Page->telefono1->caption() ?><?= $Page->telefono1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->telefono1->getInputTextType() ?>" data-table="cliente" data-field="x_telefono1" name="x_telefono1" id="x_telefono1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono1->getPlaceHolder()) ?>" value="<?= $Page->telefono1->EditValue ?>"<?= $Page->telefono1->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->telefono1->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->email1->Visible) { // email1 ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_email1"><?= $Page->email1->caption() ?><?= $Page->email1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->email1->getInputTextType() ?>" data-table="cliente" data-field="x_email1" name="x_email1" id="x_email1" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->email1->getPlaceHolder()) ?>" value="<?= $Page->email1->EditValue ?>"<?= $Page->email1->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->email1->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->web->Visible) { // web ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->web->caption() ?><?= $Page->web->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<template id="tp_x_web">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cliente" data-field="x_web" name="x_web" id="x_web"<?= $Page->web->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_web" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_web"
    name="x_web"
    value="<?= HtmlEncode($Page->web->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_web"
    data-target="dsl_x_web"
    data-repeatcolumn="5"
    class="form-control<?= $Page->web->isInvalidClass() ?>"
    data-table="cliente"
    data-field="x_web"
    data-value-separator="<?= $Page->web->displayValueSeparatorAttribute() ?>"
    <?= $Page->web->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->web->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_tarifa"><?= $Page->tarifa->caption() ?><?= $Page->tarifa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
    <select
        id="x_tarifa"
        name="x_tarifa"
        class="form-control ew-select<?= $Page->tarifa->isInvalidClass() ?>"
        data-select2-id="cliente_x_tarifa"
        data-table="cliente"
        data-field="x_tarifa"
        data-value-separator="<?= $Page->tarifa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tarifa->getPlaceHolder()) ?>"
        <?= $Page->tarifa->editAttributes() ?>>
        <?= $Page->tarifa->selectOptionListHtml("x_tarifa") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->tarifa->getErrorMessage() ?></div>
<?= $Page->tarifa->Lookup->getParamTag($Page, "p_x_tarifa") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='cliente_x_tarifa']"),
        options = { name: "x_tarifa", selectId: "cliente_x_tarifa", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.cliente.fields.tarifa.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div>
<?php } ?>
<?php if ($Page->refiere->Visible) { // refiere ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_refiere"><?= $Page->refiere->caption() ?><?= $Page->refiere->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_refiere"><?= EmptyValue(strval($Page->refiere->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->refiere->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->refiere->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->refiere->ReadOnly || $Page->refiere->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_refiere',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->refiere->getErrorMessage() ?></div>
<?= $Page->refiere->Lookup->getParamTag($Page, "p_x_refiere") ?>
<input type="hidden" is="selection-list" data-table="cliente" data-field="x_refiere" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->refiere->displayValueSeparatorAttribute() ?>" name="x_refiere" id="x_refiere" value="<?= $Page->refiere->CurrentValue ?>"<?= $Page->refiere->editAttributes() ?>>
</div>
    </div>
<?php } ?>
<?php if ($Page->puntos_refiere->Visible) { // puntos_refiere ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->puntos_refiere->caption() ?><?= $Page->puntos_refiere->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<template id="tp_x_puntos_refiere">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cliente" data-field="x_puntos_refiere" name="x_puntos_refiere" id="x_puntos_refiere"<?= $Page->puntos_refiere->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_puntos_refiere" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_puntos_refiere"
    name="x_puntos_refiere"
    value="<?= HtmlEncode($Page->puntos_refiere->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_puntos_refiere"
    data-target="dsl_x_puntos_refiere"
    data-repeatcolumn="5"
    class="form-control<?= $Page->puntos_refiere->isInvalidClass() ?>"
    data-table="cliente"
    data-field="x_puntos_refiere"
    data-value-separator="<?= $Page->puntos_refiere->displayValueSeparatorAttribute() ?>"
    <?= $Page->puntos_refiere->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->puntos_refiere->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("cliente");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
