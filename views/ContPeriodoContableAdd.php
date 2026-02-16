<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContPeriodoContableAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_periodo_contableadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fcont_periodo_contableadd = currentForm = new ew.Form("fcont_periodo_contableadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_periodo_contable")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_periodo_contable)
        ew.vars.tables.cont_periodo_contable = currentTable;
    fcont_periodo_contableadd.addFields([
        ["fecha_inicio", [fields.fecha_inicio.visible && fields.fecha_inicio.required ? ew.Validators.required(fields.fecha_inicio.caption) : null, ew.Validators.datetime(7)], fields.fecha_inicio.isInvalid],
        ["fecha_fin", [fields.fecha_fin.visible && fields.fecha_fin.required ? ew.Validators.required(fields.fecha_fin.caption) : null, ew.Validators.datetime(7)], fields.fecha_fin.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_periodo_contableadd,
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
    fcont_periodo_contableadd.validate = function () {
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
    fcont_periodo_contableadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_periodo_contableadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fcont_periodo_contableadd");
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
<form name="fcont_periodo_contableadd" id="fcont_periodo_contableadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_periodo_contable">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->fecha_inicio->Visible) { // fecha_inicio ?>
    <div id="r_fecha_inicio" class="form-group row">
        <label id="elh_cont_periodo_contable_fecha_inicio" for="x_fecha_inicio" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_inicio->caption() ?><?= $Page->fecha_inicio->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha_inicio->cellAttributes() ?>>
<span id="el_cont_periodo_contable_fecha_inicio">
<input type="<?= $Page->fecha_inicio->getInputTextType() ?>" data-table="cont_periodo_contable" data-field="x_fecha_inicio" data-format="7" name="x_fecha_inicio" id="x_fecha_inicio" placeholder="<?= HtmlEncode($Page->fecha_inicio->getPlaceHolder()) ?>" value="<?= $Page->fecha_inicio->EditValue ?>"<?= $Page->fecha_inicio->editAttributes() ?> aria-describedby="x_fecha_inicio_help">
<?= $Page->fecha_inicio->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha_inicio->getErrorMessage() ?></div>
<?php if (!$Page->fecha_inicio->ReadOnly && !$Page->fecha_inicio->Disabled && !isset($Page->fecha_inicio->EditAttrs["readonly"]) && !isset($Page->fecha_inicio->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcont_periodo_contableadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fcont_periodo_contableadd", "x_fecha_inicio", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_fin->Visible) { // fecha_fin ?>
    <div id="r_fecha_fin" class="form-group row">
        <label id="elh_cont_periodo_contable_fecha_fin" for="x_fecha_fin" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_fin->caption() ?><?= $Page->fecha_fin->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha_fin->cellAttributes() ?>>
<span id="el_cont_periodo_contable_fecha_fin">
<input type="<?= $Page->fecha_fin->getInputTextType() ?>" data-table="cont_periodo_contable" data-field="x_fecha_fin" data-format="7" name="x_fecha_fin" id="x_fecha_fin" placeholder="<?= HtmlEncode($Page->fecha_fin->getPlaceHolder()) ?>" value="<?= $Page->fecha_fin->EditValue ?>"<?= $Page->fecha_fin->editAttributes() ?> aria-describedby="x_fecha_fin_help">
<?= $Page->fecha_fin->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha_fin->getErrorMessage() ?></div>
<?php if (!$Page->fecha_fin->ReadOnly && !$Page->fecha_fin->Disabled && !isset($Page->fecha_fin->EditAttrs["readonly"]) && !isset($Page->fecha_fin->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcont_periodo_contableadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fcont_periodo_contableadd", "x_fecha_fin", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
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
    ew.addEventHandlers("cont_periodo_contable");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
