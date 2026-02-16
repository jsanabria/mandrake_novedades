<?php

namespace PHPMaker2021\mandrake;

// Page object
$EntradasAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fentradasadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fentradasadd = currentForm = new ew.Form("fentradasadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "entradas")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.entradas)
        ew.vars.tables.entradas = currentTable;
    fentradasadd.addFields([
        ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
        ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
        ["nro_control", [fields.nro_control.visible && fields.nro_control.required ? ew.Validators.required(fields.nro_control.caption) : null], fields.nro_control.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(7)], fields.fecha.isInvalid],
        ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
        ["documento", [fields.documento.visible && fields.documento.required ? ew.Validators.required(fields.documento.caption) : null], fields.documento.isInvalid],
        ["doc_afectado", [fields.doc_afectado.visible && fields.doc_afectado.required ? ew.Validators.required(fields.doc_afectado.caption) : null], fields.doc_afectado.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["aplica_retencion", [fields.aplica_retencion.visible && fields.aplica_retencion.required ? ew.Validators.required(fields.aplica_retencion.caption) : null], fields.aplica_retencion.isInvalid],
        ["ref_municipal", [fields.ref_municipal.visible && fields.ref_municipal.required ? ew.Validators.required(fields.ref_municipal.caption) : null], fields.ref_municipal.isInvalid],
        ["fecha_registro_retenciones", [fields.fecha_registro_retenciones.visible && fields.fecha_registro_retenciones.required ? ew.Validators.required(fields.fecha_registro_retenciones.caption) : null, ew.Validators.datetime(0)], fields.fecha_registro_retenciones.isInvalid],
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
        ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fentradasadd,
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
    fentradasadd.validate = function () {
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
    fentradasadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fentradasadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fentradasadd.lists.proveedor = <?= $Page->proveedor->toClientList($Page) ?>;
    fentradasadd.lists.documento = <?= $Page->documento->toClientList($Page) ?>;
    fentradasadd.lists.aplica_retencion = <?= $Page->aplica_retencion->toClientList($Page) ?>;
    fentradasadd.lists.cliente = <?= $Page->cliente->toClientList($Page) ?>;
    loadjs.done("fentradasadd");
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
<form name="fentradasadd" id="fentradasadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento" class="form-group row">
        <label id="elh_entradas_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_entradas_tipo_documento">
<input type="<?= $Page->tipo_documento->getInputTextType() ?>" data-table="entradas" data-field="x_tipo_documento" name="x_tipo_documento" id="x_tipo_documento" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->tipo_documento->getPlaceHolder()) ?>" value="<?= $Page->tipo_documento->EditValue ?>"<?= $Page->tipo_documento->editAttributes() ?> aria-describedby="x_tipo_documento_help">
<?= $Page->tipo_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tipo_documento->getErrorMessage() ?></div>
</span>
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
loadjs.ready(["fentradasadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fentradasadd", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
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
<div class="input-group ew-lookup-list" aria-describedby="x_proveedor_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_proveedor"><?= EmptyValue(strval($Page->proveedor->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->proveedor->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->proveedor->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->proveedor->ReadOnly || $Page->proveedor->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_proveedor',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->proveedor->getErrorMessage() ?></div>
<?= $Page->proveedor->getCustomMessage() ?>
<?= $Page->proveedor->Lookup->getParamTag($Page, "p_x_proveedor") ?>
<input type="hidden" is="selection-list" data-table="entradas" data-field="x_proveedor" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->proveedor->displayValueSeparatorAttribute() ?>" name="x_proveedor" id="x_proveedor" value="<?= $Page->proveedor->CurrentValue ?>"<?= $Page->proveedor->editAttributes() ?>>
</span>
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
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente" class="form-group row">
        <label id="elh_entradas_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cliente->cellAttributes() ?>>
<span id="el_entradas_cliente">
<div class="input-group ew-lookup-list" aria-describedby="x_cliente_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_cliente"><?= EmptyValue(strval($Page->cliente->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->cliente->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->cliente->ReadOnly || $Page->cliente->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_cliente',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage() ?></div>
<?= $Page->cliente->getCustomMessage() ?>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
<input type="hidden" is="selection-list" data-table="entradas" data-field="x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>" name="x_cliente" id="x_cliente" value="<?= $Page->cliente->CurrentValue ?>"<?= $Page->cliente->editAttributes() ?>>
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
<?php
    if (in_array("entradas_salidas", explode(",", $Page->getCurrentDetailTable())) && $entradas_salidas->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("entradas_salidas", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "EntradasSalidasGrid.php" ?>
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
    ew.addEventHandlers("entradas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    $( document ).ready(function() {
        $("#r_doc_afectado").hide();
        $("#r_fecha_registro_retenciones").hide();
        <?php
    	$tipo = $_REQUEST["tipo"];
        ?>
        $("#x_tipo_documento").val("<?php echo $tipo ?>");
        $('#x_tipo_documento').prop('readonly', true);

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
