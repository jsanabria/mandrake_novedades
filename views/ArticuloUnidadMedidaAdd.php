<?php

namespace PHPMaker2021\mandrake;

// Page object
$ArticuloUnidadMedidaAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var farticulo_unidad_medidaadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    farticulo_unidad_medidaadd = currentForm = new ew.Form("farticulo_unidad_medidaadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "articulo_unidad_medida")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.articulo_unidad_medida)
        ew.vars.tables.articulo_unidad_medida = currentTable;
    farticulo_unidad_medidaadd.addFields([
        ["unidad_medida", [fields.unidad_medida.visible && fields.unidad_medida.required ? ew.Validators.required(fields.unidad_medida.caption) : null], fields.unidad_medida.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = farticulo_unidad_medidaadd,
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
    farticulo_unidad_medidaadd.validate = function () {
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
    farticulo_unidad_medidaadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    farticulo_unidad_medidaadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    farticulo_unidad_medidaadd.lists.unidad_medida = <?= $Page->unidad_medida->toClientList($Page) ?>;
    loadjs.done("farticulo_unidad_medidaadd");
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
<form name="farticulo_unidad_medidaadd" id="farticulo_unidad_medidaadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="articulo_unidad_medida">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "articulo") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="articulo">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->articulo->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->unidad_medida->Visible) { // unidad_medida ?>
    <div id="r_unidad_medida" class="form-group row">
        <label id="elh_articulo_unidad_medida_unidad_medida" for="x_unidad_medida" class="<?= $Page->LeftColumnClass ?>"><?= $Page->unidad_medida->caption() ?><?= $Page->unidad_medida->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->unidad_medida->cellAttributes() ?>>
<span id="el_articulo_unidad_medida_unidad_medida">
    <select
        id="x_unidad_medida"
        name="x_unidad_medida"
        class="form-control ew-select<?= $Page->unidad_medida->isInvalidClass() ?>"
        data-select2-id="articulo_unidad_medida_x_unidad_medida"
        data-table="articulo_unidad_medida"
        data-field="x_unidad_medida"
        data-value-separator="<?= $Page->unidad_medida->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->unidad_medida->getPlaceHolder()) ?>"
        <?= $Page->unidad_medida->editAttributes() ?>>
        <?= $Page->unidad_medida->selectOptionListHtml("x_unidad_medida") ?>
    </select>
    <?= $Page->unidad_medida->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->unidad_medida->getErrorMessage() ?></div>
<?= $Page->unidad_medida->Lookup->getParamTag($Page, "p_x_unidad_medida") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='articulo_unidad_medida_x_unidad_medida']"),
        options = { name: "x_unidad_medida", selectId: "articulo_unidad_medida_x_unidad_medida", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.articulo_unidad_medida.fields.unidad_medida.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <?php if (strval($Page->articulo->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_articulo" id="x_articulo" value="<?= HtmlEncode(strval($Page->articulo->getSessionValue())) ?>">
    <?php } ?>
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
    ew.addEventHandlers("articulo_unidad_medida");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
