<?php

namespace PHPMaker2021\mandrake;

// Page object
$TarifaArticuloAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var ftarifa_articuloadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    ftarifa_articuloadd = currentForm = new ew.Form("ftarifa_articuloadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "tarifa_articulo")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.tarifa_articulo)
        ew.vars.tables.tarifa_articulo = currentTable;
    ftarifa_articuloadd.addFields([
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
        ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = ftarifa_articuloadd,
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
    ftarifa_articuloadd.validate = function () {
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
    ftarifa_articuloadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ftarifa_articuloadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    ftarifa_articuloadd.lists.fabricante = <?= $Page->fabricante->toClientList($Page) ?>;
    ftarifa_articuloadd.lists.articulo = <?= $Page->articulo->toClientList($Page) ?>;
    loadjs.done("ftarifa_articuloadd");
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
<form name="ftarifa_articuloadd" id="ftarifa_articuloadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tarifa_articulo">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "tarifa") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="tarifa">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->tarifa->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante" class="form-group row">
        <label id="elh_tarifa_articulo_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fabricante->cellAttributes() ?>>
<span id="el_tarifa_articulo_fabricante">
<?php $Page->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list" aria-describedby="x_fabricante_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_fabricante"><?= EmptyValue(strval($Page->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->getCustomMessage() ?>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x_fabricante") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x_fabricante" id="x_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <div id="r_articulo" class="form-group row">
        <label id="elh_tarifa_articulo_articulo" for="x_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo->caption() ?><?= $Page->articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->articulo->cellAttributes() ?>>
<span id="el_tarifa_articulo_articulo">
<div class="input-group ew-lookup-list" aria-describedby="x_articulo_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_articulo"><?= EmptyValue(strval($Page->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<?= $Page->articulo->getCustomMessage() ?>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x_articulo") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x_articulo" id="x_articulo" value="<?= $Page->articulo->CurrentValue ?>"<?= $Page->articulo->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <div id="r_precio" class="form-group row">
        <label id="elh_tarifa_articulo_precio" for="x_precio" class="<?= $Page->LeftColumnClass ?>"><?= $Page->precio->caption() ?><?= $Page->precio->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->precio->cellAttributes() ?>>
<span id="el_tarifa_articulo_precio">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="tarifa_articulo" data-field="x_precio" name="x_precio" id="x_precio" size="10" maxlength="13" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?> aria-describedby="x_precio_help">
<?= $Page->precio->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <?php if (strval($Page->tarifa->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_tarifa" id="x_tarifa" value="<?= HtmlEncode(strval($Page->tarifa->getSessionValue())) ?>">
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
    ew.addEventHandlers("tarifa_articulo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
