<?php

namespace PHPMaker2021\mandrake;

// Page object
$ClienteEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fclienteedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fclienteedit = currentForm = new ew.Form("fclienteedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cliente")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cliente)
        ew.vars.tables.cliente = currentTable;
    fclienteedit.addFields([
        ["ci_rif", [fields.ci_rif.visible && fields.ci_rif.required ? ew.Validators.required(fields.ci_rif.caption) : null], fields.ci_rif.isInvalid],
        ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
        ["sucursal", [fields.sucursal.visible && fields.sucursal.required ? ew.Validators.required(fields.sucursal.caption) : null], fields.sucursal.isInvalid],
        ["contacto", [fields.contacto.visible && fields.contacto.required ? ew.Validators.required(fields.contacto.caption) : null], fields.contacto.isInvalid],
        ["ciudad", [fields.ciudad.visible && fields.ciudad.required ? ew.Validators.required(fields.ciudad.caption) : null], fields.ciudad.isInvalid],
        ["direccion", [fields.direccion.visible && fields.direccion.required ? ew.Validators.required(fields.direccion.caption) : null], fields.direccion.isInvalid],
        ["telefono1", [fields.telefono1.visible && fields.telefono1.required ? ew.Validators.required(fields.telefono1.caption) : null], fields.telefono1.isInvalid],
        ["telefono2", [fields.telefono2.visible && fields.telefono2.required ? ew.Validators.required(fields.telefono2.caption) : null], fields.telefono2.isInvalid],
        ["email1", [fields.email1.visible && fields.email1.required ? ew.Validators.required(fields.email1.caption) : null, ew.Validators.email], fields.email1.isInvalid],
        ["email2", [fields.email2.visible && fields.email2.required ? ew.Validators.required(fields.email2.caption) : null], fields.email2.isInvalid],
        ["web", [fields.web.visible && fields.web.required ? ew.Validators.required(fields.web.caption) : null], fields.web.isInvalid],
        ["tarifa", [fields.tarifa.visible && fields.tarifa.required ? ew.Validators.required(fields.tarifa.caption) : null], fields.tarifa.isInvalid],
        ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
        ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid],
        ["refiere", [fields.refiere.visible && fields.refiere.required ? ew.Validators.required(fields.refiere.caption) : null], fields.refiere.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fclienteedit,
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
    fclienteedit.validate = function () {
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
    fclienteedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fclienteedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fclienteedit.lists.ciudad = <?= $Page->ciudad->toClientList($Page) ?>;
    fclienteedit.lists.web = <?= $Page->web->toClientList($Page) ?>;
    fclienteedit.lists.tarifa = <?= $Page->tarifa->toClientList($Page) ?>;
    fclienteedit.lists.cuenta = <?= $Page->cuenta->toClientList($Page) ?>;
    fclienteedit.lists.activo = <?= $Page->activo->toClientList($Page) ?>;
    fclienteedit.lists.refiere = <?= $Page->refiere->toClientList($Page) ?>;
    loadjs.done("fclienteedit");
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
<form name="fclienteedit" id="fclienteedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cliente">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <div id="r_ci_rif" class="form-group row">
        <label id="elh_cliente_ci_rif" for="x_ci_rif" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ci_rif->caption() ?><?= $Page->ci_rif->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ci_rif->cellAttributes() ?>>
<span id="el_cliente_ci_rif">
<input type="<?= $Page->ci_rif->getInputTextType() ?>" data-table="cliente" data-field="x_ci_rif" name="x_ci_rif" id="x_ci_rif" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ci_rif->getPlaceHolder()) ?>" value="<?= $Page->ci_rif->EditValue ?>"<?= $Page->ci_rif->editAttributes() ?> aria-describedby="x_ci_rif_help">
<?= $Page->ci_rif->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ci_rif->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre" class="form-group row">
        <label id="elh_cliente_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nombre->cellAttributes() ?>>
<span id="el_cliente_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" data-table="cliente" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" value="<?= $Page->nombre->EditValue ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sucursal->Visible) { // sucursal ?>
    <div id="r_sucursal" class="form-group row">
        <label id="elh_cliente_sucursal" for="x_sucursal" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sucursal->caption() ?><?= $Page->sucursal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->sucursal->cellAttributes() ?>>
<span id="el_cliente_sucursal">
<input type="<?= $Page->sucursal->getInputTextType() ?>" data-table="cliente" data-field="x_sucursal" name="x_sucursal" id="x_sucursal" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->sucursal->getPlaceHolder()) ?>" value="<?= $Page->sucursal->EditValue ?>"<?= $Page->sucursal->editAttributes() ?> aria-describedby="x_sucursal_help">
<?= $Page->sucursal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sucursal->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->contacto->Visible) { // contacto ?>
    <div id="r_contacto" class="form-group row">
        <label id="elh_cliente_contacto" for="x_contacto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->contacto->caption() ?><?= $Page->contacto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->contacto->cellAttributes() ?>>
<span id="el_cliente_contacto">
<input type="<?= $Page->contacto->getInputTextType() ?>" data-table="cliente" data-field="x_contacto" name="x_contacto" id="x_contacto" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->contacto->getPlaceHolder()) ?>" value="<?= $Page->contacto->EditValue ?>"<?= $Page->contacto->editAttributes() ?> aria-describedby="x_contacto_help">
<?= $Page->contacto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->contacto->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
    <div id="r_ciudad" class="form-group row">
        <label id="elh_cliente_ciudad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ciudad->caption() ?><?= $Page->ciudad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ciudad->cellAttributes() ?>>
<span id="el_cliente_ciudad">
<?php
$onchange = $Page->ciudad->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->ciudad->EditAttrs["onchange"] = "";
?>
<span id="as_x_ciudad" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->ciudad->getInputTextType() ?>" class="form-control" name="sv_x_ciudad" id="sv_x_ciudad" value="<?= RemoveHtml($Page->ciudad->EditValue) ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ciudad->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->ciudad->getPlaceHolder()) ?>"<?= $Page->ciudad->editAttributes() ?> aria-describedby="x_ciudad_help">
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->ciudad->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_ciudad',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->ciudad->ReadOnly || $Page->ciudad->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="cliente" data-field="x_ciudad" data-input="sv_x_ciudad" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->ciudad->displayValueSeparatorAttribute() ?>" name="x_ciudad" id="x_ciudad" value="<?= HtmlEncode($Page->ciudad->CurrentValue) ?>"<?= $onchange ?>>
<?= $Page->ciudad->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ciudad->getErrorMessage() ?></div>
<script>
loadjs.ready(["fclienteedit"], function() {
    fclienteedit.createAutoSuggest(Object.assign({"id":"x_ciudad","forceSelect":true}, ew.vars.tables.cliente.fields.ciudad.autoSuggestOptions));
});
</script>
<?= $Page->ciudad->Lookup->getParamTag($Page, "p_x_ciudad") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <div id="r_direccion" class="form-group row">
        <label id="elh_cliente_direccion" for="x_direccion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->direccion->caption() ?><?= $Page->direccion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->direccion->cellAttributes() ?>>
<span id="el_cliente_direccion">
<textarea data-table="cliente" data-field="x_direccion" name="x_direccion" id="x_direccion" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->direccion->getPlaceHolder()) ?>"<?= $Page->direccion->editAttributes() ?> aria-describedby="x_direccion_help"><?= $Page->direccion->EditValue ?></textarea>
<?= $Page->direccion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->direccion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono1->Visible) { // telefono1 ?>
    <div id="r_telefono1" class="form-group row">
        <label id="elh_cliente_telefono1" for="x_telefono1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->telefono1->caption() ?><?= $Page->telefono1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->telefono1->cellAttributes() ?>>
<span id="el_cliente_telefono1">
<input type="<?= $Page->telefono1->getInputTextType() ?>" data-table="cliente" data-field="x_telefono1" name="x_telefono1" id="x_telefono1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono1->getPlaceHolder()) ?>" value="<?= $Page->telefono1->EditValue ?>"<?= $Page->telefono1->editAttributes() ?> aria-describedby="x_telefono1_help">
<?= $Page->telefono1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono2->Visible) { // telefono2 ?>
    <div id="r_telefono2" class="form-group row">
        <label id="elh_cliente_telefono2" for="x_telefono2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->telefono2->caption() ?><?= $Page->telefono2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->telefono2->cellAttributes() ?>>
<span id="el_cliente_telefono2">
<input type="<?= $Page->telefono2->getInputTextType() ?>" data-table="cliente" data-field="x_telefono2" name="x_telefono2" id="x_telefono2" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono2->getPlaceHolder()) ?>" value="<?= $Page->telefono2->EditValue ?>"<?= $Page->telefono2->editAttributes() ?> aria-describedby="x_telefono2_help">
<?= $Page->telefono2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->email1->Visible) { // email1 ?>
    <div id="r_email1" class="form-group row">
        <label id="elh_cliente_email1" for="x_email1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->email1->caption() ?><?= $Page->email1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->email1->cellAttributes() ?>>
<span id="el_cliente_email1">
<input type="<?= $Page->email1->getInputTextType() ?>" data-table="cliente" data-field="x_email1" name="x_email1" id="x_email1" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->email1->getPlaceHolder()) ?>" value="<?= $Page->email1->EditValue ?>"<?= $Page->email1->editAttributes() ?> aria-describedby="x_email1_help">
<?= $Page->email1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->email1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->email2->Visible) { // email2 ?>
    <div id="r_email2" class="form-group row">
        <label id="elh_cliente_email2" for="x_email2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->email2->caption() ?><?= $Page->email2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->email2->cellAttributes() ?>>
<span id="el_cliente_email2">
<input type="<?= $Page->email2->getInputTextType() ?>" data-table="cliente" data-field="x_email2" name="x_email2" id="x_email2" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->email2->getPlaceHolder()) ?>" value="<?= $Page->email2->EditValue ?>"<?= $Page->email2->editAttributes() ?> aria-describedby="x_email2_help">
<?= $Page->email2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->email2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->web->Visible) { // web ?>
    <div id="r_web" class="form-group row">
        <label id="elh_cliente_web" class="<?= $Page->LeftColumnClass ?>"><?= $Page->web->caption() ?><?= $Page->web->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->web->cellAttributes() ?>>
<span id="el_cliente_web">
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
<?= $Page->web->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->web->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
    <div id="r_tarifa" class="form-group row">
        <label id="elh_cliente_tarifa" for="x_tarifa" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tarifa->caption() ?><?= $Page->tarifa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tarifa->cellAttributes() ?>>
<span id="el_cliente_tarifa">
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
    <?= $Page->tarifa->getCustomMessage() ?>
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
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <div id="r_cuenta" class="form-group row">
        <label id="elh_cliente_cuenta" for="x_cuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta->caption() ?><?= $Page->cuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cuenta->cellAttributes() ?>>
<span id="el_cliente_cuenta">
<div class="input-group ew-lookup-list" aria-describedby="x_cuenta_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_cuenta"><?= EmptyValue(strval($Page->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->cuenta->ReadOnly || $Page->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->cuenta->getErrorMessage() ?></div>
<?= $Page->cuenta->getCustomMessage() ?>
<?= $Page->cuenta->Lookup->getParamTag($Page, "p_x_cuenta") ?>
<input type="hidden" is="selection-list" data-table="cliente" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cuenta->displayValueSeparatorAttribute() ?>" name="x_cuenta" id="x_cuenta" value="<?= $Page->cuenta->CurrentValue ?>"<?= $Page->cuenta->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo" class="form-group row">
        <label id="elh_cliente_activo" for="x_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->activo->cellAttributes() ?>>
<span id="el_cliente_activo">
    <select
        id="x_activo"
        name="x_activo"
        class="form-control ew-select<?= $Page->activo->isInvalidClass() ?>"
        data-select2-id="cliente_x_activo"
        data-table="cliente"
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
    var el = document.querySelector("select[data-select2-id='cliente_x_activo']"),
        options = { name: "x_activo", selectId: "cliente_x_activo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.cliente.fields.activo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.cliente.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->refiere->Visible) { // refiere ?>
    <div id="r_refiere" class="form-group row">
        <label id="elh_cliente_refiere" for="x_refiere" class="<?= $Page->LeftColumnClass ?>"><?= $Page->refiere->caption() ?><?= $Page->refiere->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->refiere->cellAttributes() ?>>
<span id="el_cliente_refiere">
<div class="input-group ew-lookup-list" aria-describedby="x_refiere_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_refiere"><?= EmptyValue(strval($Page->refiere->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->refiere->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->refiere->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->refiere->ReadOnly || $Page->refiere->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_refiere',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->refiere->getErrorMessage() ?></div>
<?= $Page->refiere->getCustomMessage() ?>
<?= $Page->refiere->Lookup->getParamTag($Page, "p_x_refiere") ?>
<input type="hidden" is="selection-list" data-table="cliente" data-field="x_refiere" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->refiere->displayValueSeparatorAttribute() ?>" name="x_refiere" id="x_refiere" value="<?= $Page->refiere->CurrentValue ?>"<?= $Page->refiere->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="cliente" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("adjunto", explode(",", $Page->getCurrentDetailTable())) && $adjunto->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("adjunto", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "AdjuntoGrid.php" ?>
<?php } ?>
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
    ew.addEventHandlers("cliente");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    $("#x_ci_rif").change((function(){if(""==$("#x_ci_rif").val().trim())return!0;var i={ci_rif:$("#x_ci_rif").val(),tipo:"CLIENTE",accion:"U"};$.ajax({data:i,url:"RifBuscar",type:"get",beforeSend:function(){},success:function(i){return"1"!=$(i).find("#outtext").text()||(alert('RIF / CI "'+$("#x_ci_rif").val()+'" ya existe.'),$("#x_ci_rif").val(""),$("#x_ci_rif").focus(),!1)}})}));
});
</script>
