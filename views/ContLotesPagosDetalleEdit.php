<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContLotesPagosDetalleEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_lotes_pagos_detalleedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fcont_lotes_pagos_detalleedit = currentForm = new ew.Form("fcont_lotes_pagos_detalleedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_lotes_pagos_detalle")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_lotes_pagos_detalle)
        ew.vars.tables.cont_lotes_pagos_detalle = currentTable;
    fcont_lotes_pagos_detalleedit.addFields([
        ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null, ew.Validators.integer], fields.proveedor.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(7)], fields.fecha.isInvalid],
        ["tipodoc", [fields.tipodoc.visible && fields.tipodoc.required ? ew.Validators.required(fields.tipodoc.caption) : null], fields.tipodoc.isInvalid],
        ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
        ["monto_a_pagar", [fields.monto_a_pagar.visible && fields.monto_a_pagar.required ? ew.Validators.required(fields.monto_a_pagar.caption) : null, ew.Validators.float], fields.monto_a_pagar.isInvalid],
        ["monto_pagado", [fields.monto_pagado.visible && fields.monto_pagado.required ? ew.Validators.required(fields.monto_pagado.caption) : null, ew.Validators.float], fields.monto_pagado.isInvalid],
        ["saldo", [fields.saldo.visible && fields.saldo.required ? ew.Validators.required(fields.saldo.caption) : null, ew.Validators.float], fields.saldo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_lotes_pagos_detalleedit,
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
    fcont_lotes_pagos_detalleedit.validate = function () {
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
    fcont_lotes_pagos_detalleedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_lotes_pagos_detalleedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_lotes_pagos_detalleedit.lists.proveedor = <?= $Page->proveedor->toClientList($Page) ?>;
    loadjs.done("fcont_lotes_pagos_detalleedit");
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
<form name="fcont_lotes_pagos_detalleedit" id="fcont_lotes_pagos_detalleedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_lotes_pagos_detalle">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "cont_lotes_pagos") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="cont_lotes_pagos">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->cont_lotes_pago->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <div id="r_proveedor" class="form-group row">
        <label id="elh_cont_lotes_pagos_detalle_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proveedor->caption() ?><?= $Page->proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->proveedor->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_proveedor">
<?php
$onchange = $Page->proveedor->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->proveedor->EditAttrs["onchange"] = "";
?>
<span id="as_x_proveedor" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->proveedor->getInputTextType() ?>" class="form-control" name="sv_x_proveedor" id="sv_x_proveedor" value="<?= RemoveHtml($Page->proveedor->EditValue) ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->proveedor->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->proveedor->getPlaceHolder()) ?>"<?= $Page->proveedor->editAttributes() ?> aria-describedby="x_proveedor_help">
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->proveedor->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_proveedor',m:0,n:10,srch:true});" class="ew-lookup-btn btn btn-default"<?= ($Page->proveedor->ReadOnly || $Page->proveedor->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-input="sv_x_proveedor" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->proveedor->displayValueSeparatorAttribute() ?>" name="x_proveedor" id="x_proveedor" value="<?= HtmlEncode($Page->proveedor->CurrentValue) ?>"<?= $onchange ?>>
<?= $Page->proveedor->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->proveedor->getErrorMessage() ?></div>
<script>
loadjs.ready(["fcont_lotes_pagos_detalleedit"], function() {
    fcont_lotes_pagos_detalleedit.createAutoSuggest(Object.assign({"id":"x_proveedor","forceSelect":false}, ew.vars.tables.cont_lotes_pagos_detalle.fields.proveedor.autoSuggestOptions));
});
</script>
<?= $Page->proveedor->Lookup->getParamTag($Page, "p_x_proveedor") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_cont_lotes_pagos_detalle_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_fecha" data-format="7" name="x_fecha" id="x_fecha" maxlength="10" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcont_lotes_pagos_detalleedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fcont_lotes_pagos_detalleedit", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipodoc->Visible) { // tipodoc ?>
    <div id="r_tipodoc" class="form-group row">
        <label id="elh_cont_lotes_pagos_detalle_tipodoc" for="x_tipodoc" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipodoc->caption() ?><?= $Page->tipodoc->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipodoc->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_tipodoc">
<input type="<?= $Page->tipodoc->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" name="x_tipodoc" id="x_tipodoc" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->tipodoc->getPlaceHolder()) ?>" value="<?= $Page->tipodoc->EditValue ?>"<?= $Page->tipodoc->editAttributes() ?> aria-describedby="x_tipodoc_help">
<?= $Page->tipodoc->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tipodoc->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento" class="form-group row">
        <label id="elh_cont_lotes_pagos_detalle_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_nro_documento">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" name="x_nro_documento" id="x_nro_documento" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" value="<?= $Page->nro_documento->EditValue ?>"<?= $Page->nro_documento->editAttributes() ?> aria-describedby="x_nro_documento_help">
<?= $Page->nro_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_a_pagar->Visible) { // monto_a_pagar ?>
    <div id="r_monto_a_pagar" class="form-group row">
        <label id="elh_cont_lotes_pagos_detalle_monto_a_pagar" for="x_monto_a_pagar" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_a_pagar->caption() ?><?= $Page->monto_a_pagar->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_a_pagar->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_monto_a_pagar">
<input type="<?= $Page->monto_a_pagar->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" name="x_monto_a_pagar" id="x_monto_a_pagar" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto_a_pagar->getPlaceHolder()) ?>" value="<?= $Page->monto_a_pagar->EditValue ?>"<?= $Page->monto_a_pagar->editAttributes() ?> aria-describedby="x_monto_a_pagar_help">
<?= $Page->monto_a_pagar->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_a_pagar->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_pagado->Visible) { // monto_pagado ?>
    <div id="r_monto_pagado" class="form-group row">
        <label id="elh_cont_lotes_pagos_detalle_monto_pagado" for="x_monto_pagado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_pagado->caption() ?><?= $Page->monto_pagado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_pagado->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_monto_pagado">
<input type="<?= $Page->monto_pagado->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagado" name="x_monto_pagado" id="x_monto_pagado" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto_pagado->getPlaceHolder()) ?>" value="<?= $Page->monto_pagado->EditValue ?>"<?= $Page->monto_pagado->editAttributes() ?> aria-describedby="x_monto_pagado_help">
<?= $Page->monto_pagado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_pagado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
    <div id="r_saldo" class="form-group row">
        <label id="elh_cont_lotes_pagos_detalle_saldo" for="x_saldo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->saldo->caption() ?><?= $Page->saldo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->saldo->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_detalle_saldo">
<input type="<?= $Page->saldo->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" name="x_saldo" id="x_saldo" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->saldo->getPlaceHolder()) ?>" value="<?= $Page->saldo->EditValue ?>"<?= $Page->saldo->editAttributes() ?> aria-describedby="x_saldo_help">
<?= $Page->saldo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->saldo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_Id" data-hidden="1" name="x_Id" id="x_Id" value="<?= HtmlEncode($Page->Id->CurrentValue) ?>">
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
    ew.addEventHandlers("cont_lotes_pagos_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
