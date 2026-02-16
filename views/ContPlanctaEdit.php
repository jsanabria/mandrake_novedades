<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContPlanctaEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_planctaedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fcont_planctaedit = currentForm = new ew.Form("fcont_planctaedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_plancta")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_plancta)
        ew.vars.tables.cont_plancta = currentTable;
    fcont_planctaedit.addFields([
        ["clase", [fields.clase.visible && fields.clase.required ? ew.Validators.required(fields.clase.caption) : null], fields.clase.isInvalid],
        ["grupo", [fields.grupo.visible && fields.grupo.required ? ew.Validators.required(fields.grupo.caption) : null], fields.grupo.isInvalid],
        ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
        ["subcuenta", [fields.subcuenta.visible && fields.subcuenta.required ? ew.Validators.required(fields.subcuenta.caption) : null], fields.subcuenta.isInvalid],
        ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
        ["clasificacion", [fields.clasificacion.visible && fields.clasificacion.required ? ew.Validators.required(fields.clasificacion.caption) : null], fields.clasificacion.isInvalid],
        ["naturaleza", [fields.naturaleza.visible && fields.naturaleza.required ? ew.Validators.required(fields.naturaleza.caption) : null], fields.naturaleza.isInvalid],
        ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["activa", [fields.activa.visible && fields.activa.required ? ew.Validators.required(fields.activa.caption) : null], fields.activa.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_planctaedit,
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
    fcont_planctaedit.validate = function () {
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
    fcont_planctaedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_planctaedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_planctaedit.lists.moneda = <?= $Page->moneda->toClientList($Page) ?>;
    fcont_planctaedit.lists.activa = <?= $Page->activa->toClientList($Page) ?>;
    loadjs.done("fcont_planctaedit");
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
<form name="fcont_planctaedit" id="fcont_planctaedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_plancta">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->clase->Visible) { // clase ?>
    <div id="r_clase" class="form-group row">
        <label id="elh_cont_plancta_clase" for="x_clase" class="<?= $Page->LeftColumnClass ?>"><?= $Page->clase->caption() ?><?= $Page->clase->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->clase->cellAttributes() ?>>
<span id="el_cont_plancta_clase">
<span<?= $Page->clase->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->clase->getDisplayValue($Page->clase->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_plancta" data-field="x_clase" data-hidden="1" name="x_clase" id="x_clase" value="<?= HtmlEncode($Page->clase->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->grupo->Visible) { // grupo ?>
    <div id="r_grupo" class="form-group row">
        <label id="elh_cont_plancta_grupo" for="x_grupo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->grupo->caption() ?><?= $Page->grupo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->grupo->cellAttributes() ?>>
<span id="el_cont_plancta_grupo">
<span<?= $Page->grupo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->grupo->getDisplayValue($Page->grupo->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_plancta" data-field="x_grupo" data-hidden="1" name="x_grupo" id="x_grupo" value="<?= HtmlEncode($Page->grupo->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <div id="r_cuenta" class="form-group row">
        <label id="elh_cont_plancta_cuenta" for="x_cuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta->caption() ?><?= $Page->cuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cuenta->cellAttributes() ?>>
<span id="el_cont_plancta_cuenta">
<span<?= $Page->cuenta->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->cuenta->getDisplayValue($Page->cuenta->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_plancta" data-field="x_cuenta" data-hidden="1" name="x_cuenta" id="x_cuenta" value="<?= HtmlEncode($Page->cuenta->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->subcuenta->Visible) { // subcuenta ?>
    <div id="r_subcuenta" class="form-group row">
        <label id="elh_cont_plancta_subcuenta" for="x_subcuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->subcuenta->caption() ?><?= $Page->subcuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->subcuenta->cellAttributes() ?>>
<span id="el_cont_plancta_subcuenta">
<span<?= $Page->subcuenta->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->subcuenta->getDisplayValue($Page->subcuenta->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_plancta" data-field="x_subcuenta" data-hidden="1" name="x_subcuenta" id="x_subcuenta" value="<?= HtmlEncode($Page->subcuenta->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion" class="form-group row">
        <label id="elh_cont_plancta_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_plancta_descripcion">
<input type="<?= $Page->descripcion->getInputTextType() ?>" data-table="cont_plancta" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>" value="<?= $Page->descripcion->EditValue ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help">
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->clasificacion->Visible) { // clasificacion ?>
    <div id="r_clasificacion" class="form-group row">
        <label id="elh_cont_plancta_clasificacion" for="x_clasificacion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->clasificacion->caption() ?><?= $Page->clasificacion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->clasificacion->cellAttributes() ?>>
<span id="el_cont_plancta_clasificacion">
<span<?= $Page->clasificacion->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->clasificacion->getDisplayValue($Page->clasificacion->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_plancta" data-field="x_clasificacion" data-hidden="1" name="x_clasificacion" id="x_clasificacion" value="<?= HtmlEncode($Page->clasificacion->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->naturaleza->Visible) { // naturaleza ?>
    <div id="r_naturaleza" class="form-group row">
        <label id="elh_cont_plancta_naturaleza" class="<?= $Page->LeftColumnClass ?>"><?= $Page->naturaleza->caption() ?><?= $Page->naturaleza->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->naturaleza->cellAttributes() ?>>
<span id="el_cont_plancta_naturaleza">
<span<?= $Page->naturaleza->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->naturaleza->getDisplayValue($Page->naturaleza->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_plancta" data-field="x_naturaleza" data-hidden="1" name="x_naturaleza" id="x_naturaleza" value="<?= HtmlEncode($Page->naturaleza->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <div id="r_tipo" class="form-group row">
        <label id="elh_cont_plancta_tipo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo->caption() ?><?= $Page->tipo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo->cellAttributes() ?>>
<span id="el_cont_plancta_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tipo->getDisplayValue($Page->tipo->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_plancta" data-field="x_tipo" data-hidden="1" name="x_tipo" id="x_tipo" value="<?= HtmlEncode($Page->tipo->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda" class="form-group row">
        <label id="elh_cont_plancta_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->moneda->cellAttributes() ?>>
<span id="el_cont_plancta_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-control ew-select<?= $Page->moneda->isInvalidClass() ?>"
        data-select2-id="cont_plancta_x_moneda"
        data-table="cont_plancta"
        data-field="x_moneda"
        data-value-separator="<?= $Page->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>"
        <?= $Page->moneda->editAttributes() ?>>
        <?= $Page->moneda->selectOptionListHtml("x_moneda") ?>
    </select>
    <?= $Page->moneda->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
<?= $Page->moneda->Lookup->getParamTag($Page, "p_x_moneda") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='cont_plancta_x_moneda']"),
        options = { name: "x_moneda", selectId: "cont_plancta_x_moneda", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.cont_plancta.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activa->Visible) { // activa ?>
    <div id="r_activa" class="form-group row">
        <label id="elh_cont_plancta_activa" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activa->caption() ?><?= $Page->activa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->activa->cellAttributes() ?>>
<span id="el_cont_plancta_activa">
<template id="tp_x_activa">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_plancta" data-field="x_activa" name="x_activa" id="x_activa"<?= $Page->activa->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_activa" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_activa"
    name="x_activa"
    value="<?= HtmlEncode($Page->activa->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_activa"
    data-target="dsl_x_activa"
    data-repeatcolumn="5"
    class="form-control<?= $Page->activa->isInvalidClass() ?>"
    data-table="cont_plancta"
    data-field="x_activa"
    data-value-separator="<?= $Page->activa->displayValueSeparatorAttribute() ?>"
    <?= $Page->activa->editAttributes() ?>>
<?= $Page->activa->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->activa->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="cont_plancta" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
    ew.addEventHandlers("cont_plancta");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
