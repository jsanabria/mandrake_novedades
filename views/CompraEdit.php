<?php

namespace PHPMaker2021\mandrake;

// Page object
$CompraEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcompraedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fcompraedit = currentForm = new ew.Form("fcompraedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "compra")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.compra)
        ew.vars.tables.compra = currentTable;
    fcompraedit.addFields([
        ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
        ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
        ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
        ["doc_afectado", [fields.doc_afectado.visible && fields.doc_afectado.required ? ew.Validators.required(fields.doc_afectado.caption) : null], fields.doc_afectado.isInvalid],
        ["documento", [fields.documento.visible && fields.documento.required ? ew.Validators.required(fields.documento.caption) : null], fields.documento.isInvalid],
        ["nro_control", [fields.nro_control.visible && fields.nro_control.required ? ew.Validators.required(fields.nro_control.caption) : null], fields.nro_control.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(7)], fields.fecha.isInvalid],
        ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
        ["monto_exento", [fields.monto_exento.visible && fields.monto_exento.required ? ew.Validators.required(fields.monto_exento.caption) : null, ew.Validators.float], fields.monto_exento.isInvalid],
        ["monto_gravado", [fields.monto_gravado.visible && fields.monto_gravado.required ? ew.Validators.required(fields.monto_gravado.caption) : null, ew.Validators.float], fields.monto_gravado.isInvalid],
        ["alicuota", [fields.alicuota.visible && fields.alicuota.required ? ew.Validators.required(fields.alicuota.caption) : null, ew.Validators.float], fields.alicuota.isInvalid],
        ["ref_iva", [fields.ref_iva.visible && fields.ref_iva.required ? ew.Validators.required(fields.ref_iva.caption) : null], fields.ref_iva.isInvalid],
        ["ref_islr", [fields.ref_islr.visible && fields.ref_islr.required ? ew.Validators.required(fields.ref_islr.caption) : null], fields.ref_islr.isInvalid],
        ["ref_municipal", [fields.ref_municipal.visible && fields.ref_municipal.required ? ew.Validators.required(fields.ref_municipal.caption) : null], fields.ref_municipal.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcompraedit,
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
    fcompraedit.validate = function () {
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
    fcompraedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcompraedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Multi-Page
    fcompraedit.multiPage = new ew.MultiPage("fcompraedit");

    // Dynamic selection lists
    fcompraedit.lists.proveedor = <?= $Page->proveedor->toClientList($Page) ?>;
    fcompraedit.lists.tipo_documento = <?= $Page->tipo_documento->toClientList($Page) ?>;
    loadjs.done("fcompraedit");
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
<form name="fcompraedit" id="fcompraedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="compra">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav-tabs" id="Page"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navStyle() ?>">
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(1) ?>" href="#tab_compra1" data-toggle="tab"><?= $Page->pageCaption(1) ?></a></li>
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(2) ?>" href="#tab_compra2" data-toggle="tab"><?= $Page->pageCaption(2) ?></a></li>
    </ul>
    <div class="tab-content"><!-- multi-page tabs .tab-content -->
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(1) ?>" id="tab_compra1"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id" class="form-group row">
        <label id="elh_compra_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id->cellAttributes() ?>>
<span id="el_compra_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="compra" data-field="x_id" data-hidden="1" data-page="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <div id="r_proveedor" class="form-group row">
        <label id="elh_compra_proveedor" for="x_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proveedor->caption() ?><?= $Page->proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->proveedor->cellAttributes() ?>>
<span id="el_compra_proveedor">
<div class="input-group ew-lookup-list" aria-describedby="x_proveedor_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_proveedor"><?= EmptyValue(strval($Page->proveedor->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->proveedor->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->proveedor->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->proveedor->ReadOnly || $Page->proveedor->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_proveedor',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
        <?php if (AllowAdd(CurrentProjectID() . "proveedor") && !$Page->proveedor->ReadOnly) { ?>
        <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_proveedor" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->proveedor->caption() ?>" data-title="<?= $Page->proveedor->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_proveedor',url:'<?= GetUrl("ProveedorAddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button>
        <?php } ?>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->proveedor->getErrorMessage() ?></div>
<?= $Page->proveedor->getCustomMessage() ?>
<?= $Page->proveedor->Lookup->getParamTag($Page, "p_x_proveedor") ?>
<input type="hidden" is="selection-list" data-table="compra" data-field="x_proveedor" data-page="1" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->proveedor->displayValueSeparatorAttribute() ?>" name="x_proveedor" id="x_proveedor" value="<?= $Page->proveedor->CurrentValue ?>"<?= $Page->proveedor->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento" class="form-group row">
        <label id="elh_compra_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_compra_tipo_documento">
    <select
        id="x_tipo_documento"
        name="x_tipo_documento"
        class="form-control ew-select<?= $Page->tipo_documento->isInvalidClass() ?>"
        data-select2-id="compra_x_tipo_documento"
        data-table="compra"
        data-field="x_tipo_documento"
        data-page="1"
        data-value-separator="<?= $Page->tipo_documento->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_documento->getPlaceHolder()) ?>"
        <?= $Page->tipo_documento->editAttributes() ?>>
        <?= $Page->tipo_documento->selectOptionListHtml("x_tipo_documento") ?>
    </select>
    <?= $Page->tipo_documento->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_documento->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compra_x_tipo_documento']"),
        options = { name: "x_tipo_documento", selectId: "compra_x_tipo_documento", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compra.fields.tipo_documento.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compra.fields.tipo_documento.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <div id="r_doc_afectado" class="form-group row">
        <label id="elh_compra_doc_afectado" for="x_doc_afectado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->doc_afectado->caption() ?><?= $Page->doc_afectado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_compra_doc_afectado">
<input type="<?= $Page->doc_afectado->getInputTextType() ?>" data-table="compra" data-field="x_doc_afectado" data-page="1" name="x_doc_afectado" id="x_doc_afectado" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->doc_afectado->getPlaceHolder()) ?>" value="<?= $Page->doc_afectado->EditValue ?>"<?= $Page->doc_afectado->editAttributes() ?> aria-describedby="x_doc_afectado_help">
<?= $Page->doc_afectado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->doc_afectado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <div id="r_documento" class="form-group row">
        <label id="elh_compra_documento" for="x_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->documento->caption() ?><?= $Page->documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->documento->cellAttributes() ?>>
<span id="el_compra_documento">
<input type="<?= $Page->documento->getInputTextType() ?>" data-table="compra" data-field="x_documento" data-page="1" name="x_documento" id="x_documento" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->documento->getPlaceHolder()) ?>" value="<?= $Page->documento->EditValue ?>"<?= $Page->documento->editAttributes() ?> aria-describedby="x_documento_help">
<?= $Page->documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <div id="r_nro_control" class="form-group row">
        <label id="elh_compra_nro_control" for="x_nro_control" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_control->caption() ?><?= $Page->nro_control->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nro_control->cellAttributes() ?>>
<span id="el_compra_nro_control">
<input type="<?= $Page->nro_control->getInputTextType() ?>" data-table="compra" data-field="x_nro_control" data-page="1" name="x_nro_control" id="x_nro_control" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_control->getPlaceHolder()) ?>" value="<?= $Page->nro_control->EditValue ?>"<?= $Page->nro_control->editAttributes() ?> aria-describedby="x_nro_control_help">
<?= $Page->nro_control->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_control->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_compra_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_compra_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="compra" data-field="x_fecha" data-page="1" data-format="7" name="x_fecha" id="x_fecha" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcompraedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fcompraedit", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion" class="form-group row">
        <label id="elh_compra_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->descripcion->cellAttributes() ?>>
<span id="el_compra_descripcion">
<textarea data-table="compra" data-field="x_descripcion" data-page="1" name="x_descripcion" id="x_descripcion" cols="30" rows="3" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help"><?= $Page->descripcion->EditValue ?></textarea>
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(2) ?>" id="tab_compra2"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->monto_exento->Visible) { // monto_exento ?>
    <div id="r_monto_exento" class="form-group row">
        <label id="elh_compra_monto_exento" for="x_monto_exento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_exento->caption() ?><?= $Page->monto_exento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_exento->cellAttributes() ?>>
<span id="el_compra_monto_exento">
<input type="<?= $Page->monto_exento->getInputTextType() ?>" data-table="compra" data-field="x_monto_exento" data-page="2" name="x_monto_exento" id="x_monto_exento" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto_exento->getPlaceHolder()) ?>" value="<?= $Page->monto_exento->EditValue ?>"<?= $Page->monto_exento->editAttributes() ?> aria-describedby="x_monto_exento_help">
<?= $Page->monto_exento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_exento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_gravado->Visible) { // monto_gravado ?>
    <div id="r_monto_gravado" class="form-group row">
        <label id="elh_compra_monto_gravado" for="x_monto_gravado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_gravado->caption() ?><?= $Page->monto_gravado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_gravado->cellAttributes() ?>>
<span id="el_compra_monto_gravado">
<input type="<?= $Page->monto_gravado->getInputTextType() ?>" data-table="compra" data-field="x_monto_gravado" data-page="2" name="x_monto_gravado" id="x_monto_gravado" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto_gravado->getPlaceHolder()) ?>" value="<?= $Page->monto_gravado->EditValue ?>"<?= $Page->monto_gravado->editAttributes() ?> aria-describedby="x_monto_gravado_help">
<?= $Page->monto_gravado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_gravado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alicuota->Visible) { // alicuota ?>
    <div id="r_alicuota" class="form-group row">
        <label id="elh_compra_alicuota" for="x_alicuota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alicuota->caption() ?><?= $Page->alicuota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->alicuota->cellAttributes() ?>>
<span id="el_compra_alicuota">
<input type="<?= $Page->alicuota->getInputTextType() ?>" data-table="compra" data-field="x_alicuota" data-page="2" name="x_alicuota" id="x_alicuota" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->alicuota->getPlaceHolder()) ?>" value="<?= $Page->alicuota->EditValue ?>"<?= $Page->alicuota->editAttributes() ?> aria-describedby="x_alicuota_help">
<?= $Page->alicuota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->alicuota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ref_iva->Visible) { // ref_iva ?>
    <div id="r_ref_iva" class="form-group row">
        <label id="elh_compra_ref_iva" for="x_ref_iva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ref_iva->caption() ?><?= $Page->ref_iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ref_iva->cellAttributes() ?>>
<span id="el_compra_ref_iva">
<input type="<?= $Page->ref_iva->getInputTextType() ?>" data-table="compra" data-field="x_ref_iva" data-page="2" name="x_ref_iva" id="x_ref_iva" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ref_iva->getPlaceHolder()) ?>" value="<?= $Page->ref_iva->EditValue ?>"<?= $Page->ref_iva->editAttributes() ?> aria-describedby="x_ref_iva_help">
<?= $Page->ref_iva->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ref_iva->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ref_islr->Visible) { // ref_islr ?>
    <div id="r_ref_islr" class="form-group row">
        <label id="elh_compra_ref_islr" for="x_ref_islr" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ref_islr->caption() ?><?= $Page->ref_islr->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ref_islr->cellAttributes() ?>>
<span id="el_compra_ref_islr">
<input type="<?= $Page->ref_islr->getInputTextType() ?>" data-table="compra" data-field="x_ref_islr" data-page="2" name="x_ref_islr" id="x_ref_islr" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ref_islr->getPlaceHolder()) ?>" value="<?= $Page->ref_islr->EditValue ?>"<?= $Page->ref_islr->editAttributes() ?> aria-describedby="x_ref_islr_help">
<?= $Page->ref_islr->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ref_islr->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ref_municipal->Visible) { // ref_municipal ?>
    <div id="r_ref_municipal" class="form-group row">
        <label id="elh_compra_ref_municipal" for="x_ref_municipal" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ref_municipal->caption() ?><?= $Page->ref_municipal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ref_municipal->cellAttributes() ?>>
<span id="el_compra_ref_municipal">
<input type="<?= $Page->ref_municipal->getInputTextType() ?>" data-table="compra" data-field="x_ref_municipal" data-page="2" name="x_ref_municipal" id="x_ref_municipal" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ref_municipal->getPlaceHolder()) ?>" value="<?= $Page->ref_municipal->EditValue ?>"<?= $Page->ref_municipal->editAttributes() ?> aria-describedby="x_ref_municipal_help">
<?= $Page->ref_municipal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ref_municipal->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
    </div><!-- /multi-page tabs .tab-content -->
</div><!-- /multi-page tabs -->
</div><!-- /multi-page -->
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
    ew.addEventHandlers("compra");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    $(document).ready((function(){"FC"==$("#x_tipo_documento").val()?$("#r_doc_afectado").hide():$("#r_doc_afectado").show()})),$("#x_tipo_documento").change((function(){"FC"==$("#x_tipo_documento").val()?$("#r_doc_afectado").hide():$("#r_doc_afectado").show()}));
});
</script>
