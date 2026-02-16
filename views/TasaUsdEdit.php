<?php

namespace PHPMaker2021\mandrake;

// Page object
$TasaUsdEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var ftasa_usdedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    ftasa_usdedit = currentForm = new ew.Form("ftasa_usdedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "tasa_usd")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.tasa_usd)
        ew.vars.tables.tasa_usd = currentTable;
    ftasa_usdedit.addFields([
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["tasa", [fields.tasa.visible && fields.tasa.required ? ew.Validators.required(fields.tasa.caption) : null, ew.Validators.float], fields.tasa.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
        ["hora", [fields.hora.visible && fields.hora.required ? ew.Validators.required(fields.hora.caption) : null], fields.hora.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = ftasa_usdedit,
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
    ftasa_usdedit.validate = function () {
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
    ftasa_usdedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ftasa_usdedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    ftasa_usdedit.lists.moneda = <?= $Page->moneda->toClientList($Page) ?>;
    loadjs.done("ftasa_usdedit");
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
<form name="ftasa_usdedit" id="ftasa_usdedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tasa_usd">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda" class="form-group row">
        <label id="elh_tasa_usd_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->moneda->cellAttributes() ?>>
<span id="el_tasa_usd_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-control ew-select<?= $Page->moneda->isInvalidClass() ?>"
        data-select2-id="tasa_usd_x_moneda"
        data-table="tasa_usd"
        data-field="x_moneda"
        data-value-separator="<?= $Page->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>"
        <?= $Page->moneda->editAttributes() ?>>
        <?= $Page->moneda->selectOptionListHtml("x_moneda") ?>
    </select>
    <?= $Page->moneda->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
<?= $Page->moneda->Lookup->getParamTag($Page, "p_x_moneda") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='tasa_usd_x_moneda']"),
        options = { name: "x_moneda", selectId: "tasa_usd_x_moneda", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.tasa_usd.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tasa->Visible) { // tasa ?>
    <div id="r_tasa" class="form-group row">
        <label id="elh_tasa_usd_tasa" for="x_tasa" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tasa->caption() ?><?= $Page->tasa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tasa->cellAttributes() ?>>
<span id="el_tasa_usd_tasa">
<input type="<?= $Page->tasa->getInputTextType() ?>" data-table="tasa_usd" data-field="x_tasa" name="x_tasa" id="x_tasa" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->tasa->getPlaceHolder()) ?>" value="<?= $Page->tasa->EditValue ?>"<?= $Page->tasa->editAttributes() ?> aria-describedby="x_tasa_help">
<?= $Page->tasa->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tasa->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="tasa_usd" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
    ew.addEventHandlers("tasa_usd");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
