<?php

namespace PHPMaker2021\mandrake;

// Page object
$SalidasEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fsalidasedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fsalidasedit = currentForm = new ew.Form("fsalidasedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "salidas")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.salidas)
        ew.vars.tables.salidas = currentTable;
    fsalidasedit.addFields([
        ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
        ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
        ["nro_control", [fields.nro_control.visible && fields.nro_control.required ? ew.Validators.required(fields.nro_control.caption) : null], fields.nro_control.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(7)], fields.fecha.isInvalid],
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null, ew.Validators.integer], fields.cliente.isInvalid],
        ["documento", [fields.documento.visible && fields.documento.required ? ew.Validators.required(fields.documento.caption) : null], fields.documento.isInvalid],
        ["doc_afectado", [fields.doc_afectado.visible && fields.doc_afectado.required ? ew.Validators.required(fields.doc_afectado.caption) : null], fields.doc_afectado.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["monto_total", [fields.monto_total.visible && fields.monto_total.required ? ew.Validators.required(fields.monto_total.caption) : null], fields.monto_total.isInvalid],
        ["alicuota_iva", [fields.alicuota_iva.visible && fields.alicuota_iva.required ? ew.Validators.required(fields.alicuota_iva.caption) : null], fields.alicuota_iva.isInvalid],
        ["iva", [fields.iva.visible && fields.iva.required ? ew.Validators.required(fields.iva.caption) : null], fields.iva.isInvalid],
        ["total", [fields.total.visible && fields.total.required ? ew.Validators.required(fields.total.caption) : null], fields.total.isInvalid],
        ["tasa_dia", [fields.tasa_dia.visible && fields.tasa_dia.required ? ew.Validators.required(fields.tasa_dia.caption) : null], fields.tasa_dia.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["estatus", [fields.estatus.visible && fields.estatus.required ? ew.Validators.required(fields.estatus.caption) : null], fields.estatus.isInvalid],
        ["asesor", [fields.asesor.visible && fields.asesor.required ? ew.Validators.required(fields.asesor.caption) : null], fields.asesor.isInvalid],
        ["dias_credito", [fields.dias_credito.visible && fields.dias_credito.required ? ew.Validators.required(fields.dias_credito.caption) : null, ew.Validators.integer], fields.dias_credito.isInvalid],
        ["entregado", [fields.entregado.visible && fields.entregado.required ? ew.Validators.required(fields.entregado.caption) : null], fields.entregado.isInvalid],
        ["pagado", [fields.pagado.visible && fields.pagado.required ? ew.Validators.required(fields.pagado.caption) : null], fields.pagado.isInvalid],
        ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
        ["nro_despacho", [fields.nro_despacho.visible && fields.nro_despacho.required ? ew.Validators.required(fields.nro_despacho.caption) : null], fields.nro_despacho.isInvalid],
        ["impreso", [fields.impreso.visible && fields.impreso.required ? ew.Validators.required(fields.impreso.caption) : null], fields.impreso.isInvalid],
        ["igtf", [fields.igtf.visible && fields.igtf.required ? ew.Validators.required(fields.igtf.caption) : null], fields.igtf.isInvalid],
        ["monto_base_igtf", [fields.monto_base_igtf.visible && fields.monto_base_igtf.required ? ew.Validators.required(fields.monto_base_igtf.caption) : null, ew.Validators.float], fields.monto_base_igtf.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fsalidasedit,
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
    fsalidasedit.validate = function () {
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
    fsalidasedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsalidasedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fsalidasedit.lists.cliente = <?= $Page->cliente->toClientList($Page) ?>;
    fsalidasedit.lists.moneda = <?= $Page->moneda->toClientList($Page) ?>;
    fsalidasedit.lists.estatus = <?= $Page->estatus->toClientList($Page) ?>;
    fsalidasedit.lists.asesor = <?= $Page->asesor->toClientList($Page) ?>;
    fsalidasedit.lists.entregado = <?= $Page->entregado->toClientList($Page) ?>;
    fsalidasedit.lists.pagado = <?= $Page->pagado->toClientList($Page) ?>;
    fsalidasedit.lists.descuento = <?= $Page->descuento->toClientList($Page) ?>;
    fsalidasedit.lists.igtf = <?= $Page->igtf->toClientList($Page) ?>;
    loadjs.done("fsalidasedit");
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
<form name="fsalidasedit" id="fsalidasedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="salidas">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento" class="form-group row">
        <label id="elh_salidas_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_salidas_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tipo_documento->getDisplayValue($Page->tipo_documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="salidas" data-field="x_tipo_documento" data-hidden="1" name="x_tipo_documento" id="x_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento" class="form-group row">
        <label id="elh_salidas_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_salidas_nro_documento">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" data-table="salidas" data-field="x_nro_documento" name="x_nro_documento" id="x_nro_documento" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" value="<?= $Page->nro_documento->EditValue ?>"<?= $Page->nro_documento->editAttributes() ?> aria-describedby="x_nro_documento_help">
<?= $Page->nro_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <div id="r_nro_control" class="form-group row">
        <label id="elh_salidas_nro_control" for="x_nro_control" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_control->caption() ?><?= $Page->nro_control->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nro_control->cellAttributes() ?>>
<span id="el_salidas_nro_control">
<input type="<?= $Page->nro_control->getInputTextType() ?>" data-table="salidas" data-field="x_nro_control" name="x_nro_control" id="x_nro_control" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->nro_control->getPlaceHolder()) ?>" value="<?= $Page->nro_control->EditValue ?>"<?= $Page->nro_control->editAttributes() ?> aria-describedby="x_nro_control_help">
<?= $Page->nro_control->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_control->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_salidas_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_salidas_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="salidas" data-field="x_fecha" data-format="7" name="x_fecha" id="x_fecha" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsalidasedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fsalidasedit", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente" class="form-group row">
        <label id="elh_salidas_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cliente->cellAttributes() ?>>
<span id="el_salidas_cliente">
<?php
$onchange = $Page->cliente->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->cliente->EditAttrs["onchange"] = "";
?>
<span id="as_x_cliente" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->cliente->getInputTextType() ?>" class="form-control" name="sv_x_cliente" id="sv_x_cliente" value="<?= RemoveHtml($Page->cliente->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>"<?= $Page->cliente->editAttributes() ?> aria-describedby="x_cliente_help">
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_cliente',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->cliente->ReadOnly || $Page->cliente->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
            <?php if (AllowAdd(CurrentProjectID() . "cliente") && !$Page->cliente->ReadOnly) { ?>
            <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_cliente" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->cliente->caption() ?>" data-title="<?= $Page->cliente->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_cliente',url:'<?= GetUrl("ClienteAddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button>
            <?php } ?>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="salidas" data-field="x_cliente" data-input="sv_x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>" name="x_cliente" id="x_cliente" value="<?= HtmlEncode($Page->cliente->CurrentValue) ?>"<?= $onchange ?>>
<?= $Page->cliente->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsalidasedit"], function() {
    fsalidasedit.createAutoSuggest(Object.assign({"id":"x_cliente","forceSelect":true}, ew.vars.tables.salidas.fields.cliente.autoSuggestOptions));
});
</script>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <div id="r_documento" class="form-group row">
        <label id="elh_salidas_documento" for="x_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->documento->caption() ?><?= $Page->documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->documento->cellAttributes() ?>>
<span id="el_salidas_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->documento->getDisplayValue($Page->documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="salidas" data-field="x_documento" data-hidden="1" name="x_documento" id="x_documento" value="<?= HtmlEncode($Page->documento->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <div id="r_doc_afectado" class="form-group row">
        <label id="elh_salidas_doc_afectado" for="x_doc_afectado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->doc_afectado->caption() ?><?= $Page->doc_afectado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_salidas_doc_afectado">
<input type="<?= $Page->doc_afectado->getInputTextType() ?>" data-table="salidas" data-field="x_doc_afectado" name="x_doc_afectado" id="x_doc_afectado" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->doc_afectado->getPlaceHolder()) ?>" value="<?= $Page->doc_afectado->EditValue ?>"<?= $Page->doc_afectado->editAttributes() ?> aria-describedby="x_doc_afectado_help">
<?= $Page->doc_afectado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->doc_afectado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda" class="form-group row">
        <label id="elh_salidas_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->moneda->cellAttributes() ?>>
<span id="el_salidas_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-control ew-select<?= $Page->moneda->isInvalidClass() ?>"
        data-select2-id="salidas_x_moneda"
        data-table="salidas"
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
    var el = document.querySelector("select[data-select2-id='salidas_x_moneda']"),
        options = { name: "x_moneda", selectId: "salidas_x_moneda", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.salidas.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
    <div id="r_monto_total" class="form-group row">
        <label id="elh_salidas_monto_total" for="x_monto_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_total->caption() ?><?= $Page->monto_total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_total->cellAttributes() ?>>
<span id="el_salidas_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->monto_total->getDisplayValue($Page->monto_total->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="salidas" data-field="x_monto_total" data-hidden="1" name="x_monto_total" id="x_monto_total" value="<?= HtmlEncode($Page->monto_total->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
    <div id="r_alicuota_iva" class="form-group row">
        <label id="elh_salidas_alicuota_iva" for="x_alicuota_iva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alicuota_iva->caption() ?><?= $Page->alicuota_iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el_salidas_alicuota_iva">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->alicuota_iva->getDisplayValue($Page->alicuota_iva->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="salidas" data-field="x_alicuota_iva" data-hidden="1" name="x_alicuota_iva" id="x_alicuota_iva" value="<?= HtmlEncode($Page->alicuota_iva->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
    <div id="r_iva" class="form-group row">
        <label id="elh_salidas_iva" for="x_iva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iva->caption() ?><?= $Page->iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->iva->cellAttributes() ?>>
<span id="el_salidas_iva">
<span<?= $Page->iva->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->iva->getDisplayValue($Page->iva->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="salidas" data-field="x_iva" data-hidden="1" name="x_iva" id="x_iva" value="<?= HtmlEncode($Page->iva->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <div id="r_total" class="form-group row">
        <label id="elh_salidas_total" for="x_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->total->caption() ?><?= $Page->total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->total->cellAttributes() ?>>
<span id="el_salidas_total">
<span<?= $Page->total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->total->getDisplayValue($Page->total->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="salidas" data-field="x_total" data-hidden="1" name="x_total" id="x_total" value="<?= HtmlEncode($Page->total->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tasa_dia->Visible) { // tasa_dia ?>
    <div id="r_tasa_dia" class="form-group row">
        <label id="elh_salidas_tasa_dia" for="x_tasa_dia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tasa_dia->caption() ?><?= $Page->tasa_dia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tasa_dia->cellAttributes() ?>>
<span id="el_salidas_tasa_dia">
<span<?= $Page->tasa_dia->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tasa_dia->getDisplayValue($Page->tasa_dia->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="salidas" data-field="x_tasa_dia" data-hidden="1" name="x_tasa_dia" id="x_tasa_dia" value="<?= HtmlEncode($Page->tasa_dia->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota" class="form-group row">
        <label id="elh_salidas_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota->cellAttributes() ?>>
<span id="el_salidas_nota">
<textarea data-table="salidas" data-field="x_nota" name="x_nota" id="x_nota" cols="30" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <div id="r_estatus" class="form-group row">
        <label id="elh_salidas_estatus" for="x_estatus" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estatus->caption() ?><?= $Page->estatus->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->estatus->cellAttributes() ?>>
<span id="el_salidas_estatus">
    <select
        id="x_estatus"
        name="x_estatus"
        class="form-control ew-select<?= $Page->estatus->isInvalidClass() ?>"
        data-select2-id="salidas_x_estatus"
        data-table="salidas"
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
    var el = document.querySelector("select[data-select2-id='salidas_x_estatus']"),
        options = { name: "x_estatus", selectId: "salidas_x_estatus", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.salidas.fields.estatus.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.salidas.fields.estatus.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
    <div id="r_asesor" class="form-group row">
        <label id="elh_salidas_asesor" for="x_asesor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->asesor->caption() ?><?= $Page->asesor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->asesor->cellAttributes() ?>>
<span id="el_salidas_asesor">
    <select
        id="x_asesor"
        name="x_asesor"
        class="form-control ew-select<?= $Page->asesor->isInvalidClass() ?>"
        data-select2-id="salidas_x_asesor"
        data-table="salidas"
        data-field="x_asesor"
        data-value-separator="<?= $Page->asesor->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->asesor->getPlaceHolder()) ?>"
        <?= $Page->asesor->editAttributes() ?>>
        <?= $Page->asesor->selectOptionListHtml("x_asesor") ?>
    </select>
    <?= $Page->asesor->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->asesor->getErrorMessage() ?></div>
<?= $Page->asesor->Lookup->getParamTag($Page, "p_x_asesor") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='salidas_x_asesor']"),
        options = { name: "x_asesor", selectId: "salidas_x_asesor", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.salidas.fields.asesor.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
    <div id="r_dias_credito" class="form-group row">
        <label id="elh_salidas_dias_credito" for="x_dias_credito" class="<?= $Page->LeftColumnClass ?>"><?= $Page->dias_credito->caption() ?><?= $Page->dias_credito->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->dias_credito->cellAttributes() ?>>
<span id="el_salidas_dias_credito">
<input type="<?= $Page->dias_credito->getInputTextType() ?>" data-table="salidas" data-field="x_dias_credito" name="x_dias_credito" id="x_dias_credito" size="10" placeholder="<?= HtmlEncode($Page->dias_credito->getPlaceHolder()) ?>" value="<?= $Page->dias_credito->EditValue ?>"<?= $Page->dias_credito->editAttributes() ?> aria-describedby="x_dias_credito_help">
<?= $Page->dias_credito->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->dias_credito->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->entregado->Visible) { // entregado ?>
    <div id="r_entregado" class="form-group row">
        <label id="elh_salidas_entregado" for="x_entregado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->entregado->caption() ?><?= $Page->entregado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->entregado->cellAttributes() ?>>
<span id="el_salidas_entregado">
    <select
        id="x_entregado"
        name="x_entregado"
        class="form-control ew-select<?= $Page->entregado->isInvalidClass() ?>"
        data-select2-id="salidas_x_entregado"
        data-table="salidas"
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
    var el = document.querySelector("select[data-select2-id='salidas_x_entregado']"),
        options = { name: "x_entregado", selectId: "salidas_x_entregado", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.salidas.fields.entregado.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.salidas.fields.entregado.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pagado->Visible) { // pagado ?>
    <div id="r_pagado" class="form-group row">
        <label id="elh_salidas_pagado" for="x_pagado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pagado->caption() ?><?= $Page->pagado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pagado->cellAttributes() ?>>
<span id="el_salidas_pagado">
    <select
        id="x_pagado"
        name="x_pagado"
        class="form-control ew-select<?= $Page->pagado->isInvalidClass() ?>"
        data-select2-id="salidas_x_pagado"
        data-table="salidas"
        data-field="x_pagado"
        data-value-separator="<?= $Page->pagado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->pagado->getPlaceHolder()) ?>"
        <?= $Page->pagado->editAttributes() ?>>
        <?= $Page->pagado->selectOptionListHtml("x_pagado") ?>
    </select>
    <?= $Page->pagado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->pagado->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='salidas_x_pagado']"),
        options = { name: "x_pagado", selectId: "salidas_x_pagado", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.salidas.fields.pagado.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.salidas.fields.pagado.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <div id="r_descuento" class="form-group row">
        <label id="elh_salidas_descuento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descuento->caption() ?><?= $Page->descuento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->descuento->cellAttributes() ?>>
<span id="el_salidas_descuento">
<?php
$onchange = $Page->descuento->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->descuento->EditAttrs["onchange"] = "";
?>
<span id="as_x_descuento" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->descuento->getInputTextType() ?>" class="form-control" name="sv_x_descuento" id="sv_x_descuento" value="<?= RemoveHtml($Page->descuento->EditValue) ?>" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>"<?= $Page->descuento->editAttributes() ?> aria-describedby="x_descuento_help">
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->descuento->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_descuento',m:0,n:10,srch:true});" class="ew-lookup-btn btn btn-default"<?= ($Page->descuento->ReadOnly || $Page->descuento->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="salidas" data-field="x_descuento" data-input="sv_x_descuento" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->descuento->displayValueSeparatorAttribute() ?>" name="x_descuento" id="x_descuento" value="<?= HtmlEncode($Page->descuento->CurrentValue) ?>"<?= $onchange ?>>
<?= $Page->descuento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsalidasedit"], function() {
    fsalidasedit.createAutoSuggest(Object.assign({"id":"x_descuento","forceSelect":false}, ew.vars.tables.salidas.fields.descuento.autoSuggestOptions));
});
</script>
<?= $Page->descuento->Lookup->getParamTag($Page, "p_x_descuento") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
    <div id="r_nro_despacho" class="form-group row">
        <label id="elh_salidas_nro_despacho" for="x_nro_despacho" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_despacho->caption() ?><?= $Page->nro_despacho->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nro_despacho->cellAttributes() ?>>
<span id="el_salidas_nro_despacho">
<input type="<?= $Page->nro_despacho->getInputTextType() ?>" data-table="salidas" data-field="x_nro_despacho" name="x_nro_despacho" id="x_nro_despacho" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_despacho->getPlaceHolder()) ?>" value="<?= $Page->nro_despacho->EditValue ?>"<?= $Page->nro_despacho->editAttributes() ?> aria-describedby="x_nro_despacho_help">
<?= $Page->nro_despacho->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_despacho->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->impreso->Visible) { // impreso ?>
    <div id="r_impreso" class="form-group row">
        <label id="elh_salidas_impreso" class="<?= $Page->LeftColumnClass ?>"><?= $Page->impreso->caption() ?><?= $Page->impreso->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->impreso->cellAttributes() ?>>
<span id="el_salidas_impreso">
<span<?= $Page->impreso->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->impreso->getDisplayValue($Page->impreso->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="salidas" data-field="x_impreso" data-hidden="1" name="x_impreso" id="x_impreso" value="<?= HtmlEncode($Page->impreso->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->igtf->Visible) { // igtf ?>
    <div id="r_igtf" class="form-group row">
        <label id="elh_salidas_igtf" for="x_igtf" class="<?= $Page->LeftColumnClass ?>"><?= $Page->igtf->caption() ?><?= $Page->igtf->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->igtf->cellAttributes() ?>>
<span id="el_salidas_igtf">
    <select
        id="x_igtf"
        name="x_igtf"
        class="form-control ew-select<?= $Page->igtf->isInvalidClass() ?>"
        data-select2-id="salidas_x_igtf"
        data-table="salidas"
        data-field="x_igtf"
        data-value-separator="<?= $Page->igtf->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->igtf->getPlaceHolder()) ?>"
        <?= $Page->igtf->editAttributes() ?>>
        <?= $Page->igtf->selectOptionListHtml("x_igtf") ?>
    </select>
    <?= $Page->igtf->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->igtf->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='salidas_x_igtf']"),
        options = { name: "x_igtf", selectId: "salidas_x_igtf", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.salidas.fields.igtf.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.salidas.fields.igtf.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_base_igtf->Visible) { // monto_base_igtf ?>
    <div id="r_monto_base_igtf" class="form-group row">
        <label id="elh_salidas_monto_base_igtf" for="x_monto_base_igtf" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_base_igtf->caption() ?><?= $Page->monto_base_igtf->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_base_igtf->cellAttributes() ?>>
<span id="el_salidas_monto_base_igtf">
<input type="<?= $Page->monto_base_igtf->getInputTextType() ?>" data-table="salidas" data-field="x_monto_base_igtf" name="x_monto_base_igtf" id="x_monto_base_igtf" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto_base_igtf->getPlaceHolder()) ?>" value="<?= $Page->monto_base_igtf->EditValue ?>"<?= $Page->monto_base_igtf->editAttributes() ?> aria-describedby="x_monto_base_igtf_help">
<?= $Page->monto_base_igtf->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_base_igtf->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="salidas" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("entradas_salidas", explode(",", $Page->getCurrentDetailTable())) && $entradas_salidas->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("entradas_salidas", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "EntradasSalidasGrid.php" ?>
<?php } ?>
<?php
    if (in_array("pagos", explode(",", $Page->getCurrentDetailTable())) && $pagos->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("pagos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "PagosGrid.php" ?>
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
    ew.addEventHandlers("salidas");
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
    	case "TDCPDV":
    		if(!VerificaFuncion('006')) {
    			echo '$("#r_estatus").hide()';
    		}
    		break;
    	case "TDCNET":
    		if(!VerificaFuncion('007')) {
    			echo '$("#r_estatus").hide()';
    		}
    		break;
    	case "TDCFCV":
    		if(!VerificaFuncion('008')) {
    			echo '$("#r_estatus").hide()';
    		}
    		break;
    	case "TDCASA":
    		if(!VerificaFuncion('010')) {
    			echo '$("#r_estatus").hide()';
    		}
    		break;
    	}
    	?>

    	// alert("Es <?php echo $tipo; ?> y asi también <?php echo CurrentPage()->tipo_documento->CurrentValue ?>")
    	//$("#x_nota").val("<?php echo CurrentUserName(); ?>");
    	$('#sv_x_descuento').attr('readonly', true);
    });
    $("#btnCerrar").click(function() {
    	var xCtrl = $("#txtCtrl").val();
    	var xCtrl2 = $("#txtCtrl2").val();
    	//alert(xCtrl);
    	if(xCtrl2 == "YES") {
    		$("#sv_x_descuento").val("");
    		$("#x_descuento").val("");
    	}
    	else {
    		$("#" + xCtrl).val("");
    		$("#sv_" + xCtrl).val("");
    		$("#" + xCtrl.replace("precio_unidad", "precio")).val("");
    	}
    	/* Para saber si existe en el DOM
        var testData = document.getElementById('eex_tasa_dia');
        alert(testData); */
    });
    $("#btnAceptar").click(function() {
    	var xCtrl = $("#txtCtrl").val();
    	var xuser = $("#xusername").val();
    	var xpass = $("#xpassword").val();
    	var xCtrl2 = $("#txtCtrl2").val();
    	var nota_entrega = $("#x_nro_documento").val();
    	if(xCtrl2 == "YES")
    		var articulo = 0;
    	else 
    		var articulo = $("#" + xCtrl.replace("precio_unidad", "articulo")).val();
    	var usercaja = "<?php echo CurrentUserName(); ?>";
    	$.ajax({
    	  url : "include/Validar_Usuario.php",
    	  type: "GET",
    	  data : {usernama: xuser, password: xpass, nota_entrega: nota_entrega, articulo: articulo, usercaja: usercaja},
    	  beforeSend: function(){
    	  }
    	})
    	.done(function(MyResult) {
    		if(MyResult == "N") {
    			if(xCtrl2 == "YES") {
    				$("#sv_x_descuento").val("");
    				$("#x_descuento").val("");
    			}
    			else {
    				$("#" + xCtrl).val("");
    				$("#sv_" + xCtrl).val("");
    				$("#" + xCtrl.replace("precio_unidad", "precio")).val("");
    			}
    			alert("!!! NO AUTORIZADO !!!");
    			/*
    			$("#" + xCtrl).val("");
    			$("#sv_" + xCtrl).val("");
    			$("#" + xCtrl.replace("precio_unidad", "precio")).val("");
    			alert("!!! NO AUTORIZADO !!!");
    			*/
    		}
    		$('#ventanaModal').modal('hide');
    		$("#xusername").val("");
    		$("#xpassword").val("");
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    	});
    });

    // Usamos el selector de PHPMaker para el campo pago_divisa
    $("#x_pago_divisa").on("change", function() {
        var valor = $(this).val();

        // Verifica si el valor es "S" o el que desees para bloquearlo
        if (valor === "S" || valor === "N") { 
            $(this).prop("disabled", true); // Bloquea el control

            // Opcional: PHPMaker recomienda usar .readonly() si usas librerías Select2
            // $(this).attr("readonly", "readonly"); 
            console.log("Campo pago_divisa bloqueado por selección.");
        }
    });

    //////////////////////////////////////////////////////////////////////////////////
    // Aseguramos que la función seleccionarPrecio esté disponible
    // 1. Monitorear el cambio del artículo (Evento oficial de PHPMaker)
    $(document).on("change", "input[id*='_cantidad_articulo']", function() {
        var $el = $(this);
        var cantidad = $el.val();
        var idFull = $el.attr("id"); 
        var rowIdx = idFull.replace(/[^0-9]/g, '');
        var idArticulo = $("#x" + rowIdx + "_articulo").val();

        // Si hay artículo y ya pusieron una cantidad, lanzamos el modal
        if (idArticulo && cantidad > 0) {
            seleccionarPrecio(rowIdx, idArticulo);
        }
    });

    // 2. Función de selección de precios
    function seleccionarPrecio(rowIdx, idArticulo) {
        var pagoDivisa = $("#x_pago_divisa").val(); 
        $.get("include/get_precios.php?id=" + idArticulo, function(data) {
            var precios = JSON.parse(data);
            if (!precios) return;
            Swal.fire({
                title: 'Seleccione un Precio',
                html: `
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-lg btn-precio" 
                            data-valor="${precios.precio}" ${pagoDivisa === 'S' ? 'disabled' : ''}>
                            Precio a pagar por unidad en Bs.: ${precios.precio}
                        </button>
                        <button type="button" class="btn btn-secondary btn-lg btn-precio" 
                            data-valor="${precios.precio2}" ${pagoDivisa !== 'S' ? 'disabled' : ''}>
                            Precio a pagar por unidad USD: ${precios.precio2}
                        </button>
                    </div>
                `,
                showConfirmButton: false,
                didOpen: () => {
    // Dentro de .btn-precio click:
    $(".btn-precio").on("click", function() {
        var precioElegido = $(this).data("valor");
        var $inputPrecio = $("#x" + rowIdx + "_precio_unidad");

        // 1. Asignamos y disparamos tus validaciones (AJAX Clave, etc.)
        // $inputPrecio.val(precioElegido).trigger("change");
        Swal.close();

        // 2. Foco de vuelta al precio para edición final
        setTimeout(function() {
            $inputPrecio.focus().select();
        }, 300);
    });             
                }
            });
        });
    }
});
</script>
