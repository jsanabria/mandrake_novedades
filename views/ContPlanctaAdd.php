<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContPlanctaAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_planctaadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fcont_planctaadd = currentForm = new ew.Form("fcont_planctaadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_plancta")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_plancta)
        ew.vars.tables.cont_plancta = currentTable;
    fcont_planctaadd.addFields([
        ["clase", [fields.clase.visible && fields.clase.required ? ew.Validators.required(fields.clase.caption) : null], fields.clase.isInvalid],
        ["grupo", [fields.grupo.visible && fields.grupo.required ? ew.Validators.required(fields.grupo.caption) : null], fields.grupo.isInvalid],
        ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
        ["subcuenta", [fields.subcuenta.visible && fields.subcuenta.required ? ew.Validators.required(fields.subcuenta.caption) : null], fields.subcuenta.isInvalid],
        ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["activa", [fields.activa.visible && fields.activa.required ? ew.Validators.required(fields.activa.caption) : null], fields.activa.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_planctaadd,
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
    fcont_planctaadd.validate = function () {
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
    fcont_planctaadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_planctaadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_planctaadd.lists.moneda = <?= $Page->moneda->toClientList($Page) ?>;
    fcont_planctaadd.lists.activa = <?= $Page->activa->toClientList($Page) ?>;
    loadjs.done("fcont_planctaadd");
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
<form name="fcont_planctaadd" id="fcont_planctaadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_plancta">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->clase->Visible) { // clase ?>
    <div id="r_clase" class="form-group row">
        <label id="elh_cont_plancta_clase" for="x_clase" class="<?= $Page->LeftColumnClass ?>"><?= $Page->clase->caption() ?><?= $Page->clase->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->clase->cellAttributes() ?>>
<span id="el_cont_plancta_clase">
<input type="<?= $Page->clase->getInputTextType() ?>" data-table="cont_plancta" data-field="x_clase" name="x_clase" id="x_clase" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->clase->getPlaceHolder()) ?>" value="<?= $Page->clase->EditValue ?>"<?= $Page->clase->editAttributes() ?> aria-describedby="x_clase_help">
<?= $Page->clase->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->clase->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->grupo->Visible) { // grupo ?>
    <div id="r_grupo" class="form-group row">
        <label id="elh_cont_plancta_grupo" for="x_grupo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->grupo->caption() ?><?= $Page->grupo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->grupo->cellAttributes() ?>>
<span id="el_cont_plancta_grupo">
<input type="<?= $Page->grupo->getInputTextType() ?>" data-table="cont_plancta" data-field="x_grupo" name="x_grupo" id="x_grupo" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->grupo->getPlaceHolder()) ?>" value="<?= $Page->grupo->EditValue ?>"<?= $Page->grupo->editAttributes() ?> aria-describedby="x_grupo_help">
<?= $Page->grupo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->grupo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <div id="r_cuenta" class="form-group row">
        <label id="elh_cont_plancta_cuenta" for="x_cuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta->caption() ?><?= $Page->cuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cuenta->cellAttributes() ?>>
<span id="el_cont_plancta_cuenta">
<input type="<?= $Page->cuenta->getInputTextType() ?>" data-table="cont_plancta" data-field="x_cuenta" name="x_cuenta" id="x_cuenta" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->cuenta->getPlaceHolder()) ?>" value="<?= $Page->cuenta->EditValue ?>"<?= $Page->cuenta->editAttributes() ?> aria-describedby="x_cuenta_help">
<?= $Page->cuenta->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cuenta->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->subcuenta->Visible) { // subcuenta ?>
    <div id="r_subcuenta" class="form-group row">
        <label id="elh_cont_plancta_subcuenta" for="x_subcuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->subcuenta->caption() ?><?= $Page->subcuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->subcuenta->cellAttributes() ?>>
<span id="el_cont_plancta_subcuenta">
<input type="<?= $Page->subcuenta->getInputTextType() ?>" data-table="cont_plancta" data-field="x_subcuenta" name="x_subcuenta" id="x_subcuenta" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->subcuenta->getPlaceHolder()) ?>" value="<?= $Page->subcuenta->EditValue ?>"<?= $Page->subcuenta->editAttributes() ?> aria-describedby="x_subcuenta_help">
<?= $Page->subcuenta->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->subcuenta->getErrorMessage() ?></div>
</span>
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
    ew.addEventHandlers("cont_plancta");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    $("#x_clase").change((function(){if(""==$("#x_clase").val().trim())return!0;$("#x_grupo").val(""),$("#x_cuenta").val(""),$("#x_subcuenta").val("");var a={clase:$("#x_clase").val(),segmento:1};$.ajax({data:a,url:"include/buscar_cuenta.php",type:"get",beforeSend:function(){},success:function(a){var e=a;$("#cuenta").html(e)}})})),$("#x_grupo").change((function(){if(""==$("#x_clase").val().trim()||""==$("#x_grupo").val().trim())return $("#x_grupo").val(""),!0;$("#x_cuenta").val(""),$("#x_subcuenta").val("");var a={clase:$("#x_clase").val(),grupo:$("#x_grupo").val(),segmento:2};$.ajax({data:a,url:"include/buscar_cuenta.php",type:"get",beforeSend:function(){},success:function(a){var e=a;$("#cuenta").html(e)}})})),$("#x_cuenta").change((function(){if(""==$("#x_clase").val().trim()||""==$("#x_grupo").val().trim()||""==$("#x_cuenta").val().trim())return $("#x_cuenta").val(""),!0;$("#x_subcuenta").val("");var a={clase:$("#x_clase").val(),grupo:$("#x_grupo").val(),cuenta:$("#x_cuenta").val(),segmento:3};$.ajax({data:a,url:"include/buscar_cuenta.php",type:"get",beforeSend:function(){},success:function(a){var e=a;$("#cuenta").html(e)}})})),$("#x_subcuenta").change((function(){if(""==$("#x_clase").val().trim()||""==$("#x_grupo").val().trim()||""==$("#x_cuenta").val().trim()||""==$("#x_subcuenta").val().trim())return $("#x_subcuenta").val(""),!0;var a={clase:$("#x_clase").val(),grupo:$("#x_grupo").val(),cuenta:$("#x_cuenta").val(),subcuenta:$("#x_subcuenta").val(),segmento:4};$.ajax({data:a,url:"include/buscar_cuenta.php",type:"get",beforeSend:function(){},success:function(a){var e=a;$("#cuenta").html(e)}})}));
});
</script>
