<?php

namespace PHPMaker2021\mandrake;

// Page object
$ViewFacturaAsesorEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fview_factura_asesoredit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fview_factura_asesoredit = currentForm = new ew.Form("fview_factura_asesoredit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "view_factura_asesor")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.view_factura_asesor)
        ew.vars.tables.view_factura_asesor = currentTable;
    fview_factura_asesoredit.addFields([
        ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
        ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
        ["asesor", [fields.asesor.visible && fields.asesor.required ? ew.Validators.required(fields.asesor.caption) : null], fields.asesor.isInvalid],
        ["total", [fields.total.visible && fields.total.required ? ew.Validators.required(fields.total.caption) : null], fields.total.isInvalid],
        ["documento", [fields.documento.visible && fields.documento.required ? ew.Validators.required(fields.documento.caption) : null], fields.documento.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fview_factura_asesoredit,
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
    fview_factura_asesoredit.validate = function () {
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
    fview_factura_asesoredit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fview_factura_asesoredit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fview_factura_asesoredit.lists.asesor = <?= $Page->asesor->toClientList($Page) ?>;
    loadjs.done("fview_factura_asesoredit");
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
<form name="fview_factura_asesoredit" id="fview_factura_asesoredit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="view_factura_asesor">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id" class="form-group row">
        <label id="elh_view_factura_asesor_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id->cellAttributes() ?>>
<span id="el_view_factura_asesor_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_factura_asesor" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento" class="form-group row">
        <label id="elh_view_factura_asesor_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_view_factura_asesor_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->nro_documento->getDisplayValue($Page->nro_documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_factura_asesor" data-field="x_nro_documento" data-hidden="1" name="x_nro_documento" id="x_nro_documento" value="<?= HtmlEncode($Page->nro_documento->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_view_factura_asesor_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_view_factura_asesor_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha->getDisplayValue($Page->fecha->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_factura_asesor" data-field="x_fecha" data-hidden="1" name="x_fecha" id="x_fecha" value="<?= HtmlEncode($Page->fecha->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente" class="form-group row">
        <label id="elh_view_factura_asesor_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cliente->cellAttributes() ?>>
<span id="el_view_factura_asesor_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->cliente->getDisplayValue($Page->cliente->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_factura_asesor" data-field="x_cliente" data-hidden="1" name="x_cliente" id="x_cliente" value="<?= HtmlEncode($Page->cliente->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
    <div id="r_asesor" class="form-group row">
        <label id="elh_view_factura_asesor_asesor" for="x_asesor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->asesor->caption() ?><?= $Page->asesor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->asesor->cellAttributes() ?>>
<span id="el_view_factura_asesor_asesor">
<div class="input-group ew-lookup-list" aria-describedby="x_asesor_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_asesor"><?= EmptyValue(strval($Page->asesor->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->asesor->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->asesor->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->asesor->ReadOnly || $Page->asesor->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_asesor',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->asesor->getErrorMessage() ?></div>
<?= $Page->asesor->getCustomMessage() ?>
<?= $Page->asesor->Lookup->getParamTag($Page, "p_x_asesor") ?>
<input type="hidden" is="selection-list" data-table="view_factura_asesor" data-field="x_asesor" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->asesor->displayValueSeparatorAttribute() ?>" name="x_asesor" id="x_asesor" value="<?= $Page->asesor->CurrentValue ?>"<?= $Page->asesor->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <div id="r_total" class="form-group row">
        <label id="elh_view_factura_asesor_total" for="x_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->total->caption() ?><?= $Page->total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->total->cellAttributes() ?>>
<span id="el_view_factura_asesor_total">
<span<?= $Page->total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->total->getDisplayValue($Page->total->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_factura_asesor" data-field="x_total" data-hidden="1" name="x_total" id="x_total" value="<?= HtmlEncode($Page->total->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <div id="r_documento" class="form-group row">
        <label id="elh_view_factura_asesor_documento" for="x_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->documento->caption() ?><?= $Page->documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->documento->cellAttributes() ?>>
<span id="el_view_factura_asesor_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->documento->getDisplayValue($Page->documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_factura_asesor" data-field="x_documento" data-hidden="1" name="x_documento" id="x_documento" value="<?= HtmlEncode($Page->documento->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
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
    ew.addEventHandlers("view_factura_asesor");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
