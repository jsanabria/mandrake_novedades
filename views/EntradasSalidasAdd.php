<?php

namespace PHPMaker2021\mandrake;

// Page object
$EntradasSalidasAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fentradas_salidasadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fentradas_salidasadd = currentForm = new ew.Form("fentradas_salidasadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "entradas_salidas")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.entradas_salidas)
        ew.vars.tables.entradas_salidas = currentTable;
    fentradas_salidasadd.addFields([
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
        ["cantidad_articulo", [fields.cantidad_articulo.visible && fields.cantidad_articulo.required ? ew.Validators.required(fields.cantidad_articulo.caption) : null, ew.Validators.float], fields.cantidad_articulo.isInvalid],
        ["costo_unidad", [fields.costo_unidad.visible && fields.costo_unidad.required ? ew.Validators.required(fields.costo_unidad.caption) : null, ew.Validators.float], fields.costo_unidad.isInvalid],
        ["costo", [fields.costo.visible && fields.costo.required ? ew.Validators.required(fields.costo.caption) : null, ew.Validators.float], fields.costo.isInvalid],
        ["precio_unidad", [fields.precio_unidad.visible && fields.precio_unidad.required ? ew.Validators.required(fields.precio_unidad.caption) : null, ew.Validators.float], fields.precio_unidad.isInvalid],
        ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid],
        ["lote", [fields.lote.visible && fields.lote.required ? ew.Validators.required(fields.lote.caption) : null], fields.lote.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fentradas_salidasadd,
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
    fentradas_salidasadd.validate = function () {
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
    fentradas_salidasadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fentradas_salidasadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fentradas_salidasadd.lists.articulo = <?= $Page->articulo->toClientList($Page) ?>;
    loadjs.done("fentradas_salidasadd");
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
<form name="fentradas_salidasadd" id="fentradas_salidasadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas_salidas">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "entradas") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="entradas">
<input type="hidden" name="fk_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->getSessionValue()) ?>">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->id_documento->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "salidas") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="salidas">
<input type="hidden" name="fk_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->getSessionValue()) ?>">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->id_documento->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->articulo->Visible) { // articulo ?>
    <div id="r_articulo" class="form-group row">
        <label id="elh_entradas_salidas_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo->caption() ?><?= $Page->articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->articulo->cellAttributes() ?>>
<span id="el_entradas_salidas_articulo">
<?php
$onchange = $Page->articulo->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->articulo->EditAttrs["onchange"] = "";
?>
<span id="as_x_articulo" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->articulo->getInputTextType() ?>" class="form-control" name="sv_x_articulo" id="sv_x_articulo" value="<?= RemoveHtml($Page->articulo->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>"<?= $Page->articulo->editAttributes() ?> aria-describedby="x_articulo_help">
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_articulo',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="entradas_salidas" data-field="x_articulo" data-input="sv_x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x_articulo" id="x_articulo" value="<?= HtmlEncode($Page->articulo->CurrentValue) ?>"<?= $onchange ?>>
<?= $Page->articulo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready(["fentradas_salidasadd"], function() {
    fentradas_salidasadd.createAutoSuggest(Object.assign({"id":"x_articulo","forceSelect":true}, ew.vars.tables.entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x_articulo") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
    <div id="r_cantidad_articulo" class="form-group row">
        <label id="elh_entradas_salidas_cantidad_articulo" for="x_cantidad_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_articulo->caption() ?><?= $Page->cantidad_articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cantidad_articulo->cellAttributes() ?>>
<span id="el_entradas_salidas_cantidad_articulo">
<input type="<?= $Page->cantidad_articulo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_cantidad_articulo" name="x_cantidad_articulo" id="x_cantidad_articulo" size="6" maxlength="10" placeholder="<?= HtmlEncode($Page->cantidad_articulo->getPlaceHolder()) ?>" value="<?= $Page->cantidad_articulo->EditValue ?>"<?= $Page->cantidad_articulo->editAttributes() ?> aria-describedby="x_cantidad_articulo_help">
<?= $Page->cantidad_articulo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_articulo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
    <div id="r_costo_unidad" class="form-group row">
        <label id="elh_entradas_salidas_costo_unidad" for="x_costo_unidad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->costo_unidad->caption() ?><?= $Page->costo_unidad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->costo_unidad->cellAttributes() ?>>
<span id="el_entradas_salidas_costo_unidad">
<input type="<?= $Page->costo_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo_unidad" name="x_costo_unidad" id="x_costo_unidad" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->costo_unidad->getPlaceHolder()) ?>" value="<?= $Page->costo_unidad->EditValue ?>"<?= $Page->costo_unidad->editAttributes() ?> aria-describedby="x_costo_unidad_help">
<?= $Page->costo_unidad->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->costo_unidad->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
    <div id="r_costo" class="form-group row">
        <label id="elh_entradas_salidas_costo" for="x_costo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->costo->caption() ?><?= $Page->costo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->costo->cellAttributes() ?>>
<span id="el_entradas_salidas_costo">
<input type="<?= $Page->costo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo" name="x_costo" id="x_costo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->costo->getPlaceHolder()) ?>" value="<?= $Page->costo->EditValue ?>"<?= $Page->costo->editAttributes() ?> aria-describedby="x_costo_help">
<?= $Page->costo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->costo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
    <div id="r_precio_unidad" class="form-group row">
        <label id="elh_entradas_salidas_precio_unidad" for="x_precio_unidad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->precio_unidad->caption() ?><?= $Page->precio_unidad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->precio_unidad->cellAttributes() ?>>
<span id="el_entradas_salidas_precio_unidad">
<input type="<?= $Page->precio_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad" name="x_precio_unidad" id="x_precio_unidad" size="6" maxlength="14" placeholder="<?= HtmlEncode($Page->precio_unidad->getPlaceHolder()) ?>" value="<?= $Page->precio_unidad->EditValue ?>"<?= $Page->precio_unidad->editAttributes() ?> aria-describedby="x_precio_unidad_help">
<?= $Page->precio_unidad->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->precio_unidad->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <div id="r_precio" class="form-group row">
        <label id="elh_entradas_salidas_precio" for="x_precio" class="<?= $Page->LeftColumnClass ?>"><?= $Page->precio->caption() ?><?= $Page->precio->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->precio->cellAttributes() ?>>
<span id="el_entradas_salidas_precio">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio" name="x_precio" id="x_precio" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?> aria-describedby="x_precio_help">
<?= $Page->precio->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
    <div id="r_lote" class="form-group row">
        <label id="elh_entradas_salidas_lote" for="x_lote" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lote->caption() ?><?= $Page->lote->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->lote->cellAttributes() ?>>
<span id="el_entradas_salidas_lote">
<input type="<?= $Page->lote->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_lote" name="x_lote" id="x_lote" size="6" maxlength="20" placeholder="<?= HtmlEncode($Page->lote->getPlaceHolder()) ?>" value="<?= $Page->lote->EditValue ?>"<?= $Page->lote->editAttributes() ?> aria-describedby="x_lote_help">
<?= $Page->lote->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->lote->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <?php if (strval($Page->tipo_documento->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_tipo_documento" id="x_tipo_documento" value="<?= HtmlEncode(strval($Page->tipo_documento->getSessionValue())) ?>">
    <?php } ?>
    <?php if (strval($Page->id_documento->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_id_documento" id="x_id_documento" value="<?= HtmlEncode(strval($Page->id_documento->getSessionValue())) ?>">
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
    ew.addEventHandlers("entradas_salidas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
