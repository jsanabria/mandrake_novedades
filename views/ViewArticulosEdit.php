<?php

namespace PHPMaker2021\mandrake;

// Page object
$ViewArticulosEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fview_articulosedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fview_articulosedit = currentForm = new ew.Form("fview_articulosedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "view_articulos")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.view_articulos)
        ew.vars.tables.view_articulos = currentTable;
    fview_articulosedit.addFields([
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
        ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
        ["cantidad_en_mano", [fields.cantidad_en_mano.visible && fields.cantidad_en_mano.required ? ew.Validators.required(fields.cantidad_en_mano.caption) : null], fields.cantidad_en_mano.isInvalid],
        ["ultimo_costo", [fields.ultimo_costo.visible && fields.ultimo_costo.required ? ew.Validators.required(fields.ultimo_costo.caption) : null, ew.Validators.float], fields.ultimo_costo.isInvalid],
        ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid],
        ["precio2", [fields.precio2.visible && fields.precio2.required ? ew.Validators.required(fields.precio2.caption) : null, ew.Validators.float], fields.precio2.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fview_articulosedit,
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
    fview_articulosedit.validate = function () {
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
    fview_articulosedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fview_articulosedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fview_articulosedit");
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
<form name="fview_articulosedit" id="fview_articulosedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="view_articulos">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia" class="form-group row">
        <label id="elh_view_articulos_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->referencia->cellAttributes() ?>>
<span id="el_view_articulos_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->referencia->getDisplayValue($Page->referencia->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_referencia" data-hidden="1" name="x_referencia" id="x_referencia" value="<?= HtmlEncode($Page->referencia->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo" class="form-group row">
        <label id="elh_view_articulos_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->codigo->cellAttributes() ?>>
<span id="el_view_articulos_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->codigo->getDisplayValue($Page->codigo->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_codigo" data-hidden="1" name="x_codigo" id="x_codigo" value="<?= HtmlEncode($Page->codigo->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante" class="form-group row">
        <label id="elh_view_articulos_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fabricante->cellAttributes() ?>>
<span id="el_view_articulos_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fabricante->getDisplayValue($Page->fabricante->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_fabricante" data-hidden="1" name="x_fabricante" id="x_fabricante" value="<?= HtmlEncode($Page->fabricante->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre" class="form-group row">
        <label id="elh_view_articulos_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nombre->cellAttributes() ?>>
<span id="el_view_articulos_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->nombre->getDisplayValue($Page->nombre->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_nombre" data-hidden="1" name="x_nombre" id="x_nombre" value="<?= HtmlEncode($Page->nombre->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
    <div id="r_cantidad_en_mano" class="form-group row">
        <label id="elh_view_articulos_cantidad_en_mano" for="x_cantidad_en_mano" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_en_mano->caption() ?><?= $Page->cantidad_en_mano->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cantidad_en_mano->cellAttributes() ?>>
<span id="el_view_articulos_cantidad_en_mano">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->cantidad_en_mano->getDisplayValue($Page->cantidad_en_mano->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_cantidad_en_mano" data-hidden="1" name="x_cantidad_en_mano" id="x_cantidad_en_mano" value="<?= HtmlEncode($Page->cantidad_en_mano->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
    <div id="r_ultimo_costo" class="form-group row">
        <label id="elh_view_articulos_ultimo_costo" for="x_ultimo_costo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ultimo_costo->caption() ?><?= $Page->ultimo_costo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ultimo_costo->cellAttributes() ?>>
<span id="el_view_articulos_ultimo_costo">
<input type="<?= $Page->ultimo_costo->getInputTextType() ?>" data-table="view_articulos" data-field="x_ultimo_costo" name="x_ultimo_costo" id="x_ultimo_costo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->ultimo_costo->getPlaceHolder()) ?>" value="<?= $Page->ultimo_costo->EditValue ?>"<?= $Page->ultimo_costo->editAttributes() ?> aria-describedby="x_ultimo_costo_help">
<?= $Page->ultimo_costo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ultimo_costo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <div id="r_precio" class="form-group row">
        <label id="elh_view_articulos_precio" for="x_precio" class="<?= $Page->LeftColumnClass ?>"><?= $Page->precio->caption() ?><?= $Page->precio->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->precio->cellAttributes() ?>>
<span id="el_view_articulos_precio">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="view_articulos" data-field="x_precio" name="x_precio" id="x_precio" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?> aria-describedby="x_precio_help">
<?= $Page->precio->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->precio2->Visible) { // precio2 ?>
    <div id="r_precio2" class="form-group row">
        <label id="elh_view_articulos_precio2" for="x_precio2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->precio2->caption() ?><?= $Page->precio2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->precio2->cellAttributes() ?>>
<span id="el_view_articulos_precio2">
<input type="<?= $Page->precio2->getInputTextType() ?>" data-table="view_articulos" data-field="x_precio2" name="x_precio2" id="x_precio2" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio2->getPlaceHolder()) ?>" value="<?= $Page->precio2->EditValue ?>"<?= $Page->precio2->editAttributes() ?> aria-describedby="x_precio2_help">
<?= $Page->precio2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->precio2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="view_articulos" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
    ew.addEventHandlers("view_articulos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
