<?php

namespace PHPMaker2021\mandrake;

// Page object
$TarifaAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var ftarifaadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    ftarifaadd = currentForm = new ew.Form("ftarifaadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "tarifa")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.tarifa)
        ew.vars.tables.tarifa = currentTable;
    ftarifaadd.addFields([
        ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
        ["patron", [fields.patron.visible && fields.patron.required ? ew.Validators.required(fields.patron.caption) : null], fields.patron.isInvalid],
        ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid],
        ["porcentaje", [fields.porcentaje.visible && fields.porcentaje.required ? ew.Validators.required(fields.porcentaje.caption) : null, ew.Validators.float], fields.porcentaje.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = ftarifaadd,
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
    ftarifaadd.validate = function () {
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
    ftarifaadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ftarifaadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    ftarifaadd.lists.patron = <?= $Page->patron->toClientList($Page) ?>;
    ftarifaadd.lists.activo = <?= $Page->activo->toClientList($Page) ?>;
    loadjs.done("ftarifaadd");
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
<form name="ftarifaadd" id="ftarifaadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tarifa">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre" class="form-group row">
        <label id="elh_tarifa_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nombre->cellAttributes() ?>>
<span id="el_tarifa_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" data-table="tarifa" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" value="<?= $Page->nombre->EditValue ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->patron->Visible) { // patron ?>
    <div id="r_patron" class="form-group row">
        <label id="elh_tarifa_patron" for="x_patron" class="<?= $Page->LeftColumnClass ?>"><?= $Page->patron->caption() ?><?= $Page->patron->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->patron->cellAttributes() ?>>
<span id="el_tarifa_patron">
    <select
        id="x_patron"
        name="x_patron"
        class="form-control ew-select<?= $Page->patron->isInvalidClass() ?>"
        data-select2-id="tarifa_x_patron"
        data-table="tarifa"
        data-field="x_patron"
        data-value-separator="<?= $Page->patron->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->patron->getPlaceHolder()) ?>"
        <?= $Page->patron->editAttributes() ?>>
        <?= $Page->patron->selectOptionListHtml("x_patron") ?>
    </select>
    <?= $Page->patron->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->patron->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='tarifa_x_patron']"),
        options = { name: "x_patron", selectId: "tarifa_x_patron", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.tarifa.fields.patron.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.tarifa.fields.patron.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo" class="form-group row">
        <label id="elh_tarifa_activo" for="x_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->activo->cellAttributes() ?>>
<span id="el_tarifa_activo">
    <select
        id="x_activo"
        name="x_activo"
        class="form-control ew-select<?= $Page->activo->isInvalidClass() ?>"
        data-select2-id="tarifa_x_activo"
        data-table="tarifa"
        data-field="x_activo"
        data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->activo->getPlaceHolder()) ?>"
        <?= $Page->activo->editAttributes() ?>>
        <?= $Page->activo->selectOptionListHtml("x_activo") ?>
    </select>
    <?= $Page->activo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->activo->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='tarifa_x_activo']"),
        options = { name: "x_activo", selectId: "tarifa_x_activo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.tarifa.fields.activo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.tarifa.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->porcentaje->Visible) { // porcentaje ?>
    <div id="r_porcentaje" class="form-group row">
        <label id="elh_tarifa_porcentaje" for="x_porcentaje" class="<?= $Page->LeftColumnClass ?>"><?= $Page->porcentaje->caption() ?><?= $Page->porcentaje->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->porcentaje->cellAttributes() ?>>
<span id="el_tarifa_porcentaje">
<input type="<?= $Page->porcentaje->getInputTextType() ?>" data-table="tarifa" data-field="x_porcentaje" name="x_porcentaje" id="x_porcentaje" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->porcentaje->getPlaceHolder()) ?>" value="<?= $Page->porcentaje->EditValue ?>"<?= $Page->porcentaje->editAttributes() ?> aria-describedby="x_porcentaje_help">
<?= $Page->porcentaje->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->porcentaje->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("tarifa_articulo", explode(",", $Page->getCurrentDetailTable())) && $tarifa_articulo->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("tarifa_articulo", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "TarifaArticuloGrid.php" ?>
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
    ew.addEventHandlers("tarifa");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
