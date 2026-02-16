<?php

namespace PHPMaker2021\mandrake;

// Page object
$TablaEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var ftablaedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    ftablaedit = currentForm = new ew.Form("ftablaedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "tabla")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.tabla)
        ew.vars.tables.tabla = currentTable;
    ftablaedit.addFields([
        ["tabla", [fields.tabla.visible && fields.tabla.required ? ew.Validators.required(fields.tabla.caption) : null], fields.tabla.isInvalid],
        ["campo_codigo", [fields.campo_codigo.visible && fields.campo_codigo.required ? ew.Validators.required(fields.campo_codigo.caption) : null], fields.campo_codigo.isInvalid],
        ["campo_descripcion", [fields.campo_descripcion.visible && fields.campo_descripcion.required ? ew.Validators.required(fields.campo_descripcion.caption) : null], fields.campo_descripcion.isInvalid],
        ["campo_dato", [fields.campo_dato.visible && fields.campo_dato.required ? ew.Validators.required(fields.campo_dato.caption) : null], fields.campo_dato.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = ftablaedit,
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
    ftablaedit.validate = function () {
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
    ftablaedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ftablaedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    ftablaedit.lists.campo_dato = <?= $Page->campo_dato->toClientList($Page) ?>;
    loadjs.done("ftablaedit");
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
<form name="ftablaedit" id="ftablaedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tabla">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->tabla->Visible) { // tabla ?>
    <div id="r_tabla" class="form-group row">
        <label id="elh_tabla_tabla" for="x_tabla" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tabla->caption() ?><?= $Page->tabla->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tabla->cellAttributes() ?>>
<span id="el_tabla_tabla">
<span<?= $Page->tabla->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tabla->getDisplayValue($Page->tabla->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="tabla" data-field="x_tabla" data-hidden="1" name="x_tabla" id="x_tabla" value="<?= HtmlEncode($Page->tabla->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->campo_codigo->Visible) { // campo_codigo ?>
    <div id="r_campo_codigo" class="form-group row">
        <label id="elh_tabla_campo_codigo" for="x_campo_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->campo_codigo->caption() ?><?= $Page->campo_codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->campo_codigo->cellAttributes() ?>>
<span id="el_tabla_campo_codigo">
<span<?= $Page->campo_codigo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->campo_codigo->getDisplayValue($Page->campo_codigo->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="tabla" data-field="x_campo_codigo" data-hidden="1" name="x_campo_codigo" id="x_campo_codigo" value="<?= HtmlEncode($Page->campo_codigo->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->campo_descripcion->Visible) { // campo_descripcion ?>
    <div id="r_campo_descripcion" class="form-group row">
        <label id="elh_tabla_campo_descripcion" for="x_campo_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->campo_descripcion->caption() ?><?= $Page->campo_descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->campo_descripcion->cellAttributes() ?>>
<span id="el_tabla_campo_descripcion">
<input type="<?= $Page->campo_descripcion->getInputTextType() ?>" data-table="tabla" data-field="x_campo_descripcion" name="x_campo_descripcion" id="x_campo_descripcion" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->campo_descripcion->getPlaceHolder()) ?>" value="<?= $Page->campo_descripcion->EditValue ?>"<?= $Page->campo_descripcion->editAttributes() ?> aria-describedby="x_campo_descripcion_help">
<?= $Page->campo_descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->campo_descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->campo_dato->Visible) { // campo_dato ?>
    <div id="r_campo_dato" class="form-group row">
        <label id="elh_tabla_campo_dato" for="x_campo_dato" class="<?= $Page->LeftColumnClass ?>"><?= $Page->campo_dato->caption() ?><?= $Page->campo_dato->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->campo_dato->cellAttributes() ?>>
<span id="el_tabla_campo_dato">
    <select
        id="x_campo_dato"
        name="x_campo_dato"
        class="form-control ew-select<?= $Page->campo_dato->isInvalidClass() ?>"
        data-select2-id="tabla_x_campo_dato"
        data-table="tabla"
        data-field="x_campo_dato"
        data-value-separator="<?= $Page->campo_dato->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->campo_dato->getPlaceHolder()) ?>"
        <?= $Page->campo_dato->editAttributes() ?>>
        <?= $Page->campo_dato->selectOptionListHtml("x_campo_dato") ?>
    </select>
    <?= $Page->campo_dato->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->campo_dato->getErrorMessage() ?></div>
<?= $Page->campo_dato->Lookup->getParamTag($Page, "p_x_campo_dato") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='tabla_x_campo_dato']"),
        options = { name: "x_campo_dato", selectId: "tabla_x_campo_dato", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.tabla.fields.campo_dato.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="tabla" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
    ew.addEventHandlers("tabla");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
