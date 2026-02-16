<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContPeriodoContableEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_periodo_contableedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fcont_periodo_contableedit = currentForm = new ew.Form("fcont_periodo_contableedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_periodo_contable")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_periodo_contable)
        ew.vars.tables.cont_periodo_contable = currentTable;
    fcont_periodo_contableedit.addFields([
        ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
        ["fecha_inicio", [fields.fecha_inicio.visible && fields.fecha_inicio.required ? ew.Validators.required(fields.fecha_inicio.caption) : null], fields.fecha_inicio.isInvalid],
        ["fecha_fin", [fields.fecha_fin.visible && fields.fecha_fin.required ? ew.Validators.required(fields.fecha_fin.caption) : null], fields.fecha_fin.isInvalid],
        ["cerrado", [fields.cerrado.visible && fields.cerrado.required ? ew.Validators.required(fields.cerrado.caption) : null], fields.cerrado.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_periodo_contableedit,
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
    fcont_periodo_contableedit.validate = function () {
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
    fcont_periodo_contableedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_periodo_contableedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_periodo_contableedit.lists.cerrado = <?= $Page->cerrado->toClientList($Page) ?>;
    loadjs.done("fcont_periodo_contableedit");
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
<form name="fcont_periodo_contableedit" id="fcont_periodo_contableedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_periodo_contable">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id" class="form-group row">
        <label id="elh_cont_periodo_contable_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id->cellAttributes() ?>>
<span id="el_cont_periodo_contable_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_periodo_contable" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_inicio->Visible) { // fecha_inicio ?>
    <div id="r_fecha_inicio" class="form-group row">
        <label id="elh_cont_periodo_contable_fecha_inicio" for="x_fecha_inicio" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_inicio->caption() ?><?= $Page->fecha_inicio->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha_inicio->cellAttributes() ?>>
<span id="el_cont_periodo_contable_fecha_inicio">
<span<?= $Page->fecha_inicio->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha_inicio->getDisplayValue($Page->fecha_inicio->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_periodo_contable" data-field="x_fecha_inicio" data-hidden="1" name="x_fecha_inicio" id="x_fecha_inicio" value="<?= HtmlEncode($Page->fecha_inicio->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_fin->Visible) { // fecha_fin ?>
    <div id="r_fecha_fin" class="form-group row">
        <label id="elh_cont_periodo_contable_fecha_fin" for="x_fecha_fin" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_fin->caption() ?><?= $Page->fecha_fin->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha_fin->cellAttributes() ?>>
<span id="el_cont_periodo_contable_fecha_fin">
<span<?= $Page->fecha_fin->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha_fin->getDisplayValue($Page->fecha_fin->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_periodo_contable" data-field="x_fecha_fin" data-hidden="1" name="x_fecha_fin" id="x_fecha_fin" value="<?= HtmlEncode($Page->fecha_fin->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cerrado->Visible) { // cerrado ?>
    <div id="r_cerrado" class="form-group row">
        <label id="elh_cont_periodo_contable_cerrado" for="x_cerrado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cerrado->caption() ?><?= $Page->cerrado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cerrado->cellAttributes() ?>>
<span id="el_cont_periodo_contable_cerrado">
    <select
        id="x_cerrado"
        name="x_cerrado"
        class="form-control ew-select<?= $Page->cerrado->isInvalidClass() ?>"
        data-select2-id="cont_periodo_contable_x_cerrado"
        data-table="cont_periodo_contable"
        data-field="x_cerrado"
        data-value-separator="<?= $Page->cerrado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cerrado->getPlaceHolder()) ?>"
        <?= $Page->cerrado->editAttributes() ?>>
        <?= $Page->cerrado->selectOptionListHtml("x_cerrado") ?>
    </select>
    <?= $Page->cerrado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->cerrado->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='cont_periodo_contable_x_cerrado']"),
        options = { name: "x_cerrado", selectId: "cont_periodo_contable_x_cerrado", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.cont_periodo_contable.fields.cerrado.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.cont_periodo_contable.fields.cerrado.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
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
    ew.addEventHandlers("cont_periodo_contable");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
