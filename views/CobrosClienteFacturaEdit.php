<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteFacturaEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcobros_cliente_facturaedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fcobros_cliente_facturaedit = currentForm = new ew.Form("fcobros_cliente_facturaedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cobros_cliente_factura")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cobros_cliente_factura)
        ew.vars.tables.cobros_cliente_factura = currentTable;
    fcobros_cliente_facturaedit.addFields([
        ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
        ["abono", [fields.abono.visible && fields.abono.required ? ew.Validators.required(fields.abono.caption) : null], fields.abono.isInvalid],
        ["monto", [fields.monto.visible && fields.monto.required ? ew.Validators.required(fields.monto.caption) : null], fields.monto.isInvalid],
        ["retivamonto", [fields.retivamonto.visible && fields.retivamonto.required ? ew.Validators.required(fields.retivamonto.caption) : null], fields.retivamonto.isInvalid],
        ["retiva", [fields.retiva.visible && fields.retiva.required ? ew.Validators.required(fields.retiva.caption) : null], fields.retiva.isInvalid],
        ["retislrmonto", [fields.retislrmonto.visible && fields.retislrmonto.required ? ew.Validators.required(fields.retislrmonto.caption) : null], fields.retislrmonto.isInvalid],
        ["retislr", [fields.retislr.visible && fields.retislr.required ? ew.Validators.required(fields.retislr.caption) : null], fields.retislr.isInvalid],
        ["comprobante", [fields.comprobante.visible && fields.comprobante.required ? ew.Validators.required(fields.comprobante.caption) : null], fields.comprobante.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcobros_cliente_facturaedit,
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
    fcobros_cliente_facturaedit.validate = function () {
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
    fcobros_cliente_facturaedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcobros_cliente_facturaedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fcobros_cliente_facturaedit");
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
<form name="fcobros_cliente_facturaedit" id="fcobros_cliente_facturaedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente_factura">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento" class="form-group row">
        <label id="elh_cobros_cliente_factura_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tipo_documento->getDisplayValue($Page->tipo_documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-hidden="1" name="x_tipo_documento" id="x_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
    <div id="r_abono" class="form-group row">
        <label id="elh_cobros_cliente_factura_abono" class="<?= $Page->LeftColumnClass ?>"><?= $Page->abono->caption() ?><?= $Page->abono->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->abono->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_abono">
<span<?= $Page->abono->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->abono->getDisplayValue($Page->abono->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_abono" data-hidden="1" name="x_abono" id="x_abono" value="<?= HtmlEncode($Page->abono->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <div id="r_monto" class="form-group row">
        <label id="elh_cobros_cliente_factura_monto" for="x_monto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto->caption() ?><?= $Page->monto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_monto">
<span<?= $Page->monto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->monto->getDisplayValue($Page->monto->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_monto" data-hidden="1" name="x_monto" id="x_monto" value="<?= HtmlEncode($Page->monto->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->retivamonto->Visible) { // retivamonto ?>
    <div id="r_retivamonto" class="form-group row">
        <label id="elh_cobros_cliente_factura_retivamonto" for="x_retivamonto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->retivamonto->caption() ?><?= $Page->retivamonto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->retivamonto->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_retivamonto">
<span<?= $Page->retivamonto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->retivamonto->getDisplayValue($Page->retivamonto->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retivamonto" data-hidden="1" name="x_retivamonto" id="x_retivamonto" value="<?= HtmlEncode($Page->retivamonto->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->retiva->Visible) { // retiva ?>
    <div id="r_retiva" class="form-group row">
        <label id="elh_cobros_cliente_factura_retiva" for="x_retiva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->retiva->caption() ?><?= $Page->retiva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->retiva->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_retiva">
<input type="<?= $Page->retiva->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_retiva" name="x_retiva" id="x_retiva" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->retiva->getPlaceHolder()) ?>" value="<?= $Page->retiva->EditValue ?>"<?= $Page->retiva->editAttributes() ?> aria-describedby="x_retiva_help">
<?= $Page->retiva->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->retiva->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->retislrmonto->Visible) { // retislrmonto ?>
    <div id="r_retislrmonto" class="form-group row">
        <label id="elh_cobros_cliente_factura_retislrmonto" for="x_retislrmonto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->retislrmonto->caption() ?><?= $Page->retislrmonto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->retislrmonto->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_retislrmonto">
<span<?= $Page->retislrmonto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->retislrmonto->getDisplayValue($Page->retislrmonto->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislrmonto" data-hidden="1" name="x_retislrmonto" id="x_retislrmonto" value="<?= HtmlEncode($Page->retislrmonto->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->retislr->Visible) { // retislr ?>
    <div id="r_retislr" class="form-group row">
        <label id="elh_cobros_cliente_factura_retislr" for="x_retislr" class="<?= $Page->LeftColumnClass ?>"><?= $Page->retislr->caption() ?><?= $Page->retislr->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->retislr->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_retislr">
<input type="<?= $Page->retislr->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_retislr" name="x_retislr" id="x_retislr" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->retislr->getPlaceHolder()) ?>" value="<?= $Page->retislr->EditValue ?>"<?= $Page->retislr->editAttributes() ?> aria-describedby="x_retislr_help">
<?= $Page->retislr->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->retislr->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <div id="r_comprobante" class="form-group row">
        <label id="elh_cobros_cliente_factura_comprobante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->comprobante->caption() ?><?= $Page->comprobante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->comprobante->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_comprobante">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Page->comprobante->EditValue) && $Page->comprobante->linkAttributes() != "") { ?>
<a<?= $Page->comprobante->linkAttributes() ?>><input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->comprobante->getDisplayValue($Page->comprobante->EditValue))) ?>"></a>
<?php } else { ?>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->comprobante->getDisplayValue($Page->comprobante->EditValue))) ?>">
<?php } ?>
</span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_comprobante" data-hidden="1" name="x_comprobante" id="x_comprobante" value="<?= HtmlEncode($Page->comprobante->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="cobros_cliente_factura" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
    ew.addEventHandlers("cobros_cliente_factura");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
