<?php

namespace PHPMaker2021\mandrake;

// Page object
$UnidadMedidaEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var funidad_medidaedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    funidad_medidaedit = currentForm = new ew.Form("funidad_medidaedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "unidad_medida")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.unidad_medida)
        ew.vars.tables.unidad_medida = currentTable;
    funidad_medidaedit.addFields([
        ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
        ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
        ["cantidad", [fields.cantidad.visible && fields.cantidad.required ? ew.Validators.required(fields.cantidad.caption) : null, ew.Validators.float], fields.cantidad.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = funidad_medidaedit,
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
    funidad_medidaedit.validate = function () {
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
    funidad_medidaedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    funidad_medidaedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("funidad_medidaedit");
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
<form name="funidad_medidaedit" id="funidad_medidaedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="unidad_medida">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo" class="form-group row">
        <label id="elh_unidad_medida_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->codigo->cellAttributes() ?>>
<span id="el_unidad_medida_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->codigo->getDisplayValue($Page->codigo->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="unidad_medida" data-field="x_codigo" data-hidden="1" name="x_codigo" id="x_codigo" value="<?= HtmlEncode($Page->codigo->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion" class="form-group row">
        <label id="elh_unidad_medida_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->descripcion->cellAttributes() ?>>
<span id="el_unidad_medida_descripcion">
<input type="<?= $Page->descripcion->getInputTextType() ?>" data-table="unidad_medida" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>" value="<?= $Page->descripcion->EditValue ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help">
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
    <div id="r_cantidad" class="form-group row">
        <label id="elh_unidad_medida_cantidad" for="x_cantidad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad->caption() ?><?= $Page->cantidad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cantidad->cellAttributes() ?>>
<span id="el_unidad_medida_cantidad">
<input type="<?= $Page->cantidad->getInputTextType() ?>" data-table="unidad_medida" data-field="x_cantidad" name="x_cantidad" id="x_cantidad" size="6" maxlength="10" placeholder="<?= HtmlEncode($Page->cantidad->getPlaceHolder()) ?>" value="<?= $Page->cantidad->EditValue ?>"<?= $Page->cantidad->editAttributes() ?> aria-describedby="x_cantidad_help">
<?= $Page->cantidad->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="unidad_medida" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
    ew.addEventHandlers("unidad_medida");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
