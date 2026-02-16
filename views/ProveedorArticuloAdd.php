<?php

namespace PHPMaker2021\mandrake;

// Page object
$ProveedorArticuloAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fproveedor_articuloadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fproveedor_articuloadd = currentForm = new ew.Form("fproveedor_articuloadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "proveedor_articulo")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.proveedor_articulo)
        ew.vars.tables.proveedor_articulo = currentTable;
    fproveedor_articuloadd.addFields([
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
        ["codigo_proveedor", [fields.codigo_proveedor.visible && fields.codigo_proveedor.required ? ew.Validators.required(fields.codigo_proveedor.caption) : null], fields.codigo_proveedor.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fproveedor_articuloadd,
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
    fproveedor_articuloadd.validate = function () {
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
    fproveedor_articuloadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fproveedor_articuloadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fproveedor_articuloadd.lists.fabricante = <?= $Page->fabricante->toClientList($Page) ?>;
    fproveedor_articuloadd.lists.articulo = <?= $Page->articulo->toClientList($Page) ?>;
    loadjs.done("fproveedor_articuloadd");
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
<form name="fproveedor_articuloadd" id="fproveedor_articuloadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="proveedor_articulo">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "proveedor") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="proveedor">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->proveedor->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante" class="form-group row">
        <label id="elh_proveedor_articulo_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fabricante->cellAttributes() ?>>
<span id="el_proveedor_articulo_fabricante">
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
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x_fabricante" id="x_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <div id="r_articulo" class="form-group row">
        <label id="elh_proveedor_articulo_articulo" for="x_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo->caption() ?><?= $Page->articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->articulo->cellAttributes() ?>>
<span id="el_proveedor_articulo_articulo">
<div class="input-group ew-lookup-list" aria-describedby="x_articulo_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_articulo"><?= EmptyValue(strval($Page->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<?= $Page->articulo->getCustomMessage() ?>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x_articulo") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x_articulo" id="x_articulo" value="<?= $Page->articulo->CurrentValue ?>"<?= $Page->articulo->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->codigo_proveedor->Visible) { // codigo_proveedor ?>
    <div id="r_codigo_proveedor" class="form-group row">
        <label id="elh_proveedor_articulo_codigo_proveedor" for="x_codigo_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo_proveedor->caption() ?><?= $Page->codigo_proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->codigo_proveedor->cellAttributes() ?>>
<span id="el_proveedor_articulo_codigo_proveedor">
<input type="<?= $Page->codigo_proveedor->getInputTextType() ?>" data-table="proveedor_articulo" data-field="x_codigo_proveedor" name="x_codigo_proveedor" id="x_codigo_proveedor" size="10" maxlength="30" placeholder="<?= HtmlEncode($Page->codigo_proveedor->getPlaceHolder()) ?>" value="<?= $Page->codigo_proveedor->EditValue ?>"<?= $Page->codigo_proveedor->editAttributes() ?> aria-describedby="x_codigo_proveedor_help">
<?= $Page->codigo_proveedor->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->codigo_proveedor->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <?php if (strval($Page->proveedor->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_proveedor" id="x_proveedor" value="<?= HtmlEncode(strval($Page->proveedor->getSessionValue())) ?>">
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
    ew.addEventHandlers("proveedor_articulo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
