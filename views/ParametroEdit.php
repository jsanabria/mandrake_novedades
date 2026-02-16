<?php

namespace PHPMaker2021\mandrake;

// Page object
$ParametroEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fparametroedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fparametroedit = currentForm = new ew.Form("fparametroedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "parametro")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.parametro)
        ew.vars.tables.parametro = currentTable;
    fparametroedit.addFields([
        ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
        ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
        ["valor1", [fields.valor1.visible && fields.valor1.required ? ew.Validators.required(fields.valor1.caption) : null], fields.valor1.isInvalid],
        ["valor2", [fields.valor2.visible && fields.valor2.required ? ew.Validators.required(fields.valor2.caption) : null], fields.valor2.isInvalid],
        ["valor3", [fields.valor3.visible && fields.valor3.required ? ew.Validators.required(fields.valor3.caption) : null], fields.valor3.isInvalid],
        ["valor4", [fields.valor4.visible && fields.valor4.required ? ew.Validators.required(fields.valor4.caption) : null], fields.valor4.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fparametroedit,
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
    fparametroedit.validate = function () {
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
    fparametroedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fparametroedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fparametroedit");
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
<form name="fparametroedit" id="fparametroedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="parametro">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo" class="form-group row">
        <label id="elh_parametro_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->codigo->cellAttributes() ?>>
<span id="el_parametro_codigo">
<input type="<?= $Page->codigo->getInputTextType() ?>" data-table="parametro" data-field="x_codigo" name="x_codigo" id="x_codigo" size="30" maxlength="3" placeholder="<?= HtmlEncode($Page->codigo->getPlaceHolder()) ?>" value="<?= $Page->codigo->EditValue ?>"<?= $Page->codigo->editAttributes() ?> aria-describedby="x_codigo_help">
<?= $Page->codigo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->codigo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion" class="form-group row">
        <label id="elh_parametro_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->descripcion->cellAttributes() ?>>
<span id="el_parametro_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->descripcion->getDisplayValue($Page->descripcion->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="parametro" data-field="x_descripcion" data-hidden="1" name="x_descripcion" id="x_descripcion" value="<?= HtmlEncode($Page->descripcion->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->valor1->Visible) { // valor1 ?>
    <div id="r_valor1" class="form-group row">
        <label id="elh_parametro_valor1" for="x_valor1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->valor1->caption() ?><?= $Page->valor1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->valor1->cellAttributes() ?>>
<span id="el_parametro_valor1">
<input type="<?= $Page->valor1->getInputTextType() ?>" data-table="parametro" data-field="x_valor1" name="x_valor1" id="x_valor1" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->valor1->getPlaceHolder()) ?>" value="<?= $Page->valor1->EditValue ?>"<?= $Page->valor1->editAttributes() ?> aria-describedby="x_valor1_help">
<?= $Page->valor1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->valor1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->valor2->Visible) { // valor2 ?>
    <div id="r_valor2" class="form-group row">
        <label id="elh_parametro_valor2" for="x_valor2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->valor2->caption() ?><?= $Page->valor2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->valor2->cellAttributes() ?>>
<span id="el_parametro_valor2">
<input type="<?= $Page->valor2->getInputTextType() ?>" data-table="parametro" data-field="x_valor2" name="x_valor2" id="x_valor2" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->valor2->getPlaceHolder()) ?>" value="<?= $Page->valor2->EditValue ?>"<?= $Page->valor2->editAttributes() ?> aria-describedby="x_valor2_help">
<?= $Page->valor2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->valor2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->valor3->Visible) { // valor3 ?>
    <div id="r_valor3" class="form-group row">
        <label id="elh_parametro_valor3" for="x_valor3" class="<?= $Page->LeftColumnClass ?>"><?= $Page->valor3->caption() ?><?= $Page->valor3->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->valor3->cellAttributes() ?>>
<span id="el_parametro_valor3">
<input type="<?= $Page->valor3->getInputTextType() ?>" data-table="parametro" data-field="x_valor3" name="x_valor3" id="x_valor3" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->valor3->getPlaceHolder()) ?>" value="<?= $Page->valor3->EditValue ?>"<?= $Page->valor3->editAttributes() ?> aria-describedby="x_valor3_help">
<?= $Page->valor3->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->valor3->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->valor4->Visible) { // valor4 ?>
    <div id="r_valor4" class="form-group row">
        <label id="elh_parametro_valor4" for="x_valor4" class="<?= $Page->LeftColumnClass ?>"><?= $Page->valor4->caption() ?><?= $Page->valor4->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->valor4->cellAttributes() ?>>
<span id="el_parametro_valor4">
<input type="<?= $Page->valor4->getInputTextType() ?>" data-table="parametro" data-field="x_valor4" name="x_valor4" id="x_valor4" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->valor4->getPlaceHolder()) ?>" value="<?= $Page->valor4->EditValue ?>"<?= $Page->valor4->editAttributes() ?> aria-describedby="x_valor4_help">
<?= $Page->valor4->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->valor4->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="parametro" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
    ew.addEventHandlers("parametro");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
