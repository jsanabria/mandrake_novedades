<?php

namespace PHPMaker2021\mandrake;

// Page object
$GrupoFuncionesAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fgrupo_funcionesadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fgrupo_funcionesadd = currentForm = new ew.Form("fgrupo_funcionesadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "grupo_funciones")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.grupo_funciones)
        ew.vars.tables.grupo_funciones = currentTable;
    fgrupo_funcionesadd.addFields([
        ["funcion", [fields.funcion.visible && fields.funcion.required ? ew.Validators.required(fields.funcion.caption) : null], fields.funcion.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fgrupo_funcionesadd,
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
    fgrupo_funcionesadd.validate = function () {
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
    fgrupo_funcionesadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fgrupo_funcionesadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fgrupo_funcionesadd.lists.funcion = <?= $Page->funcion->toClientList($Page) ?>;
    loadjs.done("fgrupo_funcionesadd");
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
<form name="fgrupo_funcionesadd" id="fgrupo_funcionesadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="grupo_funciones">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "userlevels") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="userlevels">
<input type="hidden" name="fk_userlevelid" value="<?= HtmlEncode($Page->grupo->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->funcion->Visible) { // funcion ?>
    <div id="r_funcion" class="form-group row">
        <label id="elh_grupo_funciones_funcion" for="x_funcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->funcion->caption() ?><?= $Page->funcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->funcion->cellAttributes() ?>>
<span id="el_grupo_funciones_funcion">
    <select
        id="x_funcion"
        name="x_funcion"
        class="form-control ew-select<?= $Page->funcion->isInvalidClass() ?>"
        data-select2-id="grupo_funciones_x_funcion"
        data-table="grupo_funciones"
        data-field="x_funcion"
        data-value-separator="<?= $Page->funcion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->funcion->getPlaceHolder()) ?>"
        <?= $Page->funcion->editAttributes() ?>>
        <?= $Page->funcion->selectOptionListHtml("x_funcion") ?>
    </select>
    <?= $Page->funcion->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->funcion->getErrorMessage() ?></div>
<?= $Page->funcion->Lookup->getParamTag($Page, "p_x_funcion") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='grupo_funciones_x_funcion']"),
        options = { name: "x_funcion", selectId: "grupo_funciones_x_funcion", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.grupo_funciones.fields.funcion.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <?php if (strval($Page->grupo->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_grupo" id="x_grupo" value="<?= HtmlEncode(strval($Page->grupo->getSessionValue())) ?>">
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
    ew.addEventHandlers("grupo_funciones");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
