<?php

namespace PHPMaker2021\mandrake;

// Page object
$ViewFacturasAEntregarEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fview_facturas_a_entregaredit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fview_facturas_a_entregaredit = currentForm = new ew.Form("fview_facturas_a_entregaredit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "view_facturas_a_entregar")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.view_facturas_a_entregar)
        ew.vars.tables.view_facturas_a_entregar = currentTable;
    fview_facturas_a_entregaredit.addFields([
        ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
        ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
        ["codcli", [fields.codcli.visible && fields.codcli.required ? ew.Validators.required(fields.codcli.caption) : null], fields.codcli.isInvalid],
        ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
        ["total", [fields.total.visible && fields.total.required ? ew.Validators.required(fields.total.caption) : null], fields.total.isInvalid],
        ["entregado", [fields.entregado.visible && fields.entregado.required ? ew.Validators.required(fields.entregado.caption) : null], fields.entregado.isInvalid],
        ["fecha_entrega", [fields.fecha_entrega.visible && fields.fecha_entrega.required ? ew.Validators.required(fields.fecha_entrega.caption) : null, ew.Validators.datetime(7)], fields.fecha_entrega.isInvalid],
        ["dias_credito", [fields.dias_credito.visible && fields.dias_credito.required ? ew.Validators.required(fields.dias_credito.caption) : null], fields.dias_credito.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fview_facturas_a_entregaredit,
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
    fview_facturas_a_entregaredit.validate = function () {
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
    fview_facturas_a_entregaredit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fview_facturas_a_entregaredit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fview_facturas_a_entregaredit.lists.entregado = <?= $Page->entregado->toClientList($Page) ?>;
    loadjs.done("fview_facturas_a_entregaredit");
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
<form name="fview_facturas_a_entregaredit" id="fview_facturas_a_entregaredit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="view_facturas_a_entregar">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id" class="form-group row">
        <label id="elh_view_facturas_a_entregar_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento" class="form-group row">
        <label id="elh_view_facturas_a_entregar_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tipo_documento->getDisplayValue($Page->tipo_documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_tipo_documento" data-hidden="1" name="x_tipo_documento" id="x_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->codcli->Visible) { // codcli ?>
    <div id="r_codcli" class="form-group row">
        <label id="elh_view_facturas_a_entregar_codcli" for="x_codcli" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codcli->caption() ?><?= $Page->codcli->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->codcli->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_codcli">
<span<?= $Page->codcli->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->codcli->getDisplayValue($Page->codcli->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_codcli" data-hidden="1" name="x_codcli" id="x_codcli" value="<?= HtmlEncode($Page->codcli->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento" class="form-group row">
        <label id="elh_view_facturas_a_entregar_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->nro_documento->getDisplayValue($Page->nro_documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_nro_documento" data-hidden="1" name="x_nro_documento" id="x_nro_documento" value="<?= HtmlEncode($Page->nro_documento->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_view_facturas_a_entregar_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha->getDisplayValue($Page->fecha->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_fecha" data-hidden="1" name="x_fecha" id="x_fecha" value="<?= HtmlEncode($Page->fecha->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <div id="r_total" class="form-group row">
        <label id="elh_view_facturas_a_entregar_total" for="x_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->total->caption() ?><?= $Page->total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->total->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_total">
<span<?= $Page->total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->total->getDisplayValue($Page->total->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_total" data-hidden="1" name="x_total" id="x_total" value="<?= HtmlEncode($Page->total->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->entregado->Visible) { // entregado ?>
    <div id="r_entregado" class="form-group row">
        <label id="elh_view_facturas_a_entregar_entregado" for="x_entregado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->entregado->caption() ?><?= $Page->entregado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->entregado->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_entregado">
    <select
        id="x_entregado"
        name="x_entregado"
        class="form-control ew-select<?= $Page->entregado->isInvalidClass() ?>"
        data-select2-id="view_facturas_a_entregar_x_entregado"
        data-table="view_facturas_a_entregar"
        data-field="x_entregado"
        data-value-separator="<?= $Page->entregado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->entregado->getPlaceHolder()) ?>"
        <?= $Page->entregado->editAttributes() ?>>
        <?= $Page->entregado->selectOptionListHtml("x_entregado") ?>
    </select>
    <?= $Page->entregado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->entregado->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='view_facturas_a_entregar_x_entregado']"),
        options = { name: "x_entregado", selectId: "view_facturas_a_entregar_x_entregado", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.view_facturas_a_entregar.fields.entregado.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.view_facturas_a_entregar.fields.entregado.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_entrega->Visible) { // fecha_entrega ?>
    <div id="r_fecha_entrega" class="form-group row">
        <label id="elh_view_facturas_a_entregar_fecha_entrega" for="x_fecha_entrega" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_entrega->caption() ?><?= $Page->fecha_entrega->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha_entrega->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_fecha_entrega">
<input type="<?= $Page->fecha_entrega->getInputTextType() ?>" data-table="view_facturas_a_entregar" data-field="x_fecha_entrega" data-format="7" name="x_fecha_entrega" id="x_fecha_entrega" placeholder="<?= HtmlEncode($Page->fecha_entrega->getPlaceHolder()) ?>" value="<?= $Page->fecha_entrega->EditValue ?>"<?= $Page->fecha_entrega->editAttributes() ?> aria-describedby="x_fecha_entrega_help">
<?= $Page->fecha_entrega->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha_entrega->getErrorMessage() ?></div>
<?php if (!$Page->fecha_entrega->ReadOnly && !$Page->fecha_entrega->Disabled && !isset($Page->fecha_entrega->EditAttrs["readonly"]) && !isset($Page->fecha_entrega->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_facturas_a_entregaredit", "datetimepicker"], function() {
    ew.createDateTimePicker("fview_facturas_a_entregaredit", "x_fecha_entrega", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
    <div id="r_dias_credito" class="form-group row">
        <label id="elh_view_facturas_a_entregar_dias_credito" for="x_dias_credito" class="<?= $Page->LeftColumnClass ?>"><?= $Page->dias_credito->caption() ?><?= $Page->dias_credito->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->dias_credito->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_dias_credito">
<span<?= $Page->dias_credito->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->dias_credito->getDisplayValue($Page->dias_credito->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_dias_credito" data-hidden="1" name="x_dias_credito" id="x_dias_credito" value="<?= HtmlEncode($Page->dias_credito->CurrentValue) ?>">
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
    ew.addEventHandlers("view_facturas_a_entregar");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
