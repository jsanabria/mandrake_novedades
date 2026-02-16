<?php

namespace PHPMaker2021\mandrake;

// Page object
$AlmacenAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var falmacenadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    falmacenadd = currentForm = new ew.Form("falmacenadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "almacen")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.almacen)
        ew.vars.tables.almacen = currentTable;
    falmacenadd.addFields([
        ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
        ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
        ["movimiento", [fields.movimiento.visible && fields.movimiento.required ? ew.Validators.required(fields.movimiento.caption) : null], fields.movimiento.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = falmacenadd,
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
    falmacenadd.validate = function () {
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
    falmacenadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    falmacenadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    falmacenadd.lists.movimiento = <?= $Page->movimiento->toClientList($Page) ?>;
    loadjs.done("falmacenadd");
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
<form name="falmacenadd" id="falmacenadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="almacen">
<?php if ($Page->isConfirm()) { // Confirm page ?>
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="confirm" id="confirm" value="confirm">
<?php } else { ?>
<input type="hidden" name="action" id="action" value="confirm">
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo" class="form-group row">
        <label id="elh_almacen_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->codigo->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_almacen_codigo">
<input type="<?= $Page->codigo->getInputTextType() ?>" data-table="almacen" data-field="x_codigo" name="x_codigo" id="x_codigo" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->codigo->getPlaceHolder()) ?>" value="<?= $Page->codigo->EditValue ?>"<?= $Page->codigo->editAttributes() ?> aria-describedby="x_codigo_help">
<?= $Page->codigo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->codigo->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_almacen_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->codigo->getDisplayValue($Page->codigo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="almacen" data-field="x_codigo" data-hidden="1" name="x_codigo" id="x_codigo" value="<?= HtmlEncode($Page->codigo->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion" class="form-group row">
        <label id="elh_almacen_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->descripcion->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_almacen_descripcion">
<input type="<?= $Page->descripcion->getInputTextType() ?>" data-table="almacen" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>" value="<?= $Page->descripcion->EditValue ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help">
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_almacen_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->descripcion->getDisplayValue($Page->descripcion->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="almacen" data-field="x_descripcion" data-hidden="1" name="x_descripcion" id="x_descripcion" value="<?= HtmlEncode($Page->descripcion->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->movimiento->Visible) { // movimiento ?>
    <div id="r_movimiento" class="form-group row">
        <label id="elh_almacen_movimiento" for="x_movimiento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->movimiento->caption() ?><?= $Page->movimiento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->movimiento->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_almacen_movimiento">
    <select
        id="x_movimiento"
        name="x_movimiento"
        class="form-control ew-select<?= $Page->movimiento->isInvalidClass() ?>"
        data-select2-id="almacen_x_movimiento"
        data-table="almacen"
        data-field="x_movimiento"
        data-value-separator="<?= $Page->movimiento->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->movimiento->getPlaceHolder()) ?>"
        <?= $Page->movimiento->editAttributes() ?>>
        <?= $Page->movimiento->selectOptionListHtml("x_movimiento") ?>
    </select>
    <?= $Page->movimiento->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->movimiento->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='almacen_x_movimiento']"),
        options = { name: "x_movimiento", selectId: "almacen_x_movimiento", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.almacen.fields.movimiento.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.almacen.fields.movimiento.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el_almacen_movimiento">
<span<?= $Page->movimiento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->movimiento->getDisplayValue($Page->movimiento->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="almacen" data-field="x_movimiento" data-hidden="1" name="x_movimiento" id="x_movimiento" value="<?= HtmlEncode($Page->movimiento->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" onclick="this.form.action.value='confirm';"><?= $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" onclick="this.form.action.value='cancel';"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
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
    ew.addEventHandlers("almacen");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
