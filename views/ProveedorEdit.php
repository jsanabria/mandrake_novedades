<?php

namespace PHPMaker2021\mandrake;

// Page object
$ProveedorEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fproveedoredit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fproveedoredit = currentForm = new ew.Form("fproveedoredit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "proveedor")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.proveedor)
        ew.vars.tables.proveedor = currentTable;
    fproveedoredit.addFields([
        ["ci_rif", [fields.ci_rif.visible && fields.ci_rif.required ? ew.Validators.required(fields.ci_rif.caption) : null], fields.ci_rif.isInvalid],
        ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
        ["ciudad", [fields.ciudad.visible && fields.ciudad.required ? ew.Validators.required(fields.ciudad.caption) : null], fields.ciudad.isInvalid],
        ["direccion", [fields.direccion.visible && fields.direccion.required ? ew.Validators.required(fields.direccion.caption) : null], fields.direccion.isInvalid],
        ["telefono1", [fields.telefono1.visible && fields.telefono1.required ? ew.Validators.required(fields.telefono1.caption) : null], fields.telefono1.isInvalid],
        ["telefono2", [fields.telefono2.visible && fields.telefono2.required ? ew.Validators.required(fields.telefono2.caption) : null], fields.telefono2.isInvalid],
        ["email1", [fields.email1.visible && fields.email1.required ? ew.Validators.required(fields.email1.caption) : null, ew.Validators.email], fields.email1.isInvalid],
        ["email2", [fields.email2.visible && fields.email2.required ? ew.Validators.required(fields.email2.caption) : null, ew.Validators.email], fields.email2.isInvalid],
        ["cta_bco", [fields.cta_bco.visible && fields.cta_bco.required ? ew.Validators.required(fields.cta_bco.caption) : null], fields.cta_bco.isInvalid],
        ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fproveedoredit,
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
    fproveedoredit.validate = function () {
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
    fproveedoredit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fproveedoredit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fproveedoredit.lists.ciudad = <?= $Page->ciudad->toClientList($Page) ?>;
    fproveedoredit.lists.activo = <?= $Page->activo->toClientList($Page) ?>;
    loadjs.done("fproveedoredit");
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
<form name="fproveedoredit" id="fproveedoredit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="proveedor">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <div id="r_ci_rif" class="form-group row">
        <label id="elh_proveedor_ci_rif" for="x_ci_rif" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ci_rif->caption() ?><?= $Page->ci_rif->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ci_rif->cellAttributes() ?>>
<span id="el_proveedor_ci_rif">
<input type="<?= $Page->ci_rif->getInputTextType() ?>" data-table="proveedor" data-field="x_ci_rif" name="x_ci_rif" id="x_ci_rif" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ci_rif->getPlaceHolder()) ?>" value="<?= $Page->ci_rif->EditValue ?>"<?= $Page->ci_rif->editAttributes() ?> aria-describedby="x_ci_rif_help">
<?= $Page->ci_rif->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ci_rif->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre" class="form-group row">
        <label id="elh_proveedor_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nombre->cellAttributes() ?>>
<span id="el_proveedor_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" data-table="proveedor" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" value="<?= $Page->nombre->EditValue ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
    <div id="r_ciudad" class="form-group row">
        <label id="elh_proveedor_ciudad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ciudad->caption() ?><?= $Page->ciudad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ciudad->cellAttributes() ?>>
<span id="el_proveedor_ciudad">
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
<input type="hidden" is="selection-list" class="form-control" data-table="proveedor" data-field="x_ciudad" data-input="sv_x_ciudad" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->ciudad->displayValueSeparatorAttribute() ?>" name="x_ciudad" id="x_ciudad" value="<?= HtmlEncode($Page->ciudad->CurrentValue) ?>"<?= $onchange ?>>
<?= $Page->ciudad->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ciudad->getErrorMessage() ?></div>
<script>
loadjs.ready(["fproveedoredit"], function() {
    fproveedoredit.createAutoSuggest(Object.assign({"id":"x_ciudad","forceSelect":true}, ew.vars.tables.proveedor.fields.ciudad.autoSuggestOptions));
});
</script>
<?= $Page->ciudad->Lookup->getParamTag($Page, "p_x_ciudad") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <div id="r_direccion" class="form-group row">
        <label id="elh_proveedor_direccion" for="x_direccion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->direccion->caption() ?><?= $Page->direccion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->direccion->cellAttributes() ?>>
<span id="el_proveedor_direccion">
<textarea data-table="proveedor" data-field="x_direccion" name="x_direccion" id="x_direccion" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->direccion->getPlaceHolder()) ?>"<?= $Page->direccion->editAttributes() ?> aria-describedby="x_direccion_help"><?= $Page->direccion->EditValue ?></textarea>
<?= $Page->direccion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->direccion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono1->Visible) { // telefono1 ?>
    <div id="r_telefono1" class="form-group row">
        <label id="elh_proveedor_telefono1" for="x_telefono1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->telefono1->caption() ?><?= $Page->telefono1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->telefono1->cellAttributes() ?>>
<span id="el_proveedor_telefono1">
<input type="<?= $Page->telefono1->getInputTextType() ?>" data-table="proveedor" data-field="x_telefono1" name="x_telefono1" id="x_telefono1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono1->getPlaceHolder()) ?>" value="<?= $Page->telefono1->EditValue ?>"<?= $Page->telefono1->editAttributes() ?> aria-describedby="x_telefono1_help">
<?= $Page->telefono1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono2->Visible) { // telefono2 ?>
    <div id="r_telefono2" class="form-group row">
        <label id="elh_proveedor_telefono2" for="x_telefono2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->telefono2->caption() ?><?= $Page->telefono2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->telefono2->cellAttributes() ?>>
<span id="el_proveedor_telefono2">
<input type="<?= $Page->telefono2->getInputTextType() ?>" data-table="proveedor" data-field="x_telefono2" name="x_telefono2" id="x_telefono2" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono2->getPlaceHolder()) ?>" value="<?= $Page->telefono2->EditValue ?>"<?= $Page->telefono2->editAttributes() ?> aria-describedby="x_telefono2_help">
<?= $Page->telefono2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->email1->Visible) { // email1 ?>
    <div id="r_email1" class="form-group row">
        <label id="elh_proveedor_email1" for="x_email1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->email1->caption() ?><?= $Page->email1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->email1->cellAttributes() ?>>
<span id="el_proveedor_email1">
<input type="<?= $Page->email1->getInputTextType() ?>" data-table="proveedor" data-field="x_email1" name="x_email1" id="x_email1" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->email1->getPlaceHolder()) ?>" value="<?= $Page->email1->EditValue ?>"<?= $Page->email1->editAttributes() ?> aria-describedby="x_email1_help">
<?= $Page->email1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->email1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->email2->Visible) { // email2 ?>
    <div id="r_email2" class="form-group row">
        <label id="elh_proveedor_email2" for="x_email2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->email2->caption() ?><?= $Page->email2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->email2->cellAttributes() ?>>
<span id="el_proveedor_email2">
<input type="<?= $Page->email2->getInputTextType() ?>" data-table="proveedor" data-field="x_email2" name="x_email2" id="x_email2" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->email2->getPlaceHolder()) ?>" value="<?= $Page->email2->EditValue ?>"<?= $Page->email2->editAttributes() ?> aria-describedby="x_email2_help">
<?= $Page->email2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->email2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cta_bco->Visible) { // cta_bco ?>
    <div id="r_cta_bco" class="form-group row">
        <label id="elh_proveedor_cta_bco" for="x_cta_bco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cta_bco->caption() ?><?= $Page->cta_bco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cta_bco->cellAttributes() ?>>
<span id="el_proveedor_cta_bco">
<input type="<?= $Page->cta_bco->getInputTextType() ?>" data-table="proveedor" data-field="x_cta_bco" name="x_cta_bco" id="x_cta_bco" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->cta_bco->getPlaceHolder()) ?>" value="<?= $Page->cta_bco->EditValue ?>"<?= $Page->cta_bco->editAttributes() ?> aria-describedby="x_cta_bco_help">
<?= $Page->cta_bco->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cta_bco->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo" class="form-group row">
        <label id="elh_proveedor_activo" for="x_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->activo->cellAttributes() ?>>
<span id="el_proveedor_activo">
    <select
        id="x_activo"
        name="x_activo"
        class="form-control ew-select<?= $Page->activo->isInvalidClass() ?>"
        data-select2-id="proveedor_x_activo"
        data-table="proveedor"
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
    var el = document.querySelector("select[data-select2-id='proveedor_x_activo']"),
        options = { name: "x_activo", selectId: "proveedor_x_activo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.proveedor.fields.activo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.proveedor.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="proveedor" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("proveedor_articulo", explode(",", $Page->getCurrentDetailTable())) && $proveedor_articulo->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("proveedor_articulo", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ProveedorArticuloGrid.php" ?>
<?php } ?>
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
    ew.addEventHandlers("proveedor");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    $("#x_telefono1").mask("(9999) 999-99-99"),$("#x_telefono2").mask("(9999) 999-99-99"),$("#x_ci_rif").change((function(){if(""==$("#x_ci_rif").val().trim())return!0;var i={ci_rif:$("#x_ci_rif").val(),tipo:"CLIENTE",accion:"U"};$.ajax({data:i,url:"rif_buscar.php",type:"get",beforeSend:function(){},success:function(i){return"1"!=$(i).find("#outtext").text()||(alert('RIF / CI "'+$("#x_ci_rif").val()+'" ya existe.'),$("#x_ci_rif").val(""),$("#x_ci_rif").focus(),!1)}})}));
});
</script>
