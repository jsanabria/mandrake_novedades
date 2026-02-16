<?php

namespace PHPMaker2021\mandrake;

// Page object
$AsesorFabricanteEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fasesor_fabricanteedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fasesor_fabricanteedit = currentForm = new ew.Form("fasesor_fabricanteedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "asesor_fabricante")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.asesor_fabricante)
        ew.vars.tables.asesor_fabricante = currentTable;
    fasesor_fabricanteedit.addFields([
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fasesor_fabricanteedit,
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
    fasesor_fabricanteedit.validate = function () {
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
    fasesor_fabricanteedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fasesor_fabricanteedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fasesor_fabricanteedit.lists.fabricante = <?= $Page->fabricante->toClientList($Page) ?>;
    loadjs.done("fasesor_fabricanteedit");
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
<form name="fasesor_fabricanteedit" id="fasesor_fabricanteedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="asesor_fabricante">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "asesor") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="asesor">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->asesor->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante" class="form-group row">
        <label id="elh_asesor_fabricante_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fabricante->cellAttributes() ?>>
<span id="el_asesor_fabricante_fabricante">
<div class="input-group ew-lookup-list" aria-describedby="x_fabricante_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_fabricante"><?= EmptyValue(strval($Page->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->getCustomMessage() ?>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x_fabricante") ?>
<input type="hidden" is="selection-list" data-table="asesor_fabricante" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x_fabricante" id="x_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="asesor_fabricante" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
    ew.addEventHandlers("asesor_fabricante");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
