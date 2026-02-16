<?php

namespace PHPMaker2021\mandrake;

// Page object
$EntradasEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fentradasedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fentradasedit = currentForm = new ew.Form("fentradasedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "entradas")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.entradas)
        ew.vars.tables.entradas = currentTable;
    fentradasedit.addFields([
        ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
        ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
        ["nro_control", [fields.nro_control.visible && fields.nro_control.required ? ew.Validators.required(fields.nro_control.caption) : null], fields.nro_control.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(7)], fields.fecha.isInvalid],
        ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
        ["monto_total", [fields.monto_total.visible && fields.monto_total.required ? ew.Validators.required(fields.monto_total.caption) : null], fields.monto_total.isInvalid],
        ["alicuota_iva", [fields.alicuota_iva.visible && fields.alicuota_iva.required ? ew.Validators.required(fields.alicuota_iva.caption) : null], fields.alicuota_iva.isInvalid],
        ["iva", [fields.iva.visible && fields.iva.required ? ew.Validators.required(fields.iva.caption) : null], fields.iva.isInvalid],
        ["total", [fields.total.visible && fields.total.required ? ew.Validators.required(fields.total.caption) : null], fields.total.isInvalid],
        ["documento", [fields.documento.visible && fields.documento.required ? ew.Validators.required(fields.documento.caption) : null], fields.documento.isInvalid],
        ["doc_afectado", [fields.doc_afectado.visible && fields.doc_afectado.required ? ew.Validators.required(fields.doc_afectado.caption) : null], fields.doc_afectado.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["estatus", [fields.estatus.visible && fields.estatus.required ? ew.Validators.required(fields.estatus.caption) : null], fields.estatus.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["aplica_retencion", [fields.aplica_retencion.visible && fields.aplica_retencion.required ? ew.Validators.required(fields.aplica_retencion.caption) : null], fields.aplica_retencion.isInvalid],
        ["ref_iva", [fields.ref_iva.visible && fields.ref_iva.required ? ew.Validators.required(fields.ref_iva.caption) : null], fields.ref_iva.isInvalid],
        ["ref_islr", [fields.ref_islr.visible && fields.ref_islr.required ? ew.Validators.required(fields.ref_islr.caption) : null], fields.ref_islr.isInvalid],
        ["ref_municipal", [fields.ref_municipal.visible && fields.ref_municipal.required ? ew.Validators.required(fields.ref_municipal.caption) : null], fields.ref_municipal.isInvalid],
        ["fecha_registro_retenciones", [fields.fecha_registro_retenciones.visible && fields.fecha_registro_retenciones.required ? ew.Validators.required(fields.fecha_registro_retenciones.caption) : null, ew.Validators.datetime(0)], fields.fecha_registro_retenciones.isInvalid],
        ["tasa_dia", [fields.tasa_dia.visible && fields.tasa_dia.required ? ew.Validators.required(fields.tasa_dia.caption) : null, ew.Validators.float], fields.tasa_dia.isInvalid],
        ["monto_usd", [fields.monto_usd.visible && fields.monto_usd.required ? ew.Validators.required(fields.monto_usd.caption) : null, ew.Validators.float], fields.monto_usd.isInvalid],
        ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fentradasedit,
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
    fentradasedit.validate = function () {
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
    fentradasedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fentradasedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fentradasedit.lists.documento = <?= $Page->documento->toClientList($Page) ?>;
    fentradasedit.lists.estatus = <?= $Page->estatus->toClientList($Page) ?>;
    fentradasedit.lists.moneda = <?= $Page->moneda->toClientList($Page) ?>;
    fentradasedit.lists.aplica_retencion = <?= $Page->aplica_retencion->toClientList($Page) ?>;
    loadjs.done("fentradasedit");
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
<form name="fentradasedit" id="fentradasedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento" class="form-group row">
        <label id="elh_entradas_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_entradas_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tipo_documento->getDisplayValue($Page->tipo_documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas" data-field="x_tipo_documento" data-hidden="1" name="x_tipo_documento" id="x_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento" class="form-group row">
        <label id="elh_entradas_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_entradas_nro_documento">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" data-table="entradas" data-field="x_nro_documento" name="x_nro_documento" id="x_nro_documento" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" value="<?= $Page->nro_documento->EditValue ?>"<?= $Page->nro_documento->editAttributes() ?> aria-describedby="x_nro_documento_help">
<?= $Page->nro_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <div id="r_nro_control" class="form-group row">
        <label id="elh_entradas_nro_control" for="x_nro_control" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_control->caption() ?><?= $Page->nro_control->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nro_control->cellAttributes() ?>>
<span id="el_entradas_nro_control">
<input type="<?= $Page->nro_control->getInputTextType() ?>" data-table="entradas" data-field="x_nro_control" name="x_nro_control" id="x_nro_control" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->nro_control->getPlaceHolder()) ?>" value="<?= $Page->nro_control->EditValue ?>"<?= $Page->nro_control->editAttributes() ?> aria-describedby="x_nro_control_help">
<?= $Page->nro_control->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_control->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_entradas_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_entradas_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="entradas" data-field="x_fecha" data-format="7" name="x_fecha" id="x_fecha" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fentradasedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fentradasedit", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <div id="r_proveedor" class="form-group row">
        <label id="elh_entradas_proveedor" for="x_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proveedor->caption() ?><?= $Page->proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->proveedor->cellAttributes() ?>>
<span id="el_entradas_proveedor">
<span<?= $Page->proveedor->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->proveedor->getDisplayValue($Page->proveedor->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas" data-field="x_proveedor" data-hidden="1" name="x_proveedor" id="x_proveedor" value="<?= HtmlEncode($Page->proveedor->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
    <div id="r_monto_total" class="form-group row">
        <label id="elh_entradas_monto_total" for="x_monto_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_total->caption() ?><?= $Page->monto_total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_total->cellAttributes() ?>>
<span id="el_entradas_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->monto_total->getDisplayValue($Page->monto_total->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas" data-field="x_monto_total" data-hidden="1" name="x_monto_total" id="x_monto_total" value="<?= HtmlEncode($Page->monto_total->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
    <div id="r_alicuota_iva" class="form-group row">
        <label id="elh_entradas_alicuota_iva" for="x_alicuota_iva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alicuota_iva->caption() ?><?= $Page->alicuota_iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el_entradas_alicuota_iva">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->alicuota_iva->getDisplayValue($Page->alicuota_iva->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas" data-field="x_alicuota_iva" data-hidden="1" name="x_alicuota_iva" id="x_alicuota_iva" value="<?= HtmlEncode($Page->alicuota_iva->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
    <div id="r_iva" class="form-group row">
        <label id="elh_entradas_iva" for="x_iva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iva->caption() ?><?= $Page->iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->iva->cellAttributes() ?>>
<span id="el_entradas_iva">
<span<?= $Page->iva->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->iva->getDisplayValue($Page->iva->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas" data-field="x_iva" data-hidden="1" name="x_iva" id="x_iva" value="<?= HtmlEncode($Page->iva->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <div id="r_total" class="form-group row">
        <label id="elh_entradas_total" for="x_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->total->caption() ?><?= $Page->total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->total->cellAttributes() ?>>
<span id="el_entradas_total">
<span<?= $Page->total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->total->getDisplayValue($Page->total->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas" data-field="x_total" data-hidden="1" name="x_total" id="x_total" value="<?= HtmlEncode($Page->total->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <div id="r_documento" class="form-group row">
        <label id="elh_entradas_documento" for="x_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->documento->caption() ?><?= $Page->documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->documento->cellAttributes() ?>>
<span id="el_entradas_documento">
    <select
        id="x_documento"
        name="x_documento"
        class="form-control ew-select<?= $Page->documento->isInvalidClass() ?>"
        data-select2-id="entradas_x_documento"
        data-table="entradas"
        data-field="x_documento"
        data-value-separator="<?= $Page->documento->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->documento->getPlaceHolder()) ?>"
        <?= $Page->documento->editAttributes() ?>>
        <?= $Page->documento->selectOptionListHtml("x_documento") ?>
    </select>
    <?= $Page->documento->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->documento->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='entradas_x_documento']"),
        options = { name: "x_documento", selectId: "entradas_x_documento", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.entradas.fields.documento.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.entradas.fields.documento.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <div id="r_doc_afectado" class="form-group row">
        <label id="elh_entradas_doc_afectado" for="x_doc_afectado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->doc_afectado->caption() ?><?= $Page->doc_afectado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_entradas_doc_afectado">
<input type="<?= $Page->doc_afectado->getInputTextType() ?>" data-table="entradas" data-field="x_doc_afectado" name="x_doc_afectado" id="x_doc_afectado" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->doc_afectado->getPlaceHolder()) ?>" value="<?= $Page->doc_afectado->EditValue ?>"<?= $Page->doc_afectado->editAttributes() ?> aria-describedby="x_doc_afectado_help">
<?= $Page->doc_afectado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->doc_afectado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota" class="form-group row">
        <label id="elh_entradas_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota->cellAttributes() ?>>
<span id="el_entradas_nota">
<textarea data-table="entradas" data-field="x_nota" name="x_nota" id="x_nota" cols="30" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <div id="r_estatus" class="form-group row">
        <label id="elh_entradas_estatus" for="x_estatus" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estatus->caption() ?><?= $Page->estatus->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->estatus->cellAttributes() ?>>
<span id="el_entradas_estatus">
    <select
        id="x_estatus"
        name="x_estatus"
        class="form-control ew-select<?= $Page->estatus->isInvalidClass() ?>"
        data-select2-id="entradas_x_estatus"
        data-table="entradas"
        data-field="x_estatus"
        data-value-separator="<?= $Page->estatus->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->estatus->getPlaceHolder()) ?>"
        <?= $Page->estatus->editAttributes() ?>>
        <?= $Page->estatus->selectOptionListHtml("x_estatus") ?>
    </select>
    <?= $Page->estatus->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->estatus->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='entradas_x_estatus']"),
        options = { name: "x_estatus", selectId: "entradas_x_estatus", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.entradas.fields.estatus.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.entradas.fields.estatus.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda" class="form-group row">
        <label id="elh_entradas_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->moneda->cellAttributes() ?>>
<span id="el_entradas_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-control ew-select<?= $Page->moneda->isInvalidClass() ?>"
        data-select2-id="entradas_x_moneda"
        data-table="entradas"
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
    var el = document.querySelector("select[data-select2-id='entradas_x_moneda']"),
        options = { name: "x_moneda", selectId: "entradas_x_moneda", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.entradas.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->aplica_retencion->Visible) { // aplica_retencion ?>
    <div id="r_aplica_retencion" class="form-group row">
        <label id="elh_entradas_aplica_retencion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->aplica_retencion->caption() ?><?= $Page->aplica_retencion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->aplica_retencion->cellAttributes() ?>>
<span id="el_entradas_aplica_retencion">
<template id="tp_x_aplica_retencion">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="entradas" data-field="x_aplica_retencion" name="x_aplica_retencion" id="x_aplica_retencion"<?= $Page->aplica_retencion->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_aplica_retencion" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_aplica_retencion"
    name="x_aplica_retencion"
    value="<?= HtmlEncode($Page->aplica_retencion->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_aplica_retencion"
    data-target="dsl_x_aplica_retencion"
    data-repeatcolumn="5"
    class="form-control<?= $Page->aplica_retencion->isInvalidClass() ?>"
    data-table="entradas"
    data-field="x_aplica_retencion"
    data-value-separator="<?= $Page->aplica_retencion->displayValueSeparatorAttribute() ?>"
    <?= $Page->aplica_retencion->editAttributes() ?>>
<?= $Page->aplica_retencion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->aplica_retencion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ref_iva->Visible) { // ref_iva ?>
    <div id="r_ref_iva" class="form-group row">
        <label id="elh_entradas_ref_iva" for="x_ref_iva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ref_iva->caption() ?><?= $Page->ref_iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ref_iva->cellAttributes() ?>>
<span id="el_entradas_ref_iva">
<input type="<?= $Page->ref_iva->getInputTextType() ?>" data-table="entradas" data-field="x_ref_iva" name="x_ref_iva" id="x_ref_iva" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ref_iva->getPlaceHolder()) ?>" value="<?= $Page->ref_iva->EditValue ?>"<?= $Page->ref_iva->editAttributes() ?> aria-describedby="x_ref_iva_help">
<?= $Page->ref_iva->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ref_iva->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ref_islr->Visible) { // ref_islr ?>
    <div id="r_ref_islr" class="form-group row">
        <label id="elh_entradas_ref_islr" for="x_ref_islr" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ref_islr->caption() ?><?= $Page->ref_islr->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ref_islr->cellAttributes() ?>>
<span id="el_entradas_ref_islr">
<input type="<?= $Page->ref_islr->getInputTextType() ?>" data-table="entradas" data-field="x_ref_islr" name="x_ref_islr" id="x_ref_islr" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ref_islr->getPlaceHolder()) ?>" value="<?= $Page->ref_islr->EditValue ?>"<?= $Page->ref_islr->editAttributes() ?> aria-describedby="x_ref_islr_help">
<?= $Page->ref_islr->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ref_islr->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ref_municipal->Visible) { // ref_municipal ?>
    <div id="r_ref_municipal" class="form-group row">
        <label id="elh_entradas_ref_municipal" for="x_ref_municipal" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ref_municipal->caption() ?><?= $Page->ref_municipal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ref_municipal->cellAttributes() ?>>
<span id="el_entradas_ref_municipal">
<input type="<?= $Page->ref_municipal->getInputTextType() ?>" data-table="entradas" data-field="x_ref_municipal" name="x_ref_municipal" id="x_ref_municipal" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ref_municipal->getPlaceHolder()) ?>" value="<?= $Page->ref_municipal->EditValue ?>"<?= $Page->ref_municipal->editAttributes() ?> aria-describedby="x_ref_municipal_help">
<?= $Page->ref_municipal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ref_municipal->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_registro_retenciones->Visible) { // fecha_registro_retenciones ?>
    <div id="r_fecha_registro_retenciones" class="form-group row">
        <label id="elh_entradas_fecha_registro_retenciones" for="x_fecha_registro_retenciones" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_registro_retenciones->caption() ?><?= $Page->fecha_registro_retenciones->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha_registro_retenciones->cellAttributes() ?>>
<span id="el_entradas_fecha_registro_retenciones">
<input type="<?= $Page->fecha_registro_retenciones->getInputTextType() ?>" data-table="entradas" data-field="x_fecha_registro_retenciones" name="x_fecha_registro_retenciones" id="x_fecha_registro_retenciones" placeholder="<?= HtmlEncode($Page->fecha_registro_retenciones->getPlaceHolder()) ?>" value="<?= $Page->fecha_registro_retenciones->EditValue ?>"<?= $Page->fecha_registro_retenciones->editAttributes() ?> aria-describedby="x_fecha_registro_retenciones_help">
<?= $Page->fecha_registro_retenciones->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha_registro_retenciones->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tasa_dia->Visible) { // tasa_dia ?>
    <div id="r_tasa_dia" class="form-group row">
        <label id="elh_entradas_tasa_dia" for="x_tasa_dia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tasa_dia->caption() ?><?= $Page->tasa_dia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tasa_dia->cellAttributes() ?>>
<span id="el_entradas_tasa_dia">
<input type="<?= $Page->tasa_dia->getInputTextType() ?>" data-table="entradas" data-field="x_tasa_dia" name="x_tasa_dia" id="x_tasa_dia" size="6" maxlength="14" placeholder="<?= HtmlEncode($Page->tasa_dia->getPlaceHolder()) ?>" value="<?= $Page->tasa_dia->EditValue ?>"<?= $Page->tasa_dia->editAttributes() ?> aria-describedby="x_tasa_dia_help">
<?= $Page->tasa_dia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tasa_dia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
    <div id="r_monto_usd" class="form-group row">
        <label id="elh_entradas_monto_usd" for="x_monto_usd" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_usd->caption() ?><?= $Page->monto_usd->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_usd->cellAttributes() ?>>
<span id="el_entradas_monto_usd">
<input type="<?= $Page->monto_usd->getInputTextType() ?>" data-table="entradas" data-field="x_monto_usd" name="x_monto_usd" id="x_monto_usd" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto_usd->getPlaceHolder()) ?>" value="<?= $Page->monto_usd->EditValue ?>"<?= $Page->monto_usd->editAttributes() ?> aria-describedby="x_monto_usd_help">
<?= $Page->monto_usd->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_usd->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <div id="r_descuento" class="form-group row">
        <label id="elh_entradas_descuento" for="x_descuento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descuento->caption() ?><?= $Page->descuento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->descuento->cellAttributes() ?>>
<span id="el_entradas_descuento">
<input type="<?= $Page->descuento->getInputTextType() ?>" data-table="entradas" data-field="x_descuento" name="x_descuento" id="x_descuento" size="10" maxlength="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" value="<?= $Page->descuento->EditValue ?>"<?= $Page->descuento->editAttributes() ?> aria-describedby="x_descuento_help">
<?= $Page->descuento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="entradas" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("entradas_salidas", explode(",", $Page->getCurrentDetailTable())) && $entradas_salidas->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("entradas_salidas", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "EntradasSalidasGrid.php" ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
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
    ew.addEventHandlers("entradas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $(document).ready(function() {
    	<?php
    	$tipo = $_REQUEST["tipo"];
    	switch($tipo) {
    	case "TDCPDC":
    		if(!VerificaFuncion('003')) {
    			echo '$("#r_estatus").hide()';
    		}
    		break;
    	case "TDCNRP":
    		if(!VerificaFuncion('004')) {
    			echo '$("#r_estatus").hide()';
    		}
    		break;
    	case "TDCFCC":
    		if(!VerificaFuncion('005')) {
    			echo '$("#r_estatus").hide()';
    		}
    		break;
    	case "TDCAEN":
    		if(!VerificaFuncion('009')) {
    			echo '$("#r_estatus").hide()';
    		}
    		break;
    	}
    	?>
    	//$("#x_nota").val("<?php echo CurrentUserName(); ?>");
    		var nivelgrupo = $("#xGroup").val();
    		if(nivelgrupo != -1) {
    			try {
    				for(i=1; i<=200; i++) {
    					$("#x" + i + "_precio_unidad_sin_desc").prop('readonly', true);
    					// $("#x" + i + "_descuento").prop('readonly', true);
    					$("#x" + i + "_costo_unidad").prop('readonly', true);
    					$("#x" + i + "_costo").prop('readonly', true);
    					$("#x" + i + "_descuento").prop('readonly', true);
    				}
    			}
    			catch(err) {
    				//alert("No indice");
    			}
    		}
    });
    $( document ).ready(function() {
    	if($("#x_documento").val() == "FC") {
        	$("#r_doc_afectado").hide();
        }
        else {
        	$("#r_doc_afectado").show();
        }
        $("#r_fecha_registro_retenciones").hide();

       	// $("#x_descuento").prop('readonly', true);
    });
    $("#x_documento").change(function() {
        if($("#x_documento").val() == "FC") {
        	$("#r_doc_afectado").hide();
        }
        else {
        	$("#r_doc_afectado").show();
        }
    });
});
</script>
