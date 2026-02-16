<?php

namespace PHPMaker2021\mandrake;

// Page object
$TablaRetencionesAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var ftabla_retencionesadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    ftabla_retencionesadd = currentForm = new ew.Form("ftabla_retencionesadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "tabla_retenciones")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.tabla_retenciones)
        ew.vars.tables.tabla_retenciones = currentTable;
    ftabla_retencionesadd.addFields([
        ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
        ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
        ["base_imponible", [fields.base_imponible.visible && fields.base_imponible.required ? ew.Validators.required(fields.base_imponible.caption) : null, ew.Validators.float], fields.base_imponible.isInvalid],
        ["tarifa", [fields.tarifa.visible && fields.tarifa.required ? ew.Validators.required(fields.tarifa.caption) : null, ew.Validators.float], fields.tarifa.isInvalid],
        ["sustraendo", [fields.sustraendo.visible && fields.sustraendo.required ? ew.Validators.required(fields.sustraendo.caption) : null, ew.Validators.float], fields.sustraendo.isInvalid],
        ["pagos_mayores", [fields.pagos_mayores.visible && fields.pagos_mayores.required ? ew.Validators.required(fields.pagos_mayores.caption) : null, ew.Validators.float], fields.pagos_mayores.isInvalid],
        ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = ftabla_retencionesadd,
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
    ftabla_retencionesadd.validate = function () {
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
    ftabla_retencionesadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ftabla_retencionesadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    ftabla_retencionesadd.lists.codigo = <?= $Page->codigo->toClientList($Page) ?>;
    ftabla_retencionesadd.lists.tipo = <?= $Page->tipo->toClientList($Page) ?>;
    ftabla_retencionesadd.lists.activo = <?= $Page->activo->toClientList($Page) ?>;
    loadjs.done("ftabla_retencionesadd");
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
<form name="ftabla_retencionesadd" id="ftabla_retencionesadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tabla_retenciones">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo" class="form-group row">
        <label id="elh_tabla_retenciones_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->codigo->cellAttributes() ?>>
<span id="el_tabla_retenciones_codigo">
<div class="input-group ew-lookup-list" aria-describedby="x_codigo_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_codigo"><?= EmptyValue(strval($Page->codigo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->codigo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->codigo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->codigo->ReadOnly || $Page->codigo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_codigo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->codigo->getErrorMessage() ?></div>
<?= $Page->codigo->getCustomMessage() ?>
<?= $Page->codigo->Lookup->getParamTag($Page, "p_x_codigo") ?>
<input type="hidden" is="selection-list" data-table="tabla_retenciones" data-field="x_codigo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->codigo->displayValueSeparatorAttribute() ?>" name="x_codigo" id="x_codigo" value="<?= $Page->codigo->CurrentValue ?>"<?= $Page->codigo->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <div id="r_tipo" class="form-group row">
        <label id="elh_tabla_retenciones_tipo" for="x_tipo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo->caption() ?><?= $Page->tipo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo->cellAttributes() ?>>
<span id="el_tabla_retenciones_tipo">
    <select
        id="x_tipo"
        name="x_tipo"
        class="form-control ew-select<?= $Page->tipo->isInvalidClass() ?>"
        data-select2-id="tabla_retenciones_x_tipo"
        data-table="tabla_retenciones"
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
    var el = document.querySelector("select[data-select2-id='tabla_retenciones_x_tipo']"),
        options = { name: "x_tipo", selectId: "tabla_retenciones_x_tipo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.tabla_retenciones.fields.tipo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.tabla_retenciones.fields.tipo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->base_imponible->Visible) { // base_imponible ?>
    <div id="r_base_imponible" class="form-group row">
        <label id="elh_tabla_retenciones_base_imponible" for="x_base_imponible" class="<?= $Page->LeftColumnClass ?>"><?= $Page->base_imponible->caption() ?><?= $Page->base_imponible->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->base_imponible->cellAttributes() ?>>
<span id="el_tabla_retenciones_base_imponible">
<input type="<?= $Page->base_imponible->getInputTextType() ?>" data-table="tabla_retenciones" data-field="x_base_imponible" name="x_base_imponible" id="x_base_imponible" size="10" maxlength="12" placeholder="<?= HtmlEncode($Page->base_imponible->getPlaceHolder()) ?>" value="<?= $Page->base_imponible->EditValue ?>"<?= $Page->base_imponible->editAttributes() ?> aria-describedby="x_base_imponible_help">
<?= $Page->base_imponible->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->base_imponible->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
    <div id="r_tarifa" class="form-group row">
        <label id="elh_tabla_retenciones_tarifa" for="x_tarifa" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tarifa->caption() ?><?= $Page->tarifa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tarifa->cellAttributes() ?>>
<span id="el_tabla_retenciones_tarifa">
<input type="<?= $Page->tarifa->getInputTextType() ?>" data-table="tabla_retenciones" data-field="x_tarifa" name="x_tarifa" id="x_tarifa" size="10" maxlength="12" placeholder="<?= HtmlEncode($Page->tarifa->getPlaceHolder()) ?>" value="<?= $Page->tarifa->EditValue ?>"<?= $Page->tarifa->editAttributes() ?> aria-describedby="x_tarifa_help">
<?= $Page->tarifa->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tarifa->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sustraendo->Visible) { // sustraendo ?>
    <div id="r_sustraendo" class="form-group row">
        <label id="elh_tabla_retenciones_sustraendo" for="x_sustraendo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sustraendo->caption() ?><?= $Page->sustraendo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->sustraendo->cellAttributes() ?>>
<span id="el_tabla_retenciones_sustraendo">
<input type="<?= $Page->sustraendo->getInputTextType() ?>" data-table="tabla_retenciones" data-field="x_sustraendo" name="x_sustraendo" id="x_sustraendo" size="10" maxlength="12" placeholder="<?= HtmlEncode($Page->sustraendo->getPlaceHolder()) ?>" value="<?= $Page->sustraendo->EditValue ?>"<?= $Page->sustraendo->editAttributes() ?> aria-describedby="x_sustraendo_help">
<?= $Page->sustraendo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sustraendo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pagos_mayores->Visible) { // pagos_mayores ?>
    <div id="r_pagos_mayores" class="form-group row">
        <label id="elh_tabla_retenciones_pagos_mayores" for="x_pagos_mayores" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pagos_mayores->caption() ?><?= $Page->pagos_mayores->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pagos_mayores->cellAttributes() ?>>
<span id="el_tabla_retenciones_pagos_mayores">
<input type="<?= $Page->pagos_mayores->getInputTextType() ?>" data-table="tabla_retenciones" data-field="x_pagos_mayores" name="x_pagos_mayores" id="x_pagos_mayores" size="10" maxlength="12" placeholder="<?= HtmlEncode($Page->pagos_mayores->getPlaceHolder()) ?>" value="<?= $Page->pagos_mayores->EditValue ?>"<?= $Page->pagos_mayores->editAttributes() ?> aria-describedby="x_pagos_mayores_help">
<?= $Page->pagos_mayores->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pagos_mayores->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo" class="form-group row">
        <label id="elh_tabla_retenciones_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->activo->cellAttributes() ?>>
<span id="el_tabla_retenciones_activo">
<template id="tp_x_activo">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="tabla_retenciones" data-field="x_activo" name="x_activo" id="x_activo"<?= $Page->activo->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_activo" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_activo"
    name="x_activo"
    value="<?= HtmlEncode($Page->activo->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_activo"
    data-target="dsl_x_activo"
    data-repeatcolumn="5"
    class="form-control<?= $Page->activo->isInvalidClass() ?>"
    data-table="tabla_retenciones"
    data-field="x_activo"
    data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
    <?= $Page->activo->editAttributes() ?>>
<?= $Page->activo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->activo->getErrorMessage() ?></div>
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
    ew.addEventHandlers("tabla_retenciones");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
