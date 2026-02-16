<?php

namespace PHPMaker2021\mandrake;

// Page object
$ProveedorAddopt = &$Page;
?>
<script>
var currentForm, currentPageID;
var fproveedoraddopt;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "addopt";
    fproveedoraddopt = currentForm = new ew.Form("fproveedoraddopt", "addopt");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "proveedor")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.proveedor)
        ew.vars.tables.proveedor = currentTable;
    fproveedoraddopt.addFields([
        ["ci_rif", [fields.ci_rif.visible && fields.ci_rif.required ? ew.Validators.required(fields.ci_rif.caption) : null], fields.ci_rif.isInvalid],
        ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
        ["direccion", [fields.direccion.visible && fields.direccion.required ? ew.Validators.required(fields.direccion.caption) : null], fields.direccion.isInvalid],
        ["telefono1", [fields.telefono1.visible && fields.telefono1.required ? ew.Validators.required(fields.telefono1.caption) : null], fields.telefono1.isInvalid],
        ["cta_bco", [fields.cta_bco.visible && fields.cta_bco.required ? ew.Validators.required(fields.cta_bco.caption) : null], fields.cta_bco.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fproveedoraddopt,
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
    fproveedoraddopt.validate = function () {
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
    fproveedoraddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fproveedoraddopt.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fproveedoraddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fproveedoraddopt" id="fproveedoraddopt" class="ew-form ew-horizontal" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="proveedor">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_ci_rif"><?= $Page->ci_rif->caption() ?><?= $Page->ci_rif->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->ci_rif->getInputTextType() ?>" data-table="proveedor" data-field="x_ci_rif" name="x_ci_rif" id="x_ci_rif" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ci_rif->getPlaceHolder()) ?>" value="<?= $Page->ci_rif->EditValue ?>"<?= $Page->ci_rif->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ci_rif->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_nombre"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->nombre->getInputTextType() ?>" data-table="proveedor" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" value="<?= $Page->nombre->EditValue ?>"<?= $Page->nombre->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_direccion"><?= $Page->direccion->caption() ?><?= $Page->direccion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<textarea data-table="proveedor" data-field="x_direccion" name="x_direccion" id="x_direccion" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->direccion->getPlaceHolder()) ?>"<?= $Page->direccion->editAttributes() ?>><?= $Page->direccion->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Page->direccion->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->telefono1->Visible) { // telefono1 ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_telefono1"><?= $Page->telefono1->caption() ?><?= $Page->telefono1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->telefono1->getInputTextType() ?>" data-table="proveedor" data-field="x_telefono1" name="x_telefono1" id="x_telefono1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono1->getPlaceHolder()) ?>" value="<?= $Page->telefono1->EditValue ?>"<?= $Page->telefono1->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->telefono1->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->cta_bco->Visible) { // cta_bco ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_cta_bco"><?= $Page->cta_bco->caption() ?><?= $Page->cta_bco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->cta_bco->getInputTextType() ?>" data-table="proveedor" data-field="x_cta_bco" name="x_cta_bco" id="x_cta_bco" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->cta_bco->getPlaceHolder()) ?>" value="<?= $Page->cta_bco->EditValue ?>"<?= $Page->cta_bco->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cta_bco->getErrorMessage() ?></div>
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
    ew.addEventHandlers("proveedor");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
