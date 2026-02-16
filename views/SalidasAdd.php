<?php

namespace PHPMaker2021\mandrake;

// Page object
$SalidasAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fsalidasadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fsalidasadd = currentForm = new ew.Form("fsalidasadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "salidas")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.salidas)
        ew.vars.tables.salidas = currentTable;
    fsalidasadd.addFields([
        ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null, ew.Validators.integer], fields.cliente.isInvalid],
        ["documento", [fields.documento.visible && fields.documento.required ? ew.Validators.required(fields.documento.caption) : null], fields.documento.isInvalid],
        ["doc_afectado", [fields.doc_afectado.visible && fields.doc_afectado.required ? ew.Validators.required(fields.doc_afectado.caption) : null], fields.doc_afectado.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["lista_pedido", [fields.lista_pedido.visible && fields.lista_pedido.required ? ew.Validators.required(fields.lista_pedido.caption) : null], fields.lista_pedido.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["asesor", [fields.asesor.visible && fields.asesor.required ? ew.Validators.required(fields.asesor.caption) : null], fields.asesor.isInvalid],
        ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
        ["ci_rif", [fields.ci_rif.visible && fields.ci_rif.required ? ew.Validators.required(fields.ci_rif.caption) : null], fields.ci_rif.isInvalid],
        ["nro_despacho", [fields.nro_despacho.visible && fields.nro_despacho.required ? ew.Validators.required(fields.nro_despacho.caption) : null], fields.nro_despacho.isInvalid],
        ["igtf", [fields.igtf.visible && fields.igtf.required ? ew.Validators.required(fields.igtf.caption) : null], fields.igtf.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fsalidasadd,
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
    fsalidasadd.validate = function () {
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
    fsalidasadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsalidasadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fsalidasadd.lists.cliente = <?= $Page->cliente->toClientList($Page) ?>;
    fsalidasadd.lists.documento = <?= $Page->documento->toClientList($Page) ?>;
    fsalidasadd.lists.moneda = <?= $Page->moneda->toClientList($Page) ?>;
    fsalidasadd.lists.lista_pedido = <?= $Page->lista_pedido->toClientList($Page) ?>;
    fsalidasadd.lists.asesor = <?= $Page->asesor->toClientList($Page) ?>;
    fsalidasadd.lists.descuento = <?= $Page->descuento->toClientList($Page) ?>;
    fsalidasadd.lists.ci_rif = <?= $Page->ci_rif->toClientList($Page) ?>;
    fsalidasadd.lists.igtf = <?= $Page->igtf->toClientList($Page) ?>;
    loadjs.done("fsalidasadd");
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
<form name="fsalidasadd" id="fsalidasadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="salidas">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento" class="form-group row">
        <label id="elh_salidas_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_salidas_tipo_documento">
<input type="<?= $Page->tipo_documento->getInputTextType() ?>" data-table="salidas" data-field="x_tipo_documento" name="x_tipo_documento" id="x_tipo_documento" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->tipo_documento->getPlaceHolder()) ?>" value="<?= $Page->tipo_documento->EditValue ?>"<?= $Page->tipo_documento->editAttributes() ?> aria-describedby="x_tipo_documento_help">
<?= $Page->tipo_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tipo_documento->getErrorMessage() ?></div>
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
loadjs.ready(["fsalidasadd"], function() {
    fsalidasadd.createAutoSuggest(Object.assign({"id":"x_cliente","forceSelect":true}, ew.vars.tables.salidas.fields.cliente.autoSuggestOptions));
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
    <select
        id="x_documento"
        name="x_documento"
        class="form-control ew-select<?= $Page->documento->isInvalidClass() ?>"
        data-select2-id="salidas_x_documento"
        data-table="salidas"
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
    var el = document.querySelector("select[data-select2-id='salidas_x_documento']"),
        options = { name: "x_documento", selectId: "salidas_x_documento", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.salidas.fields.documento.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.salidas.fields.documento.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
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
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
    <div id="r_lista_pedido" class="form-group row">
        <label id="elh_salidas_lista_pedido" for="x_lista_pedido" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lista_pedido->caption() ?><?= $Page->lista_pedido->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->lista_pedido->cellAttributes() ?>>
<span id="el_salidas_lista_pedido">
    <select
        id="x_lista_pedido"
        name="x_lista_pedido"
        class="form-control ew-select<?= $Page->lista_pedido->isInvalidClass() ?>"
        data-select2-id="salidas_x_lista_pedido"
        data-table="salidas"
        data-field="x_lista_pedido"
        data-value-separator="<?= $Page->lista_pedido->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->lista_pedido->getPlaceHolder()) ?>"
        <?= $Page->lista_pedido->editAttributes() ?>>
        <?= $Page->lista_pedido->selectOptionListHtml("x_lista_pedido") ?>
    </select>
    <?= $Page->lista_pedido->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->lista_pedido->getErrorMessage() ?></div>
<?= $Page->lista_pedido->Lookup->getParamTag($Page, "p_x_lista_pedido") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='salidas_x_lista_pedido']"),
        options = { name: "x_lista_pedido", selectId: "salidas_x_lista_pedido", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.salidas.fields.lista_pedido.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
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
loadjs.ready(["fsalidasadd"], function() {
    fsalidasadd.createAutoSuggest(Object.assign({"id":"x_descuento","forceSelect":false}, ew.vars.tables.salidas.fields.descuento.autoSuggestOptions));
});
</script>
<?= $Page->descuento->Lookup->getParamTag($Page, "p_x_descuento") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <div id="r_ci_rif" class="form-group row">
        <label id="elh_salidas_ci_rif" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ci_rif->caption() ?><?= $Page->ci_rif->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ci_rif->cellAttributes() ?>>
<span id="el_salidas_ci_rif">
<template id="tp_x_ci_rif">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="salidas" data-field="x_ci_rif" name="x_ci_rif" id="x_ci_rif"<?= $Page->ci_rif->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_ci_rif" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_ci_rif"
    name="x_ci_rif"
    value="<?= HtmlEncode($Page->ci_rif->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_ci_rif"
    data-target="dsl_x_ci_rif"
    data-repeatcolumn="5"
    class="form-control<?= $Page->ci_rif->isInvalidClass() ?>"
    data-table="salidas"
    data-field="x_ci_rif"
    data-value-separator="<?= $Page->ci_rif->displayValueSeparatorAttribute() ?>"
    <?= $Page->ci_rif->editAttributes() ?>>
<?= $Page->ci_rif->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ci_rif->getErrorMessage() ?></div>
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
</div><!-- /page* -->
<?php
    if (in_array("entradas_salidas", explode(",", $Page->getCurrentDetailTable())) && $entradas_salidas->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("entradas_salidas", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "EntradasSalidasGrid.php" ?>
<?php } ?>
<?php
    if (in_array("pagos", explode(",", $Page->getCurrentDetailTable())) && $pagos->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("pagos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "PagosGrid.php" ?>
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
    ew.addEventHandlers("salidas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $(document).ready(function(){
    	var tipo = "<?php echo $_REQUEST["tipo"]; ?>";
        $("#x_tipo_documento").val(tipo);
        $('#x_tipo_documento').prop('readonly', true);
    	$('#sv_x_descuento').attr('readonly', true);
    });
    $("#x_cliente").change(function(){
    	var cliente = $("#x_cliente").val();
    	$.ajax({
    	  url : "include/busca_saldo_cliente.php",
    	  type: "GET",
    	  data : {id: cliente},
    	  beforeSend: function(){
    	    $("#elh_salidas_cliente").html("Espere. . . ");
    	  }
    	})
    	.done(function(data) {
    		//alert(data);
    		$("#elh_salidas_cliente").html(data);
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    		//alert( "complete" );
    		//$("#result").html("Espere. . . ");
    	});
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
});
</script>
